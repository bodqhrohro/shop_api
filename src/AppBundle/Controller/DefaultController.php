<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Common\Collections\Criteria;

use AppBundle\Validation\DefaultValidator;

class DefaultController extends ApiController
{
    /**
     * @Route("/", name="commodities_list")
     */
    public function indexAction(Request $request) : JsonResponse
    {
        $doctrine = $this->getDoctrine();
        $repo = $doctrine->getRepository('AppBundle:Commodity');

        $criteria = new Criteria();
        $orderBy = [];

        if ($input = $this->getRequestData($request)) {
            $input = DefaultValidator::validateInput($input);

            // filtering logic
            if (isset($input->count)) {
                $criteria->setMaxResults($input->count);
            }

            if (isset($input->price)) {
                if (isset($input->price->min)) {
                    $criteria->andWhere($criteria->expr()->gte('price', $input->price->min));
                }
                if (isset($input->price->max)) {
                    $criteria->andWhere($criteria->expr()->lte('price', $input->price->max));
                }
            }

            if (isset($input->created_at)) {
                if (isset($input->created_at->min)) {
                    $criteria->andWhere($criteria->expr()->gte('created_at', $input->created_at->min));
                }
                if (isset($input->created_at->max)) {
                    $criteria->andWhere($criteria->expr()->lte('created_at', $input->created_at->max));
                }
                if (isset($input->created_at->sort)) {
                    $orderBy['created_at'] = $input->created_at->sort === 'ASC'
                        ? $criteria::ASC
                        : $criteria::DESC;
                }
            }

            if (isset($input->name)) {
                if (isset($input->name->sort)) {
                    $orderBy['name'] = $input->name->sort === 'ASC'
                        ? $criteria::ASC
                        : $criteria::DESC;
                }
            }
        }

        if (!empty($orderBy)) {
            $criteria->orderBy($orderBy);
        }

        $commodities = $repo->matching($criteria);
        $commodities_array = [];
        foreach ($commodities as $commodity) {
            $commodities_array[] = $commodity;
        }

        return $this->json($commodities_array);
    }
}
