<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Common\Collections\Criteria;

class DefaultController extends Controller
{
    protected const ORDER_ORIENTATION = ['ASC', 'DESC'];
    protected const DATETIME_FORMAT = '!Y-m-d';

    protected function getRequestData(Request $request) : ?Object
    {
        $input = $request->getContent();
        if (!empty($input)) {
            return json_decode($input);
        } else {
            return null;
        }
    }

    /**
     * @Route("/", name="commodities_list")
     */
    public function indexAction(Request $request) : JsonResponse
    {
        $doctrine = $this->getDoctrine();
        $repo = $doctrine->getRepository('AppBundle:Commodity');

        $criteria = new Criteria();
        $orderBy = [];

        // filtering logic
        if ($input = $this->getRequestData($request)) {
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
                    $criteria->andWhere($criteria->expr()->gte(
                        'created_at',
                        \DateTime::createFromFormat($this::DATETIME_FORMAT, $input->created_at->min)
                    ));
                }
                if (isset($input->created_at->max)) {
                    $criteria->andWhere($criteria->expr()->lte(
                        'created_at',
                        \DateTime::createFromFormat($this::DATETIME_FORMAT, $input->created_at->max)
                    ));
                }
                if (isset($input->created_at->sort)) {
                    if (in_array($input->created_at->sort, $this::ORDER_ORIENTATION)) {
                        $orderBy['created_at'] = $input->created_at->sort === 'ASC'
                            ? $criteria::ASC
                            : $criteria::DESC;
                    }
                }
            }

            if (isset($input->name)) {
                if (isset($input->name->sort)) {
                    if (in_array($input->name->sort, $this::ORDER_ORIENTATION)) {
                        $orderBy['name'] = $input->name->sort === 'ASC'
                            ? $criteria::ASC
                            : $criteria::DESC;
                    }
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
