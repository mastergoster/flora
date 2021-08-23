<?php

namespace App;

class RouteConfig
{
    public function getConfig(): array
    {
        return [
            ["get",     '/validation/[*:slug]', 'users#validate', 'validate'],
            ["get",     '/admin/update', 'AdminCore#update', 'update'],
            ["get",     '/tactile', 'Display#tactile', 'tactile'],
            ["get",     '/tv', 'Display#tv', 'tv'],
            ["get",     '/', 'Pages#index', 'home'],
            ["match",   '/events/[i:id]-[*:slug]/[*:email]?', 'Events#booking', 'eventsBooking'],
            ["get",     '/events', 'Events#index', 'events'],
            ["post",    '/admin/compta/add', 'AdminCompta#add', 'adminComptaadd'],
            ["get",     '/admin/compta/ligne', 'AdminCompta#ligne', 'adminComptaligne'],
            ["match",   '/admin/compta/ndf', 'AdminCompta#ndf', 'adminComptaNDF'],
            ["get",     '/admin/compta', 'AdminCompta#index', 'adminCompta'],
            ["match",   '/inscription', 'Users#subscribe', 'usersSubscribe'],
            ["get",     '/login', 'Users#login', 'usersLogin'],
            ["match",   '/mdpoublie', 'Users#mdpoublie', 'usersMdpoublie'],
            ["match",   '/mdpchange/[*:slug]', 'Users#mdpchange', 'usersMdpchange'],
            ["get",     '/admin', 'Admin#panel', 'adminPanel'],
            ["match",   '/admin/users/[i:id]/modifheure', 'Admin#modifHeure', 'adminUserModifHeure'],
            ["match",   '/admin/users/[i:id]', 'Admin#user', 'adminUser'],
            ["match",   '/admin/events', 'AdminEvents#events', 'adminEvents'],
            ["match",   '/admin/event/[i:id]?', 'AdminEvents#event', 'adminEventSingle'],
            ["get",     '/admin/users', 'Admin#users', 'adminUsers'],
            ["get",     '/admin/roles', 'Admin#roles', 'adminRoles'],
            ["get",     '/admin/roles/[i:id]', 'Admin#role', 'adminRole'],
            ["match",     '/admin/messages', 'Admin#messages', 'adminMessages'],
            ["match",   '/admin/invoices', 'AdminInvoces#all', 'adminInvoces'],
            ["match",   '/admin/invoices/[i:id]/pdf', 'AdminInvoces#invocePdf', 'adminInvocePdf'],
            ["post",    '/admin/invoices/[i:id]/validate', 'AdminInvoces#validate', 'adminInvoceValidate'],
            ["match",   '/admin/invoices/[i:id]', 'AdminInvoces#single', 'adminInvoceEdit'],
            ["match",   '/admin/products', 'AdminInvoces#products', 'adminProducts'],
            ["match",   '/admin/invoices/[i:id]/actualise', 'AdminInvoces#actualise', 'adminActualise'],
            ["match",   '/admin/packages', 'Admin#products', 'adminPackages'],
            ["get",     '/admin/orders', 'Admin#orders', 'adminOrders'],
            ["get",     '/user/logout', 'users#logout', 'userLogout'],
            ["get",     '/user/profile', 'users#profile', 'userProfile'],
            ["get",     '/user/invoices', 'users#invoces', 'userInvoces'],
            ["match",   '/user/messages', 'users#userMessages', 'userMessages'],
            ["match",   '/user/edit', 'users#edit', 'userEdit'],
            ["get",     '/user/invoices/[i:id]', 'users#invoce', 'userInvoce'],
            ["get",     '/activate', 'users#activatePage', 'activatePage'],
            ["post",    '/mails', 'Users#mail', 'mailSend'],
            ["post",    '/user/admin/newline', 'Users#ajaxNewUserLine', 'post_AjaxNewUserLine'],
            ["post",    '/display/admin/new/line', 'Display#ajaxDisplayNewLine', 'post_ajaxDisplayNewLine'],
            ["post",    '/login', 'Users#login', 'post_usersLogin'],
            ["match",    '/ajax', 'Ajax#init', 'ajax'],
            ["get",    '/gestion/users', 'GesUsers#users', 'gestion_users'],
            ["get",    '/Mentions-Legales', 'Pages#legal', 'legal']
        ];
    }
}
