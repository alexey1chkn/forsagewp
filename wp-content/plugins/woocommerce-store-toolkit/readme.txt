=== WooCommerce - Store Toolkit ===

Contributors: visser, visser.labs
Donate link: https://www.visser.com.au/donations/
Tags: woocommerce, mod, delete store, clean store, nuke, store toolkit
Requires at least: 2.9.2
Tested up to: 5.0
Stable tag: 2.0.2
License: GPLv2 or later

== Description ==

Store Toolkit includes a growing set of commonly-used WooCommerce administration tools aimed at web developers and store maintainers.

= Features =

Features include:

**WooCommerce maintainence/debugging tools**

* Re-link rogue Products to the Simple Product Type
* Delete corrupt Variations
* Refresh Product Transients
* Auto-complete Orders with 0 totals
* Unlock the Edit Product screen for Product Variations
* All in One SEO Pack integration for Products
* Show Order custom Post meta from the Edit Order screen
* Show Order Items custom Post meta on the Edit Order screen
* Show Refund custom Post meta on the Edit Order screen
* Show Subscription custom Post meta box on the Edit Subscription screen
* Show Product custom Post meta box on the Edit Product screen
* Show Coupon custom Post meta box on the Edit Coupon screen
* Show Product Category custom Term meta box on the Edit Category screen
* Show custom User meta box on the Edit User screen
* Show Event custom Post meta box on the Edit Ticket screen
* Show Booking custom Post meta box on the Edit Booking screen
* Show Membership Plan custom Post meta box on the Edit Membership Plan screen
* Show User Membership custom Post meta box on the Edit User Membership Plan screen
* List of all registered WordPress Image Sizes on WooCommerce > System Status screen
* View and manage existing Orders of customers from the User Profile screen
* View number of Orders linked to each User from the Users screen
* Filter Orders by Billing Country from the Orders screen
* Filter Orders by Shipping Country from the Orders screen
* Filter Orders by Payment Method from the Orders screen
* WP-CLI support for limitless nukes

**Nuke support for clearing WooCommerce store records**

* Products
* Variations
* Product Categories
* Product Tags
* Product Brands
* Product Vendors
* Product Images
* Product Attributes
* WooCommerce Logs
* Orders
* Order Items
* Tax Rates
* Download Permissions
* Coupons
* Shipping Classes
* WooCommerce Logs
* Advanced Google Product Feed
* Delete Products by Product Category
* Delete Orders by Order Status
* Delete Orders by Order Date
* Bulk permanently Delete Products from the Edit Products screen

**Nuke support for clearing WordPress records**

* Posts
* Post Categories
* Post Tags
* Links
* Comments
* Media: Images

If you find yourself in the situation where you need to start over with a fresh installation of WooCommerce then a 'nuke' will do the job.

**Coming soon**

* Got more ideas? Tell me!

Want regular updates? Become a fan on Facebook!

http://www.facebook.com/visser.labs/

For more information visit: http://www.visser.com.au/woocommerce/

== Installation ==

1. Upload the folder 'woocommerce-store-toolkit' to the '/wp-content/plugins/' directory
2. Activate 'WooCommerce - Store Toolkit' through the 'Plugins' menu in WordPress

That's it!

== Usage ==

1. Open WooCommerce > Store Toolkit
2. Select which WooCommerce details you would like to remove and click Nuke

Done!

== Frequently Asked Questions ==

**Where can I request new features?**

You can vote on and request new features and extensions on our Support Forum, see http://www.visser.com.au

**Where can I report bugs or contribute to the project?**

Bugs can be reported here on WordPress.org or on our Support Forum, see http://www.visser.com.au

== Support ==

If you have any problems, questions or suggestions please join the members discussion on my WooCommerce dedicated forum.

http://www.visser.com.au/woocommerce/forums/

== Changelog ==

= 2.0.2 =
* Added: WordPress Action to allow developers to extend individual nuke types

= 2.0.1 =
* Fixed: Fatal PHP error on Edit User screen where Classes are serialized as custom User meta (thanks Pavle)
* Fixed: PHP warning notice on Users screen where no Orders are returned for a User (thanks Michael)

= 2.0 =
* Added: Post Types screen to Store Toolkit (thanks Rob)
* Added: Custom Post meta to the Advanced Custom Fields Group Field screen (thanks Rob)
* Fixed: Custom Term meta not showing on Edit Category, Edit Tag, Edit Brand screens (thanks Xavier)
* Added: Actions column to Edit Category, Edit Tag and Edit Brand Term meta tables
* Added: User ID column to Orders screen
* Added: Generate sample Orders
* Fixed: Nuking Orders would result in only 100 removed

