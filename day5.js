document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('form[data-entry-form]').forEach(function(form) {
    const name=form.querySelector('#name'), email=form.querySelector('#email'), phone=form.querySelector('#phone');
    function validate(field) {
      const value=field.value.trim();
      const valid=field===name ? value.length>=3 : field===email ? /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value) : /^[0-9]{10}$/.test(value);
      field.classList.toggle('is-valid', valid); field.classList.toggle('is-invalid', !valid);
      return valid;
    }
    [name,email,phone].forEach(function(field) {field.addEventListener('input', function(){
      if(field===name) field.value=field.value.toUpperCase();
      if(field===phone) field.value=field.value.replace(/[^0-9]/g,'').slice(0,10);
      validate(field);
    });});
    form.addEventListener('submit', function(e){if(![name,email,phone].every(validate)){e.preventDefault(); e.stopImmediatePropagation();}});
    form.addEventListener('reset', function(){setTimeout(function(){[name,email,phone].forEach(function(f){f.classList.remove('is-valid','is-invalid');});},0);});
  });
  const table=document.getElementById('entriesTable');
  if(table){
    table.querySelectorAll('th[data-sort]').forEach(function(th){
      th.style.cursor='pointer'; th.tabIndex=0; th.setAttribute('role','button');
      function sort(){const idx=Number(th.dataset.sort), tbody=table.tBodies[0], rows=Array.from(tbody.rows).filter(r=>r.cells.length>1); const asc=th.dataset.direction!=='asc'; rows.sort((a,b)=>a.cells[idx].textContent.trim().localeCompare(b.cells[idx].textContent.trim(),undefined,{numeric:true})*(asc?1:-1)); rows.forEach(r=>tbody.appendChild(r)); table.querySelectorAll('th[data-sort]').forEach(h=>h.removeAttribute('aria-sort')); th.dataset.direction=asc?'asc':'desc'; th.setAttribute('aria-sort',asc?'ascending':'descending');}
      th.addEventListener('click',sort); th.addEventListener('keydown',e=>{if(e.key==='Enter'||e.key===' '){e.preventDefault();sort();}});
    });
    table.querySelectorAll('tbody tr').forEach(function(row){row.addEventListener('mouseenter',()=>row.classList.add('table-active'));row.addEventListener('mouseleave',()=>row.classList.remove('table-active'));});
    const modal=document.getElementById('deleteModal');
    if(modal) modal.addEventListener('show.bs.modal',function(e){const btn=e.relatedTarget; modal.querySelector('input[name="id"]').value=btn.dataset.id;modal.querySelector('.entry-label').textContent=btn.dataset.name;});
  }
});
