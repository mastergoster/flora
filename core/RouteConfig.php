<?php

namespace Core;

class RouteConfig
{
    public function getConfig(): array
    {
        return [
            ["get",     '/stripe/[:id]', 'charge#create',                'charge'],
            ["get",     '/stripe/[:id]/[*:slug]', 'charge#chargeReturn', 'chargeReturn'],

        ];
    }
}
