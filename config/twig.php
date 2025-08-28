<?php
$twig->addFunction(new TwigFunction('route_exists', function ($name) use ($container) {
    return $container->get('router')->getRouteCollection()->get($name) !== null;
}));