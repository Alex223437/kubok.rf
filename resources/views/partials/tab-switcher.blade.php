<script>
window.initTabSwitcher = window.initTabSwitcher || function (tabsMap, btnsMap) {
    var tabEls = {};
    Object.keys(tabsMap).forEach(function (k) {
        tabEls[k] = document.getElementById(tabsMap[k]);
    });
    function showTab(active) {
        Object.keys(tabEls).forEach(function (k) {
            if (tabEls[k]) tabEls[k].style.display = k === active ? 'block' : 'none';
            (btnsMap[k] || []).forEach(function (id) {
                var el = document.getElementById(id);
                if (el) el.classList.toggle('is-active', k === active);
            });
        });
    }
    Object.keys(btnsMap).forEach(function (k) {
        (btnsMap[k] || []).forEach(function (id) {
            var el = document.getElementById(id);
            if (el) el.addEventListener('click', function () { showTab(k); });
        });
    });
};
</script>
