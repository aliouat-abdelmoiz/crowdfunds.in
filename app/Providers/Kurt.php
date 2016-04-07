<?php
/**
 * Created by PhpStorm.
 * User: mandeepgill
 * Date: 20/04/15
 * Time: 4:08 AM
 */

namespace project\Providers;
use Illuminate\Pagination\BootstrapThreePresenter;

class Kurt extends BootstrapThreePresenter {

//<li><a href="#">&laquo;</a></li>
//<li><a href="#">1</a></li>
//<li><span>2</span></li>
//<li><a href="#">3</a></li>
//<li><a href="#">4</a></li>
//<li><a href="#">5</a></li>
//<li><a href="#">&raquo;</a></li>

    public function getActivePageWrapper($text)
    {
        return '<li><span>'.$text.'</span></li>';
    }

    public function getDisabledTextWrapper($text)
    {
        return '<li class="disabled"><a href="#">'.$text.'</a></li>';
    }

    public function getPageLinkWrapper($url, $page, $rel = null)
    {
        return '<li><a href="'.$url.'">'.$page.'</a></li>';
    }

    public function render()
    {
        if ($this->hasPages())
        {
            return sprintf(
                '%s %s %s',
                $this->getPreviousButton(),
                $this->getLinks(),
                $this->getNextButton()
            );
        }

        return '';
    }

}