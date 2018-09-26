<?php

namespace AppBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class AppExtension extends ConfigurableExtension
{
    private const configKeys = [
        'jwt_issuer',
        'jwt_audience',
        'jwt_token_ttl',
        'jwt_secret_key',
        'jwt_public_key',
    ];

    public function loadInternal(array $mergedConfig, ContainerBuilder $builder)
    {
        foreach ($this::configKeys as $key) {
            $value = $mergedConfig[$key];
            if (strpos($key, 'key') !== FALSE) {
                $value = 'file://' . $value;
            }

            $builder->setParameter('shop_api.' . $key, $value);
        }
    }
}
