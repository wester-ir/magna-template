<?php

function render_categories($category, bool $first = true): void
{
    if ($category->children):
        echo "<ul class=\"". ($first ? 'main-ul' : 'sub-ul') ."\">";
            foreach ($category->children as $child):
                echo "<li>
                    <a href=\"{$child->url}\" class=\"default flex items-center\">
                        <span>{$child->name}</span>
                        ". ($first ? "<span class=\"fi fi-rr-angle-small-left flex mr-1\"></span>" : '') ."
                    </a>";

                    render_categories($child, false);
                echo "</li>";
            endforeach;
        echo "</ul>";
    endif;
}
