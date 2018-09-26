<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Keychain;
use Lcobucci\JWT\Signer\Rsa\Sha256;

class TokenGenerator extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('shop_api:token-generate')
            ->setDescription('Generate a JWT token for authorization')
            ->setHelp('')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        $signer = new Sha256();

        $keychain = new Keychain();

        $builder = new Builder();
        $token = $builder->setIssuer($container->getParameter('shop_api.jwt_issuer'))
                         ->setAudience($container->getParameter('shop_api.jwt_audience'))
                         ->setId(md5(random_bytes(512)), true)
                         ->setIssuedAt(time())
                         ->setExpiration(time() + $container->getParameter('shop_api.jwt_token_ttl'))
                         ->sign($signer, $keychain->getPrivateKey($container->getParameter('shop_api.jwt_secret_key')))
                         ->getToken();

        $output->writeln((string)$token);
    }
}