= 1.9.2 =
* Fixed: Delete Products by Category ignoring filter (thanks Rene)

= 1.9.1 =
* Fixed: Date format incorrect on Edit User/Edit Profile screen (thanks Francisco)

= 1.9 =
* Added: Validation to the Delete Products by Product Category section
* Added: Validation to the Delete Orders by Order Status section
* Fixed: Do not filter deleted Orders by date if not selected (thanks @cleverpixel)
* Changed: Moved Javascript from inline to toolkit.js
* Added: Confirmation popup for filter nukes (thanks @alucard001)

= 1.8.3 =
* Added: Pagination to the User Orders page of the My Profile screen (thanks Michael)
* Changed: Re-added Remove buttons to individual nuke filters (thanks @onlineatwork)

= 1.8.2 =
* Fixed: Fatal PHP error on older PHP sites

= 1.8.1 =
* Added: Tool to delete obviously corrupt Variations (thanks Rupesh)

= 1.8 =
* Added: Delete Orders by Order Date (thanks @cleverpixel and @coryinthelou)

= 1.7.9 =
* Added: Refunds Post meta to the Edit Order screen (thanks Lawrence)

= 1.7.8 =
* Added: Nuke WooCommerce logs
* Added: WP-CLI support for tactical nukes

= 1.7.7 =
* Added: Export button support to the Profile/Edit User screen
* Added: Custom Post meta for WooCommerce Subscriptions

= 1.7.6 =
* Added: Category heirachy detail to Edit Category screen
* Added: Category heirachy depth to Edit Category screen
* Added: Export button support for EPO details within the Edit Order screen

= 1.7.5 =
* Changed: Styling of Post meta meta boxes to striped
* Added: Actions button support to Orders, Order Items, Coupons, Products
* Added: Custom Post meta to Export Template CPT

= 1.7.4 =
* Fixed: Really fixed Order nuking getting stuck at 10 for some users

= 1.7.3 =
* Fixed: Order nuking getting stuck at 10 for some users
* Added: wp_cache_delete() to try and force Post count refreshes
* Added debug_log() for spotting permanent nuke loops

= 1.7.2 =
* Added: WooCommerce Memberships support

= 1.7.1 =
* Fixed: Dashboard crashing when WooCommerce is deactivated
* Fixed: PHP warnings on User detail screen
* Added: WOO_ST_DEBUG for controlling debug notices
* Fixed: WooCommerce 3.0 compatibility

= 1.7 =
* Fixed: Performance issue on Sales Summary widget refreshing every screenload
* Added: Refresh Store Sales Summary Transient counts hourly
* Added: Add custom Product meta to Store Exporter Deluxe via Edit Product screen (thanks Stephen)

= 1.6.9 =
* Added: Additional WordPress Filters
* Fixed: Save changes on Tools screen not working
* Added: Attachment meta box

= 1.6.8 =
* Added: WordPress Filters for supporting tactical nukes (thanks Peter)
* Added: Migrated to WP_Query over get_posts for Order nukes

= 1.6.7 =
* Added: Permanent loop detection for failed nukes
* Added: Sales Summary Dashboard widget
* Added: Nuke Store Exports
* Added: Stopwatch to Admin footer

= 1.6.6 =
* Added: Filter Orders by Billing Country to Orders screen
* Added: Filter Orders by Shipping Country to Orders screen
* Added: Filter Orders by Payment method to Orders screen
* Added: WordPress Filters to toggle new Order filters
* Added: Stopwatch to WordPress Administration screens
* Added: CRON nuke engine for automated nuking

= 1.6.5 =
* Added: WooCommerce Right Now Dashboard Widget
* Added: Orders column to Users screen
* Added: User Orders dialog to User Detail screen
* Fixed: Delete Orders by Order Status

= 1.6.4 =
* Added: Image Sizes section to WooCommerce > System Status

= 1.6.3 =
* Added: Generate Sample Products
* Added: Product Name template for Generate Sample Products
* Added: SKU template for Generate Sample Products
* Added: Short description template for Generate Sample Products
* Added: Description template for Generate Sample Products
* Added: Nuke Shipping Classes

= 1.6.2 =
* Added: Reset Product Transients to Tools screen

