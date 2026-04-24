    </main>
  </div><!-- end main col -->
</div><!-- end admin-wrap -->

<script>
function adminToast(msg, type='info', duration=4000){
  const c=document.getElementById('admin-toast');
  const icons={success:'✅',error:'❌',info:'ℹ️',warning:'⚠️'};
  const t=document.createElement('div');
  t.className=`a-toast a-toast-${type}`;
  t.innerHTML=`<span>${icons[type]||'ℹ️'}</span><span>${msg}</span>`;
  c.appendChild(t);
  setTimeout(()=>{t.style.opacity='0';setTimeout(()=>t.remove(),400);},duration);
}
// Auto flash messages
document.querySelectorAll('[data-toast]').forEach(el=>{
  adminToast(el.dataset.msg||el.textContent, el.dataset.toast);
  el.remove();
});
// Table search
function filterTable(inputId, tableId){
  const inp=document.getElementById(inputId), tbl=document.getElementById(tableId);
  if(!inp||!tbl)return;
  inp.addEventListener('input',()=>{
    const v=inp.value.toLowerCase();
    tbl.querySelectorAll('tbody tr').forEach(r=>r.style.display=r.textContent.toLowerCase().includes(v)?'':'none');
  });
}
function confirmDelete(url,name){if(confirm(`Delete "${name}"? This cannot be undone.`))window.location.href=url;}
</script>
</body>
</html>
