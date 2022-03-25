(function ($) {
    'use strict'

    var $sidebar = $('.control-sidebar')
    var $container = $('<div />', {
        class: 'p-3 control-sidebar-content'
    })

    $sidebar.append($container)

    // Checkboxes

    $container.append(
        '<h5>Personalizar Dashboard</h5><hr class="mb-2"/>'
    )

    // Dark Mode
    var $dark_mode_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('body').hasClass('dark-mode'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
            $('body').addClass('dark-mode')
            $('.navbar-light').addClass('navbar-dark')
        } else {
            $('body').removeClass('dark-mode')
            $('.navbar-light').removeClass('navbar-dark')
        }
    })
    var $dark_mode_container = $('<div />', { class: 'mb-4' }).append($dark_mode_checkbox).append('<span>Modo Oscuro</span>')
    $container.append($dark_mode_container)

    // Navbar
    $container.append('<h6>Men&uacute; Superior</h6>')
    var $header_fixed_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('body').hasClass('layout-navbar-fixed'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
        $('body').addClass('layout-navbar-fixed')
        } else {
        $('body').removeClass('layout-navbar-fixed')
        }
    })
    var $header_fixed_container = $('<div />', { class: 'mb-1' }).append($header_fixed_checkbox).append('<span>Encrustado</span>')
    $container.append($header_fixed_container)

    var $dropdown_legacy_offset_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('.main-header').hasClass('dropdown-legacy'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
        $('.main-header').addClass('dropdown-legacy')
        } else {
        $('.main-header').removeClass('dropdown-legacy')
        }
    })
    var $dropdown_legacy_offset_container = $('<div />', { class: 'mb-1' }).append($dropdown_legacy_offset_checkbox).append('<span>Desplazamiento Heredado Desplegable</span>')
    $container.append($dropdown_legacy_offset_container)

    var $no_border_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('.main-header').hasClass('border-bottom-0'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
        $('.main-header').addClass('border-bottom-0')
        } else {
        $('.main-header').removeClass('border-bottom-0')
        }
    })
    var $no_border_container = $('<div />', { class: 'mb-4' }).append($no_border_checkbox).append('<span>Sin Bordes</span>')
    $container.append($no_border_container)

    // Sidebar
    $container.append('<h6>Barra Lateral</h6>')

    var $sidebar_collapsed_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('body').hasClass('sidebar-collapse'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
        $('body').addClass('sidebar-collapse')
        $(window).trigger('resize')
        } else {
        $('body').removeClass('sidebar-collapse')
        $(window).trigger('resize')
        }
    })
    var $sidebar_collapsed_container = $('<div />', { class: 'mb-1' }).append($sidebar_collapsed_checkbox).append('<span>Colapsar</span>')
    $container.append($sidebar_collapsed_container)

    var $sidebar_fixed_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('body').hasClass('layout-fixed'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
        $('body').addClass('layout-fixed')
        $(window).trigger('resize')
        } else {
        $('body').removeClass('layout-fixed')
        $(window).trigger('resize')
        }
    })
    var $sidebar_fixed_container = $('<div />', { class: 'mb-1' }).append($sidebar_fixed_checkbox).append('<span>Encrustado</span>')
    $container.append($sidebar_fixed_container)

    var $flat_sidebar_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('.nav-sidebar').hasClass('nav-flat'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
        $('.nav-sidebar').addClass('nav-flat')
        } else {
        $('.nav-sidebar').removeClass('nav-flat')
        }
    })
    var $flat_sidebar_container = $('<div />', { class: 'mb-1' }).append($flat_sidebar_checkbox).append('<span>Estilo Plano</span>')
    $container.append($flat_sidebar_container)

    var $legacy_sidebar_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('.nav-sidebar').hasClass('nav-legacy'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
        $('.nav-sidebar').addClass('nav-legacy')
        } else {
        $('.nav-sidebar').removeClass('nav-legacy')
        }
    })
    var $legacy_sidebar_container = $('<div />', { class: 'mb-1' }).append($legacy_sidebar_checkbox).append('<span>Estilo Heredado</span>')
    $container.append($legacy_sidebar_container)
    
    var $compact_sidebar_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('.nav-sidebar').hasClass('nav-compact'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
        $('.nav-sidebar').addClass('nav-compact')
        } else {
        $('.nav-sidebar').removeClass('nav-compact')
        }
    })
    var $compact_sidebar_container = $('<div />', { class: 'mb-1' }).append($compact_sidebar_checkbox).append('<span>Estilo Compacto</span>')
    $container.append($compact_sidebar_container)
    
    var $child_indent_sidebar_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('.nav-sidebar').hasClass('nav-child-indent'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
        $('.nav-sidebar').addClass('nav-child-indent')
        } else {
        $('.nav-sidebar').removeClass('nav-child-indent')
        }
    })
    var $child_indent_sidebar_container = $('<div />', { class: 'mb-4' }).append($child_indent_sidebar_checkbox).append('<span>Sub Men&uacute; Identado</span>')
    $container.append($child_indent_sidebar_container)
    

    // Footer
    $container.append('<h6>Pie de P&aacute;gina</h6>')
    var $footer_fixed_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('body').hasClass('layout-footer-fixed'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
        $('body').addClass('layout-footer-fixed')
        } else {
        $('body').removeClass('layout-footer-fixed')
        }
    })
    var $footer_fixed_container = $('<div />', { class: 'mb-4' }).append($footer_fixed_checkbox).append('<span>Encrustado</span>')
    $container.append($footer_fixed_container)

    // Small Text
    $container.append('<h6>Texto Peque&ntilde;o</h6>')

    var $text_sm_body_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('body').hasClass('text-sm'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
        $('body').addClass('text-sm')
        } else {
        $('body').removeClass('text-sm')
        }
    })
    var $text_sm_body_container = $('<div />', { class: 'mb-1' }).append($text_sm_body_checkbox).append('<span>Todo</span>')
    $container.append($text_sm_body_container)

    var $text_sm_header_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('.main-header').hasClass('text-sm'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
        $('.main-header').addClass('text-sm')
        } else {
        $('.main-header').removeClass('text-sm')
        }
    })
    var $text_sm_header_container = $('<div />', { class: 'mb-1' }).append($text_sm_header_checkbox).append('<span>Men&uacute; Superior</span>')
    $container.append($text_sm_header_container)

    var $text_sm_brand_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('.brand-link').hasClass('text-sm'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
        $('.brand-link').addClass('text-sm')
        } else {
        $('.brand-link').removeClass('text-sm')
        }
    })
    var $text_sm_brand_container = $('<div />', { class: 'mb-1' }).append($text_sm_brand_checkbox).append('<span>Marca</span>')
    $container.append($text_sm_brand_container)

    var $text_sm_sidebar_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('.nav-sidebar').hasClass('text-sm'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
        $('.nav-sidebar').addClass('text-sm')
        } else {
        $('.nav-sidebar').removeClass('text-sm')
        }
    })
    var $text_sm_sidebar_container = $('<div />', { class: 'mb-1' }).append($text_sm_sidebar_checkbox).append('<span>Barra Lateral</span>')
    $container.append($text_sm_sidebar_container)

    var $text_sm_footer_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('.main-footer').hasClass('text-sm'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
        $('.main-footer').addClass('text-sm')
        } else {
        $('.main-footer').removeClass('text-sm')
        }
    })
    var $text_sm_footer_container = $('<div />', { class: 'mb-4' }).append($text_sm_footer_checkbox).append('<span>Pie de P&aacute;gina</span>')
    $container.append($text_sm_footer_container)

})(jQuery)
