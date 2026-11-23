const { defineConfig } = require('@playwright/test');

module.exports = defineConfig({
  testDir: './tests/Browser',
  timeout: 60_000,
  expect: { timeout: 10_000 },
  use: {
    baseURL: process.env.APP_URL || 'http://127.0.0.1:8000',
    trace: 'retain-on-failure'
  },
  outputDir: 'qa/playwright-results'
});
