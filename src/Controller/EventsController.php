<?php

namespace App\Controller;

use \Core\Controller\Controller;

class EventsController extends Controller
{

    public function __construct()
    {
        $this->loadModel("events");
        $this->loadModel("bookingEvents");
        $this->loadModel("users");
    }

    public function index()
    {
        $events = $this->events->allfutur(true, "date_at");

        return $this->render(
            "events/events",
            ["items" => $events]
        );
    }

    public function booking($id, $slug, $email = "")
    {
        $form = false;
        $booking = false;
        $email = filter_var($email, \FILTER_VALIDATE_EMAIL);

        $event = $this->events->find($id);

        if (!$event) {
            $this->redirect("events");
        }
        if ($event->getSlug() != $slug) {

            $this->redirect(
                "eventsBooking",
                [
                    "id" => $id,
                    "slug" => $event->getSlug(),
                    "email" => $email
                ]
            );
        }
        if ($this->bookingEvents->find(["email" => $email, "id_events" => $event->getId()])) {
            $this->messageFlash()->success("vous êtes déjà inscrit pour cet évènement");
            $booking = true;
        }
        if (!empty($email) && $user = $this->users->find($email, "email") && !$booking) {

            if ($this->security()->isConnect()) {
                $this->bookingEvents->create(["id_events" => $id, "email" => $email]);
                $this->messageFlash()->success("vous êtes bien inscrit pour cet évènement");
            } else {
                $this->session()->set("redirect", $this->generateUrl("eventsBooking",  [
                    "id" => $id,
                    "slug" => $event->getSlug(),
                    "email" => $email
                ]));
                $this->messageFlash()->error("merci de vous connecter");
                $this->redirect('usersLogin');
            }
        } elseif (!empty($email) && !$booking) {
            $this->bookingEvents->create(["id" => $id, "email" => $email]);
            $this->messageFlash()->success("vous êtes bien inscrit pour cet évènement");
        } elseif (!$booking) {
            if ($this->security()->isConnect()) {
                $email = $this->session()->get("users")->getEmail();
            }

            $form = true;
        }
        return $this->render(
            "events/event",
            [
                "event" => $event,
                "email" => $email,
                "form" => $form,
                "url" => $this->generateUrl(
                    "eventsBooking",
                    [
                        "id" => $id,
                        "slug" => $event->getSlug(),
                        "email" => ""
                    ]
                )
            ]
        );
    }
}
