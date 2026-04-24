<!-- Begin footer.tpl -->
</main>
</div><!-- /.app-shell -->

<footer class="border-top bg-white text-muted small py-3 mt-0">
    <div class="container-fluid text-center">
        <div>
            &copy; 2005&nbsp;-&nbsp;{php}echo date('Y');{/php}
            Cite CRM
            <a href="http://www.incitecrm.com" target="new">www.incitecrm.com</a> — All rights reserved.
        </div>
        <div>
            All rights reserved.
        </div>
    </div>
</footer>

<!-- Bootstrap Bundle JS (with Popper) -->
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

<!-- Sidebar calendar JS (used in left sidebar widget) -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

{literal}
<script type="text/javascript">
	(function () {
		var storageKey = 'app_sidebar_collapsed';
		var collapsedClass = 'app-sidebar-collapsed';

		function setCollapsed(isCollapsed) {
			if (isCollapsed) {
				document.body.classList.add(collapsedClass);
				try { localStorage.setItem(storageKey, '1'); } catch (e) {}

				document.querySelectorAll('.app-sidebar-nav details.app-nav-group').forEach(function (d) {
					d.open = false;
				});
			} else {
				document.body.classList.remove(collapsedClass);
				try { localStorage.setItem(storageKey, '0'); } catch (e) {}

				var activeGroup =
					document.querySelector('.app-sidebar-nav details.app-nav-group summary.app-nav-item.active') ||
					document.querySelector('.app-sidebar-nav details.app-nav-group .app-nav-subitem.active');
				if (activeGroup) {
					var detail = activeGroup.closest('details.app-nav-group');
					if (detail) detail.open = true;
				}
			}
		}

		// Apply saved state on load (desktop only via CSS, but harmless everywhere)
		try {
			if (localStorage.getItem(storageKey) === '1') {
				document.body.classList.add(collapsedClass);
			}
		} catch (e) {}

		document.querySelectorAll('[data-sidebar-collapse]').forEach(function (btn) {
			btn.addEventListener('click', function () {
				setCollapsed(!document.body.classList.contains(collapsedClass));
			});
		});
	})();
</script>
{/literal}

</body>
</html>
