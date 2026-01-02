<?php
#################################################
#                                               #
#   Impleo Musiksamling 1.0 (Atualizado 2026)   #
#   (c) 2006 Christoffer Kjeldgaard Petersen    #
#   http://sappy.dk/impleo/                     #
#                                               #
#################################################

function getCharset()
{
    require("./config.php");
    return $charset;
}

function installedTables()
{
    require("./config.php");
    $database = @mysqli_connect($host, $user, $pass, $dat) or die("<p>" . mysqli_connect_error() . "</p>\n");
    $result = mysqli_query($database, "SHOW TABLES") or die("<p>" . mysqli_error($database) . "</p>\n");
    $array = array();
    while ($row = mysqli_fetch_row($result))
    {
        $array[] = $row[0];
    }
    mysqli_close($database);
    return $array;
}

function getCollectionSettings($info)
{
    require("./config.php");
    $database = @mysqli_connect($host, $user, $pass, $dat) or die("<p>" . mysqli_connect_error() . "</p>\n");
    $getSettings = mysqli_query($database, "SELECT " . $info . " FROM " . $tablestart . "settings LIMIT 1") or die("<p>" . mysqli_error($database) . "</p>\n");
    $settingsData = mysqli_fetch_array($getSettings);
    mysqli_close($database);
    return $settingsData[$info];
}

function countRecords()
{
    require("./config.php");
    $database = @mysqli_connect($host, $user, $pass, $dat) or die("<p>" . mysqli_connect_error() . "</p>\n");
    $res = mysqli_query($database, "SELECT id FROM " . $tablestart . "collection");
    $count = mysqli_num_rows($res);
    mysqli_close($database);
    return $count;
}

function countAttribute($case)
{
    require("./config.php");
    $database = @mysqli_connect($host, $user, $pass, $dat) or die("<p>" . mysqli_connect_error() . "</p>\n");
    $res = mysqli_query($database, "SELECT COUNT(id) as cnt, " . $case . " FROM " . $tablestart . "collection GROUP BY " . $case);
    $count = mysqli_num_rows($res);
    mysqli_close($database);
    return $count;
}

function getRecords($start, $limit, $order)
{
    require("./config.php");
    $database = @mysqli_connect($host, $user, $pass, $dat) or die("<p>" . mysqli_connect_error() . "</p>\n");
    $sql = mysqli_query($database, "SELECT * FROM " . $tablestart . "collection ORDER BY " . $order . " LIMIT " . (int)$start . ", " . (int)$limit) or die("<p>" . mysqli_error($database) . "</p>\n");
    // Nota: Em mysqli, se você fechar a conexão aqui, o resultado pode dar erro ao ser lido depois. 
    // Recomenda-se fechar após o uso no arquivo que chama a função.
    return $sql;
}

function createPagingList($numRecords, $limit, $display, $sort)
{
    require("./language.php");
    if (preg_match("/[A-Za-z0-9]+/", $sort))
        $sortVar = "sort=" . $sort;
    else
        $sortVar = "sort=artist";
    $paging = ceil($numRecords/$limit);
    $pagingOutput = "<p id=\"navigation\">\n";
    if ($display > 1)
    {
        $previous = $display - 1;
        if ($previous == 1)
            $pagingOutput .= "  <a href=\"./?" . $sortVar . "\">« " . $lang_prev . "</a>\n";
        else
            $pagingOutput .= "  <a href=\"./?" . $sortVar . "&amp;show=" . $previous . "\">« " . $lang_prev . "</a>\n";
    }
    else
        $pagingOutput .= "  « " . $lang_prev . "\n";
    if ($numRecords != $limit)
    {
        for ($i = 1; $i <= $paging; $i++)
        {
            if ($display == $i)
                $pagingOutput .= "  <b>$i</b>\n";
            else
            {
                if ($i == 1)
                    $pagingOutput .= "  <a href=\"./?" . $sortVar . "\">" . $i ."</a>\n";
                else
                    $pagingOutput .= "  <a href=\"./?" . $sortVar . "&amp;show=" . $i . "\">" . $i . "</a>\n";
            }
        }
    }
    if ($display < $paging)
    {
        $next = $display + 1;
        $pagingOutput .= "  <a href=\"./?" . $sortVar . "&amp;show=" . $next . "\">" . $lang_next . " »</a>\n";
    }
    else
    {
        $pagingOutput .= "  " . $lang_next . " »\n";
    }
    $pagingOutput .= "</p>\n";
    return $pagingOutput;
}

function createToplist($case, $limit)
{
    require("./config.php");
    $database = @mysqli_connect($host, $user, $pass, $dat) or die("<p>" . mysqli_connect_error() . "</p>\n");
    $sql = mysqli_query($database, "SELECT COUNT(id) as cnt, " . $case . " FROM " . $tablestart . "collection GROUP BY " . $case . " ORDER BY cnt DESC LIMIT " . (int)$limit) or die("<p>" . mysqli_error($database) . "</p>\n");
    $list = "<ol>\n";
    while ($data = mysqli_fetch_array($sql))
    {
        $list .= "  <li>" . $data[$case] . " (" . $data['cnt'] . ")</li>\n";
    }
    $list .= "</ol>\n";
    mysqli_close($database);
    return $list;
}
?>
