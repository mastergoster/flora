<?php

namespace App\Model\Entity;

use Core\Model\Entity;
use Core\Controller\Helpers\HController;

class RecapConsoEntity extends Entity
{
    private $LastForfaitExpiredAt;
    private $total_achat_heure = 0;
    private $all_forfaits;
    private $heuresLog;
    private $consototal = 0;
    private $heurDispo;
    private $presence = false;
    private $consoByJour;
    private $lastday = false;


    public function __construct(int $id_user)
    {
        $app = \App\App::getInstance();
        $forfaitsId     =   $app->getTable('forfait')->allAssocId();
        $forfaitsLog    =   $app->getTable('forfaitLog')->findAllByIdUser($id_user);
        $heureslog       =   $app->getTable('heures')->findAll($id_user, 'id_user');

        $this->hydrateForfait($forfaitsLog, $forfaitsId);
        $this->hydrateHeure($heureslog);

        $this->id_user = $id_user;
    }
    private function hydrateForfait($forfaitsLog, $forfaitsId)
    {
        foreach ($forfaitsLog as $key => $value) {
            $createdAt = $value->getCreatedAt();
            $forfaitsLog[$key] = $forfaitsId[$value->getId()];
            $forfaitsLog[$key]->setCreatedAt($createdAt);
            $this->total_achat_heure += $forfaitsLog[$key]->getDuree();
            $this->LastForfaitExpiredAt = $forfaitsLog[0]->getExpiredAt();
            $this->all_forfaits = $forfaitsLog;
        }
    }
    private function hydrateHeure($heureslog)
    {
        $now = date("Y-m-d");
        foreach ($heureslog as $heureLog) {
            $datetext = $heureLog->getCreatedAt()->format('Y-m-d H:i:s');
            if ($heureLog->getCreatedAt()->format('Y-m-d') < $now) {
                $this->lastday = $heureLog->getCreatedAt();
            }
            $date = substr($datetext, 0, 10);
            $this->heuresLog[$date][] = substr($datetext, 11);
        }
        if ($this->heuresLog === null) {
            $this->heuresLog = [];
        }
        foreach ($this->heuresLog as $key => $heures) {
            if ($key <= $now) {
                $this->consoByJour[$key] = $this->consoByJour($heures, $key == $now);
                if (!$now) {
                    $this->consototal += $this->consoByJour[$key];
                }
            }
        }
        $this->heurDispo = ($this->total_achat_heure * 60 * 60)  -  $this->consototal;
    }

    private function consoByJour($heurspoint, $now = false)
    {
        if ($now) {
            $e = 1;
            if (count($heurspoint) % 2) {
                $this->presence = true;
            } else {
                $this->presence = false;
            }
        } else {
            if (count($heurspoint) % 2) {
                $heurspoint[] = "23:59:59";
            }
            $e = 0;
        }

        sort($heurspoint);
        $dureejour = 0;
        for ($i = 0; $i < count($heurspoint) - $e; $i += 2) {
            if (isset($heurspoint[$i + 1])) {
                $heurpointfin = explode(":", $heurspoint[$i + 1]);
                $heurpointdebut = explode(":", $heurspoint[$i]);
                $heurpointfin = $heurpointfin[0] * 60 * 60 + $heurpointfin[1] * 60 + $heurpointfin[2];
                $heurpointdebut =  $heurpointdebut[0] * 60 * 60 + $heurpointdebut[1] * 60 + $heurpointdebut[2];
                $dureejour += $heurpointfin - $heurpointdebut;
            }
        }
        return $dureejour;
    }

    /**
     * Get the value of heurDispo
     */
    public function getHeurDispo()
    {
        return HController::convertisseurTime($this->heurDispo);
    }

    /**
     * Get the value of LastForfaitExpiredAt
     */
    public function getLastForfaitExpiredAt()
    {
        return $this->LastForfaitExpiredAt;
    }

    /**
     * Get the value of lastday
     */
    public function getLastDay()
    {
        if ($this->lastday) {
            $return = HController::convertisseurTime($this->consoByJour[$this->lastday->format("Y-m-d")]);
            $return["date"] = $this->lastday;
        } else {
            $return = ["date" => false, "h" => 0, "m" => 0];
        }
        return $return;
    }

    /**
     * Get the value of today
     */
    public function getToDay()
    {
        $now = date("Y-m-d");
        if (isset($this->consoByJour[$now])) {
            $return = HController::convertisseurTime($this->consoByJour[$now]);
        } else {
            $return = ["h" => 0, "m" => 0];
        }
        return $return;
    }

    /**
     * Get the value of presence
     */
    public function getPresence()
    {
        return $this->presence;
    }
}
