	</div> 
	<!-- end container -->

	<footer class="footer text-center">
		<div class="container">
			<?php
				if ( has_nav_menu( 'footer-menu' ) ) {
					wp_nav_menu(
						array( 
							'theme_location'  => 'footer-menu',
							'depth' => 2,
							'container' => false,
							'menu_class' => 'footer-menu',
						) 
					);
				} else {
					if ( ! is_admin() ) {
						echo '<h6 class="mt-4"><a href="'. esc_url(admin_url( 'nav-menus.php' )) .'" class="add-menu-link">'; esc_attr_e('Add a menu', 'mugi'); echo '</a></h6>';
					}
				}
			?>
			<span class="copyright">
				<?php echo esc_attr__('© 2020 IFL Inc. All rights reserved.','maanjaa'); ?>
			</span>
		</div>
	</footer>

</main>
<!-- end main layout -->
<!-- Go to top button -->
<a href="javascript:" id="return-to-top"><i class="fas fa-arrow-up"></i></a>

<?php wp_footer(); ?>

</body>
</html>