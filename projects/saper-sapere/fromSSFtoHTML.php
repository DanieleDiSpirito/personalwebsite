<?php

function getTitle(array $articolo) {
    $testo = $articolo['contenuto'];
    if (preg_match('/&t\{(.+)\}/', $testo, $match)) {
        return $match[1];
    }
    return 'Nessun titolo';
}

function getSummary(array $articolo) {
    $testo = $articolo['contenuto'];
    if (preg_match('/&s\{(.+)\}/', $testo, $match)) {
        return $match[1];
    }
    return 'Riassunto non disponibile';
}

function getContent(array $articolo) {
    $testo = $articolo['contenuto'];
    if (preg_match('/&c{(.*?)\\\c}/s', $testo, $match)) {
        $content = $match[1];
    }
    
    // <h1> .. <h7>
    $content = preg_replace("/&1{(.*?)}/", "<h3>$1</h3>", $content);
    $content = preg_replace("/&2{(.*?)}/", "<h2>$1</h2>", $content);
    $content = preg_replace("/&3{(.*?)}/", "<h3>$1</h3>", $content);
    $content = preg_replace("/&4{(.*?)}/", "<h3>$1</h3>", $content);
    $content = preg_replace("/&5{(.*?)}/", "<h3>$1</h3>", $content);
    $content = preg_replace("/&6{(.*?)}/", "<h3>$1</h3>", $content);
    $content = preg_replace("/&7{(.*?)}/", "<h3>$1</h3>", $content);
    
    return $content;
}

?>