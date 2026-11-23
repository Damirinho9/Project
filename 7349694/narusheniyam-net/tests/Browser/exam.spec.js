const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');

const qaDir = path.resolve('qa/screenshots');
fs.mkdirSync(qaDir, { recursive: true });

function ruToday() {
  const d = new Date();
  const dd = String(d.getDate()).padStart(2, '0');
  const mm = String(d.getMonth() + 1).padStart(2, '0');
  return `${dd}.${mm}.${d.getFullYear()}`;
}

async function noHorizontalOverflow(page) {
  return await page.evaluate(() => document.documentElement.scrollWidth <= document.documentElement.clientWidth + 1);
}

async function login(page, login, password) {
  await page.goto('/login');
  await page.locator('#login').fill(login);
  await page.locator('#password').fill(password);
  await page.getByRole('button', { name: 'Авторизация' }).click();
}

async function logout(page) {
  await page.getByRole('button', { name: 'Выход' }).click();
  await expect(page).toHaveURL(/\/$/);
}

test('complete exam workflow and responsive QA', async ({ browser }) => {
  const username = `exam${Date.now()}`;
  const email = `${username}@example.test`;
  const reportTitle = `Яма у школы ${Date.now()}`;

  // Desktop: home, source assets, slider controls and automatic transition.
  const desktop = await browser.newPage({ viewport: { width: 1200, height: 900 } });
  let response = await desktop.goto('/');
  expect(response.status()).toBe(200);
  await expect(desktop.locator('img.brand-logo')).toBeVisible();
  await expect(desktop.locator('.slide img')).toHaveCount(3);
  await expect(desktop.locator('img[src*="slider-1.jpg"]')).toBeVisible();
  expect(await noHorizontalOverflow(desktop)).toBeTruthy();
  await desktop.screenshot({ path: path.join(qaDir, 'home-1200.png'), fullPage: true });

  const first = desktop.locator('[data-slide]').nth(0);
  const second = desktop.locator('[data-slide]').nth(1);
  await expect(first).toHaveClass(/is-active/);
  await desktop.getByRole('button', { name: 'Следующий слайд' }).click();
  await expect(second).toHaveClass(/is-active/);
  await desktop.getByRole('button', { name: 'Предыдущий слайд' }).click();
  await expect(first).toHaveClass(/is-active/);
  await desktop.waitForTimeout(5200);
  await expect(second).toHaveClass(/is-active/);

  // Tablet and mobile viewport overflow checks.
  for (const [width, file] of [[700, 'home-700.png'], [390, 'home-390.png']]) {
    const page = await browser.newPage({ viewport: { width, height: 900 } });
    await page.goto('/');
    expect(await noHorizontalOverflow(page)).toBeTruthy();
    await page.screenshot({ path: path.join(qaDir, file), fullPage: true });
    await page.close();
  }

  // Registration through the real HTML form.
  await desktop.goto('/register');
  await desktop.locator('#fio').fill('Иванов Иван Иванович');
  await desktop.locator('#login').fill(username);
  await desktop.locator('#email').fill(email);
  await desktop.locator('#phone').fill('+7(999)123-45-67');
  await desktop.locator('#password').fill('secret1');
  await desktop.screenshot({ path: path.join(qaDir, 'register.png'), fullPage: true });
  await desktop.getByRole('button', { name: 'Зарегистрироваться' }).click();
  await expect(desktop).toHaveURL(/\/reports\/create$/);

  // Create report.
  await desktop.locator('#title').fill(reportTitle);
  await desktop.locator('#category_id').selectOption({ index: 1 });
  await desktop.locator('#description').fill('Большая глубокая яма рядом с главным входом в школу, требуется ремонт покрытия.');
  await desktop.locator('#date_incident').fill(ruToday());
  await desktop.locator('input[name="contact"][value="email"]').check();
  await desktop.getByRole('button', { name: 'Отправить' }).click();
  await expect(desktop).toHaveURL(/\/reports$/);
  await expect(desktop.getByText(reportTitle)).toBeVisible();
  await expect(desktop.getByText('Новое', { exact: true })).toBeVisible();
  await desktop.screenshot({ path: path.join(qaDir, 'user-reports.png'), fullPage: true });

  // Guest auth guard after logout.
  await logout(desktop);
  await desktop.goto('/reports/create');
  await expect(desktop).toHaveURL(/\/login$/);

  // Regular user cannot open admin.
  await login(desktop, username, 'secret1');
  response = await desktop.goto('/admin');
  expect(response.status()).toBe(403);
  await desktop.goto('/');
  await logout(desktop);

  // Seeded admin credentials work and report is visible.
  await login(desktop, 'admin', 'adm123');
  await expect(desktop).toHaveURL(/\/admin$/);
  await expect(desktop.getByText(reportTitle)).toBeVisible();

  // Server-side filter new.
  await desktop.locator('#status').selectOption('new');
  await desktop.getByRole('button', { name: 'Фильтровать' }).click();
  await expect(desktop).toHaveURL(/status=new/);
  await expect(desktop.getByText(reportTitle)).toBeVisible();
  await desktop.screenshot({ path: path.join(qaDir, 'admin-panel.png'), fullPage: true });

  // Change report to resolved through real confirmation dialog.
  const row = desktop.locator('tr', { hasText: reportTitle });
  await row.locator('select[name="status"]').selectOption('resolved');
  await row.getByRole('button', { name: 'Изменить' }).click();
  await expect(desktop.locator('#status-dialog')).toBeVisible();
  await desktop.locator('#status-dialog').getByRole('button', { name: 'Изменить статус' }).click();
  await expect(desktop.getByText('Статус заявки изменён.')).toBeVisible();

  // Footer counter must now be database-driven and equal at least one.
  const footerText = await desktop.locator('.site-footer').innerText();
  expect(footerText).toMatch(/количество решённых проблем:\s*[1-9]\d*/i);
  await logout(desktop);

  // User sees resolved status and can save review.
  await login(desktop, username, 'secret1');
  await desktop.goto('/reports');
  await expect(desktop.getByText(reportTitle)).toBeVisible();
  await expect(desktop.getByText('Решено', { exact: true })).toBeVisible();
  const card = desktop.locator('article.report-card', { hasText: reportTitle });
  await expect(card.getByLabel('Отзыв о качестве работ')).toBeVisible();
  await card.getByLabel('Отзыв о качестве работ').fill('Работы выполнены хорошо, проблема устранена.');
  await card.getByRole('button', { name: 'Сохранить отзыв' }).click();
  await expect(desktop.getByText('Спасибо! Отзыв сохранён.')).toBeVisible();
  await desktop.screenshot({ path: path.join(qaDir, 'resolved-report.png'), fullPage: true });

  await desktop.close();
});
