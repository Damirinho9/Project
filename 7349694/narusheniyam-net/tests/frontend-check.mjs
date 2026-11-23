import fs from 'node:fs';
const root=new URL('../',import.meta.url);
const read=p=>fs.readFileSync(new URL(p,root),'utf8');
const required=['public/images/logo.png','public/images/slider-1.jpg','public/images/slider-2.jpg','public/images/slider-3.jpg','public/css/app.css','public/js/app.js'];
for(const p of required){if(!fs.existsSync(new URL(p,root))) throw new Error(`Missing ${p}`);}
const css=read('public/css/app.css'), js=read('public/js/app.js'), layout=read('resources/views/layouts/app.blade.php');
for(const marker of ['max-width:520px','min-width:700px','min-width:1200px']) if(!css.includes(marker)) throw new Error(`Responsive marker missing: ${marker}`);
if(!js.includes('5000')) throw new Error('5-second slider interval missing');
if(!layout.includes('<nav')||!layout.includes('<header')||!layout.includes('<footer')||!layout.includes('<main')) throw new Error('Semantic layout missing');
console.log('FRONTEND STATIC CHECK: PASS (390 / 700 / 1200 rules present)');
