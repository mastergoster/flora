<?php

namespace App\Controller;

use \Core\Controller\PaginatedQueryController;

class PaginatedQueryAppController extends PaginatedQueryController
{

    public function getNavHtml(): string
    {
        $urls = $this->getNav();
        $html = "";
        foreach ($urls as $key => $url) {
            $class = $this->getCurrentPage() == $key ? " active" : "";
            $html .= "<li class=\"page-item {$class}\"><a class=\"page-link\" href=\"{$url}\">{$key}</a></li>";
        }
        return <<<HTML
        <nav class="Page navigation">
            <ul class="pagination justify-content-center">
                {$html}
            </ul>
        </nav>
HTML;
    }
}
