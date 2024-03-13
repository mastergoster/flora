<?php

namespace App\Services;

use Core\Controller\Controller;

class CalendarService extends Controller
{
    public $ics = "BEGIN:VCALENDAR\nVERSION:2.0\nPRODID:-//hacksw/handcal//NONSGML v1.0//EN\n";

    public $books;

    public function __construct()
    {
        $this->loadModel("books");
        $this->loadModel("users");
        $this->books = $this->books->all();
        foreach ($this->books as $book) {
            \dump($book);
            $user = $this->users->find($book->getIdUser());
            $salle =$book->ressource_slug;
            $society = $user->getSociety();
            if ($society != null) {
                $salle .= " - ". $society;
            }
            $salle .= " - " . $user->getFirstName() . " " . $user->getLastName();
            $this->event(
                $book->getStartAt(),
                $book->getEndAt(),
                $salle);
        }
        $this->ics .= "END:VCALENDAR";
        $fichier = getenv("fichier_ics").'.ics';
        $f = fopen(
            \getenv("PATH_BASE") .
             \DIRECTORY_SEPARATOR .
              "html" .
              \DIRECTORY_SEPARATOR .
              "medias" .
              \DIRECTORY_SEPARATOR .
              $fichier,
              'w+'
            );
        fputs($f, $this->ics);
    }

    public function event($date_debut, $date_fin, $objet){
        $event = "BEGIN:VEVENT\n";
        $event .= "X-WR-TIMEZONE:Europe/Paris\n";
        $event .= "DTSTART:".date('Ymd',$date_debut)."T".date('His',$date_debut)."\n";
        $event .= "DTEND:".date('Ymd',$date_fin)."T".date('His',$date_fin)."\n";
        $event .= "SUMMARY:".$objet."\n";
        // $event .= "LOCATION:".$lieu."\n";
        // $event .= "DESCRIPTION:".$details."\n";
        $event .= "END:VEVENT\n";
        $this->ics .= $event;
    }
}