= 1.6.1 =
* Added: Booking mta box support for WooCommerce Bookings

= 1.6 =
* Added: Unlock the Edit Product screen for Product Variations

= 1.5.9 =
* Added: Event meta box support for WooCommerce Events

= 1.5.8 =
* Fixed: Privilege escalation vulnerability (thanks jamesgol)

= 1.5.7 =
* Fixed: Privilege escalation vulnerability (thanks panVagenas)
* Added: Remove WooCommerce Product Transients

= 1.5.6 =
* Fixed: Attributes screen not updating after Attribute nuke
* Fixed: Delete WooCommerce Attribute Transient
* Fixed: Delete WooCommerce Featured Products Transient

= 1.5.5 =
* Added: WordPress Filter for overriding the default product_brand Term Taxonomy

= 1.5.4 =
* Added: Nuke support for Advanced Google Product Feed

= 1.5.3 =
* Fixed: Attribute nuke skipping Terms
* Added: Auto-complete Orders with 0 totals

= 1.5.2 =
* Added: Subscription Post meta box to Subscription Edit screen
* Added: User Post meta box to User Edit screen
* Added: WordPress.org translation support
* Fixed: Order nuke skipping Terms

= 1.5.1 =
* Fixed: add_query_arg() usage in Plugin
* Added: Permanently Delete Products from Edit Products screen

= 1.5 =
* Added: Delete Download Permissions on Orders nuked

= 1.4.9 =
* Fixed: Taxonomy detection prior to counts
* Added: Custom Post meta box to Product Variation Edit screen

= 1.4.8 =
* Added: Tools tab for non-nuke actions
* Added: Re-link rogue Products to Simple Product Type
* Fixed: Common white screen and 500 Internal Server Error notices
* Added: Explanation of nuke process while nuking
* Added: Retry notice after nuke fails mid-nuke

= 1.4.7 =
* Added: Product Brands support

= 1.4.6 =
* Added: User capability check 'manage_options' for Meta boxes

= 1.4.5 =
* Added: Custom User meta box to Edit User

= 1.4.4 =
* Changed: Renamed mislabeled Category Term meta
* Fixed: PHP warning on image nuke

= 1.4.3 =
* Changed: Renamed meta box template files
* Fixed: Nuke Product Images when no Products exist
* Added: Coupon Post meta to Add/Edit Coupon screen
* Added: Category Term meta to Edit Category screen

= 1.4.2 =
* Fixed: Delete both product and product_variation Post Types
* Fixed: Delete Orders in WooCommerce 2.2+
* Fixed: Remove Terms linked to Products

= 1.4.1 =
* Fixed: WooCommerce 2.2+ compatibility

= 1.4 =
* Added: Support for nuking all images within WordPress Media

= 1.3.9 =
* Fixed: Reduced memory usage when bulk deleting large catalogues

= 1.3.8 =
* Added: Order Meta widget on Orders screen
* Added: Order Cart Item widget on Orders screen

= 1.3.7 =
* Fixed: Missing icon
* Changed: Layout styling of descriptions on Nuke screen
* Added: Screenshots to Plugin page

= 1.3.6 =
* Changed: Plugin description
* Fixed: Updated URL for WooCommerce Plugin News widget

= 1.3.5 =
* Changed: Removed woo_admin_is_valid_icon
* Changed: Using default WooCommerce icons

= 1.3.4 =
* Added: Select All options to Nuke

= 1.3.3 =
* Fixed: Coupons removal

= 1.3.2 =
* Added: Per-Category Product nuking
* Added: Tabs support
* Changed: Removed Tools menu reference

= 1.3.1 =
* Added: Store Toolkit menu item under WooCommerce

= 1.3 =
* Added: Attributes support

= 1.2 =
* Changed: Cleaned up markup
* Added: Remove WooCommerce term details when removing Categories

= 1.1 =
* Fixed: Dashboard widget URL out of date

= 1.0 =
* Added: Delete Products, Product Categories, Tags and Orders
* Added: First working release of the Plugin

== Upgrade Notice == 



== Screenshots ==

1. The Store Toolkit overview screen.
2. The Nuke WooCommerce screen with 'nuke' options.
3. Additional 'nuke' options for WordPress details.

== Disclaimer ==

It is not responsible for any harm or wrong doing this Plugin may cause. Users are fully responsible for their own use. This Plugin is to be used WITHOUT warranty.