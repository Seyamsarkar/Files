<?php
header("Content-Type:text/css");
function checkhexcolor($color)
{
    return preg_match('/^#[a-f0-9]{6}$/i', $color);
}

if (isset($_GET['color']) and $_GET['color'] != '') {
    $color = "#" . $_GET['color'];
}

if (!$color or !checkhexcolor($color)) {
    $color = "#336699";
}

?>
.header__top,.btn--base,.product-card .tending-badge,.cart-btn,.testimonial-slider .slick-dots li.slick-active button,.custom--accordion .accordion-button:not(.collapsed),.subscribe-form button,.scroll-to-top,.filter-price-widget .ui-state-default,.header .nav-right .header-top-search-area .header-search-form .header-search-btn,.blog-details__thumb .post__date .date,.sidebar .widget .widget__title::after,.bg--base,.profile-thumb .avatar-edit label,.select2-container--default .select2-results__option--highlighted[aria-selected],.custom--nav-tabs.style--two .nav-item .nav-link.active::after,.action-sidebar-close,.action-sidebar-open,.cart-total-box,.product-thumb-slider-area .product-details-thumb .tending-badge-two,.copied::after,.border-line-title,.border-line-area::before{
background-color: <?php echo $color ?> !important;
}
.preloader .preloader-container .animated-preloader, .preloader .preloader-container .animated-preloader:before, .ui-slider-range, .hero-search-form .hero-search-btn{
background: <?php echo $color ?> !important;
}
.cart-btn:hover{
box-shadow: 0 5px 10px <?php echo $color ?>59 !important;
}
.cart-btn{
box-shadow: 0 3px 8px <?php echo $color ?>bd !important;
}
.text--base,.product-title a:hover,.post-card__title a:hover,.read-more,.short-link-list li a:hover,.header .main-menu li a:hover, .header .main-menu li a:focus,.custom--checkbox input:checked~label::before,.filter-price-widget .price-range input,.product-card__content p a:hover,.product-price-form #product-price,.author-name a:hover,.header .nav-right .header-serch-btn,.post__title a:hover,.user-nav-tabs .nav-item .nav-link.active,.top__bar-left li.active,.single-cart__title a:hover,.socail-list li a:hover{
color: <?php echo $color ?>!important;
}
.custom--nav-tabs .nav-item .nav-link.active, .custom--nav-tabs .nav-item .nav-link:hover{
border-color:<?php echo $color ?>!important;
}
.testimonial-card{
border-top:2px solid <?php echo $color ?> !important;
}
.custom--accordion .accordion-item{
border: 1px solid <?php echo $color ?>80 !important;
}
.custom--accordion .accordion-button{
background-color: <?php echo $color ?>0d !important;
}
.dropdown .dropdown-menu li .dropdown-item:hover{
color: <?php echo $color ?>!important;
background-color: <?php echo $color ?>0d !important;
}
.input-group .input-group-text,.form-check-input:checked{
background-color: <?php echo $color ?> !important;
border-color: <?php echo $color ?> !important;
}
.form--control:focus{
border-color: <?php echo $color ?> !important;
box-shadow: 0 0 5px <?php echo $color ?>59 !important;
}
.filter-price-widget .ui-widget.ui-widget-content::after{
background: <?php echo $color ?>33 !important;
}
.pagination .page-item.active .page-link,.pagination .page-item .page-link:hover,.btn-outline--base:hover,.product-widget-tags a:hover,.blog-details__footer .social__links li a:hover{
background-color: <?php echo $color ?> !important;
}
.pagination .page-item .page-link{
border: 1px solid <?php echo $color ?>40 !important;
}
.btn-outline--base{
color: <?php echo $color ?> !important;
border: 1px solid <?php echo $color ?> !important;
}
.d-widget{
border-left: 3px solid <?php echo $color ?>;
}
.d-widget::after{
background-color: <?php echo $color ?>26 !important;
}
.custom--card .card-header{
background-color: <?php echo $color ?>21;
}
.verification-code span{
border: solid 1px <?php echo $color ?>8c;
color: <?php echo $color ?>c7;
}

.border--base{
border-color: <?php echo $color ?>4f!important;
}
.dashboard-content-wrapper {
border: 1px solid <?php echo $color ?>36;
}
@media(max-width: 1199px) {
.header .main-menu li.menu_has_children:hover>a::before{
color: #fff !important;
}
}
.header .main-menu li.menu_has_children.open .category-name, .header .main-menu li.menu_has_children.open > a::before,.product-widget p i{
color: <?php echo $color ?> !important;
}
.form--check .form-check-input:checked {
background-color: <?php echo $color ?> !important;
border-color: <?php echo $color ?> !important;
box-shadow: none;
}
.form--check:focus .form-check-input:focus,.select2-container--open .select2-selection {
border: 1px solid <?php echo $color ?> !important;
box-shadow: 0 0 5px <?php echo $color ?>59 !important;
}
.select2-container .select2-selection--single,.border--base{
border-color: <?php echo $color ?> !important;
}
.ticket--border{
border-color: <?php echo $color ?>4f !important;
}
.verification-code span{
border: 1px solid <?php echo $color ?>2e;
color:<?php echo $color ?>;
}


.nicParent:has(.nicEdit-selected)>div {
border-color:<?php echo $color ?> !important;
box-shadow: 0 0 5px <?php echo $color ?>59 !important;
}
.product-widget p i.fa-times,.product-widget p i.la-times{
color:#ea5455 !important
}