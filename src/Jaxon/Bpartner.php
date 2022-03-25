<?php

namespace App\Jaxon;

use App\Entity\CBpartner;
use App\Entity\CBpartnerLocation;
use App\Repository\CBpartnerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Common\Collections\ExpressionBuilder;
use Doctrine\ORM\Query\Expr;
use Jaxon\Response\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class Bpartner extends Base
{
}

?>