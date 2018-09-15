<?php

namespace AppBundle\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

use Fakerino\Fakerino;
use Fakerino\Core\FakeDataFactory;

use AppBundle\Entity\Commodity;

class FixtureGenerator extends Fixture {
    private function fakeName(FakeDataFactory $fakerino) {
        $words = [];
        for ($i = 0; $i < 3; $i ++) {
            $words = array_merge($words, explode(' ', $fakerino->fake('job')));
        }
        $words = array_filter($words, function($word) {
            return $word !== 'and';
        });

        if (count($words)) {
            $randKeys = array_rand($words, mt_rand(1, count($words)));
            if (!is_array($randKeys)) {
                $randKeys = [$randKeys];
            }

            $nameWords = array_map(function($key) use ($words) {
                return $words[$key];
            }, $randKeys);

            return join(' ', $nameWords);
        } else {
            return 'New Commodity';
        }
    }

    public function load(ObjectManager $manager) {
        $fakerino = Fakerino::create();

        for ($i = 0; $i < 50000; $i ++) {
            $commodity = new Commodity();
            $commodity->setName($this->fakeName($fakerino));
            $commodity->setPrice(mt_rand(15, 32000));

            $datetime = new \DateTime();
            $datetime->setTimestamp(mt_rand(1420063200, 1535749200));
            $commodity->setCreatedAt($datetime);

            $manager->persist($commodity);
        }

        $manager->flush();
    }
}
