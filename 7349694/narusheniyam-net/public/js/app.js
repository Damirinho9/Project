document.addEventListener('DOMContentLoaded', () => {
  // Мобильная навигация сохраняет доступность через aria-expanded.
  const toggle = document.querySelector('.nav-toggle');
  const nav = document.querySelector('.main-nav');
  toggle?.addEventListener('click', () => { const open = nav.classList.toggle('is-open'); toggle.setAttribute('aria-expanded', String(open)); });

  // Слайдер: 3 изображения, ручное управление и автоматическое переключение каждые 5 секунд.
  const slider = document.querySelector('[data-slider]');
  if (slider) {
    const slides = [...slider.querySelectorAll('[data-slide]')]; let index = 0; let timer;
    const dots = slider.querySelector('.slider-dots');
    slides.forEach((_, i) => { const b=document.createElement('button'); b.className='slider-dot'+(i===0?' active':''); b.type='button'; b.setAttribute('aria-label',`Слайд ${i+1}`); b.addEventListener('click',()=>show(i)); dots.appendChild(b); });
    const dotNodes=[...dots.children];
    function show(next){ index=(next+slides.length)%slides.length; slides.forEach((s,i)=>{s.classList.toggle('is-active',i===index);s.setAttribute('aria-hidden',String(i!==index));});dotNodes.forEach((d,i)=>d.classList.toggle('active',i===index));restart(); }
    function restart(){ clearInterval(timer); timer=setInterval(()=>show(index+1),5000); }
    slider.querySelector('[data-prev]').addEventListener('click',()=>show(index-1));
    slider.querySelector('[data-next]').addEventListener('click',()=>show(index+1));
    restart();
  }

  // Маска телефона нормализует только цифры, а сервер повторно проверяет итоговый формат.
  document.querySelectorAll('[data-phone-mask]').forEach(input => input.addEventListener('input', () => {
    let digits=input.value.replace(/\D/g,'').replace(/^8/,'7'); if(!digits.startsWith('7')) digits='7'+digits; digits=digits.slice(0,11);
    const p=digits.slice(1); let v='+7'; if(p.length)v+='('+p.slice(0,3); if(p.length>=3)v+=')'+p.slice(3,6); if(p.length>=6)v+='-'+p.slice(6,8); if(p.length>=8)v+='-'+p.slice(8,10); input.value=v;
  }));
  document.querySelectorAll('[data-date-mask]').forEach(input => input.addEventListener('input', () => { let d=input.value.replace(/\D/g,'').slice(0,8); input.value=[d.slice(0,2),d.slice(2,4),d.slice(4,8)].filter(Boolean).join('.'); }));

  // Клиентская валидация дополняет, но не заменяет Laravel FormRequest.
  document.querySelectorAll('form[data-validate]').forEach(form => form.addEventListener('submit', (event) => {
    let valid=true; form.querySelectorAll('[required]').forEach(field => { field.setCustomValidity(''); if(!field.checkValidity()) valid=false; });
    const fio=form.querySelector('[data-rule="fio"]'); if(fio && !/^[А-Яа-яЁё]+(?:-[А-Яа-яЁё]+)*\s+[А-Яа-яЁё]+(?:-[А-Яа-яЁё]+)*\s+[А-Яа-яЁё]+(?:-[А-Яа-яЁё]+)*$/u.test(fio.value.trim())) { fio.setCustomValidity('Введите ровно три слова кириллицей.'); valid=false; }
    if(!valid){ event.preventDefault(); form.reportValidity(); }
  }));

  // Перед изменением статуса admin подтверждает действие в нативном модальном dialog.
  const dialog=document.querySelector('#status-dialog'); let pendingForm=null;
  document.querySelectorAll('[data-status-form]').forEach(form => form.addEventListener('submit', e => { if(!dialog) return; e.preventDefault(); pendingForm=form; dialog.showModal(); }));
  dialog?.addEventListener('close', () => { if(dialog.returnValue==='confirm' && pendingForm){ const f=pendingForm; pendingForm=null; f.submit(); } else pendingForm=null; });
});
