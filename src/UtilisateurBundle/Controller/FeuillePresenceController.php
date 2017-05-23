<?php

namespace UtilisateurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ConnexionBundle\Entity\ETimeSheet;
use ConnexionBundle\Repository\ETimeSheetRepository;

class FeuillePresenceController extends Controller
{
    public function creerFeuillePresenceAction()
    {
        return $this->render('UtilisateurBundle:Default:creation_ets.html.twig');
    }

    public function historiqueFeuillePresenceAction()
    {
        $this->denyAccessUnlessGranted(array('ROLE_USER'));

        $user = $this->getUser();
        $lesEts = $this->container->get('ets')->validees($user->getPromotion());

        //echo $user->getPromotion()->getLibelle(); die;

        /*foreach ($lesEts as $lEts)
        {
            echo $lEts; die;
        }*/

        return $this->render('UtilisateurBundle:Default:historique_ets.html.twig', array(
            'user' => $user,
            'lesEts' => $lesEts
        ));
    }

    public function visionnerFeuillePresenceAction()
    {
        $this->denyAccessUnlessGranted(array('ROLE_ETUDIANT'));

        $user = $this->getUser();
        $lEts = $this->getDoctrine()->getRepository('ConnexionBundle:ETimeSheet')->getEtsDuJour();

        $lesCours = null;
        $lesCoursNonValide = array();
        if(isset($lEts[0])) { //L'ETS iexste ?
            $lesCours = $lEts[0]->getLesCours();

            foreach ($lesCours as $leCours) {
                if (!$leCours->getEstValide()) {
                    $lesCoursNonValide[] = $leCours;
                }
            }
        }
        return $this->render('UtilisateurBundle:Default:signaler_presence.html.twig', array(
            'user' => $user,
            'lesCours' => $lesCoursNonValide
        ));
    }

    public function visionnerHistoriqueAbsencesPromosAction()
    {
        return $this->render('UtilisateurBundle:Default:recap_absences.html.twig');
    }

    public function visionnerHistoriqueFactureAction()
    {
        return $this->render('UtilisateurBundle:Default:historique_facture.html.twig');
    }

}
