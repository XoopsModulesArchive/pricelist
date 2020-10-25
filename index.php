<?php

// ------------------------------------------------------------------------- //
// XOOPS - PHP Content Management System //
// <http://xoops.codigolivre.org.br> //
// ------------------------------------------------------------------------- //
// Based on: //
// myPHPNUKE Web Portal System - http://myphpnuke.com/ //
// PHP-NUKE Web Portal System - http://phpnuke.org/ //
// Thatware - http://thatware.org/ //
// ------------------------------------------------------------------------- //
// This program is free software; you can redistribute it and/or modify //
// it under the terms of the GNU General Public License as published by //
// the Free Software Foundation; either version 2 of the License, or //
// (at your option) any later version.  //
//   //
// This program is distributed in the hope that it will be useful, //
// but WITHOUT ANY WARRANTY; without even the implied warranty of //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the //
// GNU General Public License for more details. //
//   //
// You should have received a copy of the GNU General Public License //
// along with this program; if not, write to the Free Software //
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA //
// ------------------------------------------------------------------------- //
include '../../mainfile.php';
require XOOPS_ROOT_PATH . '/modules/pricelist/cache/config.php';
require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
$myts = MyTextSanitizer::getInstance(); // MyTextSanitizer object
$mytree = new XoopsTree($xoopsDB->prefix('pricelist_cat'), 'cid', 'pid');
if ('pricelist' == $xoopsConfig['startpage']) {
    $xoopsOption['show_rblock'] = 1;

    require XOOPS_ROOT_PATH . '/header.php';

    make_cblock();

    echo '<br>';
} else {
    $xoopsOption['show_rblock'] = 0;

    require XOOPS_ROOT_PATH . '/header.php';
}
require XOOPS_ROOT_PATH . '/modules/pricelist/cache/config.php';
function AdIndex()
{
    global $xoopsDB, $xoopsConfig, $xoopsTheme;

    TopPart();

    BotPart();
}

function TopPart()
{
    // global $thfontface, $thfontcolor, $thfontstyle, $tcfontface, $tcfontcolor, $tcfontstyle, $thcolor, $tccolor;

    // echo "\n\n<style type=\"text/css\">\n"

    // .".th {BACKGROUND: $thcolor; COLOR: $thfontcolor; FONT-FAMILY: $thfontface; TEXT-DECORATION: $thfontstyle}\n"

    // .".tc {BACKGROUND: $tccolor; COLOR: $tcfontcolor; FONT-FAMILY: $tcfontface; TEXT-DECORATION: $tcfontstyle}\n"

    // ."</style>\n\n";

    AdHeader();
}

function BotPart()
{
    require XOOPS_ROOT_PATH . '/modules/pricelist/footer.php';
}

function BrowseCat()
{
    global $xoopsDB;

    TopPart();

    echo "<br>\n";

    OpenTable();

    echo '<center>'
         . _VIEWITEMINCAT
         . "</center>\n"
         . "<center><form action=\"index.php\" method=\"post\">\n"
         . "<input type=\"hidden\" name=\"func\" value=\"viewlist\">\n"
         . '<input type="hidden" name="viewstyle" value="0">'
         . '<input type="hidden" name="sortfield" value="itemname">'
         . "<input type=\"hidden\" name=\"sortby\" value=\"desc\">\n"
         . "<input type=\"hidden\" name=\"of\" value=\"0\">\n"
         . '<select name="idnum"><option>'
         . _SELCAT
         . "</option>\n";

    $selcat = $xoopsDB->query('select caid, catname from ' . $xoopsDB->prefix('pricelist_categories') . ' WHERE parent < 0 order by catname');

    while (list($caid, $catname) = $xoopsDB->fetchRow($selcat)) {
        echo "<option name=\"idnum\" value=\"$caid\">$catname -</option>\n";

        $pselcat = $xoopsDB->query('select caid, catname from ' . $xoopsDB->prefix('pricelist_categories') . " WHERE parent = $caid order by catname");

        while (list($caid, $catname) = $xoopsDB->fetchRow($pselcat)) {
            echo "<option name=\"idnum\" value=\"$caid\">-- $catname</option>";
        }
    }

    echo '</select>&nbsp;<input type="submit" value="' . _GO_TEXT . "\"></form></center>\n";

    CloseTable();

    BotPart();
}

function BrowseDealer()
{
    global $xoopsDB;

    TopPart();

    echo "<br>\n";

    OpenTable();

    echo '<center>'
         . _VIEWITEMSOLD
         . "</center>\n"
         . "<center><form action=\"index.php\" method=\"post\">\n"
         . "<input type=\"hidden\" name=\"func\" value=\"viewlist\">\n"
         . '<input type="hidden" name="viewstyle" value="1">'
         . '<input type="hidden" name="sortfield" value="itemname">'
         . "<input type=\"hidden\" name=\"sortby\" value=\"desc\">\n"
         . "<input type=\"hidden\" name=\"of\" value=\"0\">\n"
         . '<select name="idnum"><option>'
         . _SELDEALER
         . "</option>\n";

    $seldeal = $xoopsDB->query('select deid, dealername from ' . $xoopsDB->prefix('pricelist_dealers') . ' order by dealername');

    while (list($deid, $dealername) = $xoopsDB->fetchRow($seldeal)) {
        echo "<option name=\"idnum\" value=\"$deid\">$dealername</option>\n";
    }

    echo '</select>&nbsp;<input type="submit" value="' . _GO_TEXT . "\"></form></center>\n";

    CloseTable();

    BotPart();
}

function AdHeader()
{
    global $xoopsDB;

    OpenTable();

    // echo "<center><img src=\"images/pricelist.jpg\" border=\"0\" alt=\"\"></center>\n";

    echo "<center class='itemHead'>"
         . _SEARCHFORITEM
         . "</center>\n"
         . '<center><form action="index.php" method="post">'
         . "<input type=\"hidden\" name=\"func\" value=\"search\">\n"
         . "<table class='odd'><tr>"
         . '<td>'
         . _SEARCHFOR_TEXT
         . ': </td>'
         . '<td><input type="text" size="28" name="query">'
         . '<select name="bool" size="1">'
         . "<option value='AND' selected>"
         . _ALLWORDSAND
         . '</option>'
         . "<option value='OR'>"
         . _ALLWORDSOR
         . '</option>'
         . '</select> '
         . _WITHIN_TEXT
         . ': </td>'
         . "<td><select name=\"secsearch\">\n"
         . '<option name="secsearch" value="1" selected>'
         . _ITEM_TEXT
         . "</option>\n"
         . '<option name="secsearch" value="2">'
         . _MAKER
         . "</option>\n"
         . '<option name="secsearch" value="3">'
         . _CITY
         . "</option>\n"
         . "</select></td>\n"
         . '<td><input type="submit" value="'
         . _GO_TEXT
         . "\"></td></tr>\n"
         . '<tr><td></td>'
         . '<td><select name="catid"><option name="catid" value="0" selected>'
         . _SELCAT
         . "</option>\n";

    $selcat = $xoopsDB->query('select caid, catname from ' . $xoopsDB->prefix('pricelist_categories') . ' WHERE parent < 0 order by catname');

    while (list($caid, $catname) = $xoopsDB->fetchRow($selcat)) {
        echo "<option name=\"catid\" value=\"$caid\">$catname -</option>\n";

        $pselcat = $xoopsDB->query('select caid, catname from ' . $xoopsDB->prefix('pricelist_categories') . " WHERE parent = $caid order by catname");

        while (list($caid, $catname) = $xoopsDB->fetchRow($pselcat)) {
            echo "<option name=\"catid\" value=\"$caid\">-- $catname</option>";
        }
    }

    echo '</select></td>' . '<td><select name="dealerid"><option name="dealerid" value="0" selected>' . _SELDEALER . "</option>\n";

    $seldeal = $xoopsDB->query('select deid, dealername from ' . $xoopsDB->prefix('pricelist_dealers') . ' order by dealername');

    while (list($deid, $dealername) = $xoopsDB->fetchRow($seldeal)) {
        echo "<option name=\"dealerid\" value=\"$deid\">$dealername</option>\n";
    }

    echo '</select></td><td></td></tr>' . '<input type="hidden" name="sortfield" value="itemname">' . "<input type=\"hidden\" name=\"sortby\" value=\"desc\">\n" . "<input type=\"hidden\" name=\"of\" value=\"0\">\n" . "</form></table></center>\n";

    echo '<center>' . _BROWSEACCORDING . "</center>\n" . "<center><table class='foot'><tr>" . '<td><a href="index.php?func=BrowseCat">[ ' . _CATEGORY_TEXT . ' ]</a></td>' . '<td><a href="index.php?func=BrowseDealer">[ ' . _DEALER_TEXT . ' ]</a></td>' . "</tr></table></center>\n";

    CloseTable();
}

function displaySubmitter($submitter, $idnum)
{
    global $xoopsDB;

    $result = $xoopsDB->query('SELECT dealeruid, dealername FROM ' . $xoopsDB->prefix('pricelist_dealers') . " where dealerid='$idnum'");

    [$dealeruid, $dealername] = $xoopsDB->fetchRow($result);

    if ($dealeruid > 0) {
        $submitter = '<a href="userinfo.php?uid=$uid">$dealername</a>';
    } else {
        $submitter = '<a href="index.php?func=dealerdetails&amp;iden=$idnum">$dealername</a>';
    }

    echo (string)$submitter;
}

function navigation($of, $nrows, $idnum, $viewstyle, $sortfield, $sortby)
{
    global $galleryvar, $xoopsDB, $limit;

    if (!$of) {
        $of = 0;
    }

    $nav = '<table><tr><td><center>';

    $pages = (int)($nrows / $limit);

    if ($nrows > 0 & $pages >= 1) {
        if (0 != $of) {
            $pof = $of - $limit;

            $nav .= "<a href=\"index.php?func=viewlist&amp;viewstyle=$viewstyle&amp;idnum=$idnum&amp;sortfield=$sortfield&amp;sortby=$sortby&amp;of=$pof\"><img src=\"images/left.gif\" border=\"0\"></a> . ";
        }

        if ($nrows % $limit) {
            $pages++;
        }

        for ($i = 1; $i <= $pages; $i++) {
            $nof = $limit * ($i - 1);

            if ($nof == $of) {
                $nav .= '';

                if (1 == $i) {
                    if (1 == $pages) {
                        $nav .= '<img src="images/left.gif" border="0"> - ' . $i . ' - <img src="images/right.gif" border="0">';
                    } else {
                        $nav .= '<img src="images/left.gif" border="0"> - ' . $i . ' . ';
                    }
                } elseif ($i == $pages) {
                    $nav .= $i . ' - <img src="images/right.gif" border="0">';
                } else {
                    $nav .= $i . ' - ';
                }

                $nav .= '';
            } else {
                $nav .= "<a href=\"index.php?func=viewlist&amp;viewstyle=$viewstyle&amp;idnum=$idnum&amp;sortfield=$sortfield&amp;sortby=$sortby&amp;of=$nof\">$i</a> . ";
            }
        }

        if (!(($of / $limit) == ($pages - 1)) && 1 != $pages) {
            $nof = $of + $limit;

            $nav .= "<a href=\"index.php?func=viewlist&amp;viewstyle=$viewstyle&amp;idnum=$idnum&amp;sortfield=itemname&amp;sortby=desc&amp;of=$nof\"><img src=\"images/right.gif\" border=\"0\"></a>";
        }
    }

    $nav .= '</center></td></tr></table>';

    echo (string)$nav;
}

function viewlist($viewstyle, $idnum, $sortfield, $sortby, $of)
{
    global $tbcolor, $xoopsDB, $limit;

    TopPart();

    if (0 == $viewstyle) {
        echo "<br>\n";

        $result = $xoopsDB->query('select catname from ' . $xoopsDB->prefix('pricelist_categories') . " where caid='$idnum'");

        [$catname] = $xoopsDB->fetchRow($result);

        $resultcount = $selcat = $xoopsDB->query('select catid from ' . $xoopsDB->prefix('pricelist_items') . " where catid='$idnum'");

        $nrows = $xoopsDB->getRowsNum($resultcount);

        OpenTable();

        echo '<center>'
             . _VIEWINGITEMSINCAT
             . ": $catname<br>"
             . _FOUND_TEXT
             . " $nrows "
             . _MATCHES_TEXT
             . "</center><br>\n"
             . "<center><table><tr class=''>"
             . "<td align=\"center\"><a href=\"index.php?func=viewlist&amp;viewstyle=0&amp;idnum=$idnum&amp;sortfield=itemname&amp;sortby=desc&amp;of=$of\"><img src=\"images/down.gif\"></a>"
             . _ITEMNAME
             . ''
             . "<a href=\"index.php?func=viewlist&amp;viewstyle=0&amp;idnum=$idnum&amp;sortfield=itemname&amp;sortby=asc&amp;of=$of\"><img src=\"images/up.gif\" ></a></td>\n"
             . "<td align=\"center\"><a href=\"index.php?func=viewlist&amp;viewstyle=0&amp;idnum=$idnum&amp;sortfield=maker&amp;sortby=desc&amp;of=$of\"><img src=\"images/down.gif\"></a>"
             . _MAKER
             . ''
             . "<a href=\"index.php?func=viewlist&amp;viewstyle=0&amp;idnum=$idnum&amp;sortfield=maker&amp;sortby=asc&amp;of=$of\"><img src=\"images/up.gif\" border=\"0\"></a></td>\n"
             . "<td align=\"center\"><a href=\"index.php?func=viewlist&amp;viewstyle=0&amp;idnum=$idnum&amp;sortfield=priceus&amp;sortby=desc&amp;of=$of\"><img src=\"images/down.gif\"></a>"
             . _PRICEUS
             . ''
             . "<a href=\"index.php?func=viewlist&amp;viewstyle=0&amp;idnum=$idnum&amp;sortfield=priceus&amp;sortby=asc&amp;of=$of\"><img src=\"images/up.gif\" border=\"0\"></a></td>\n"
             . "<td align=\"center\"><a href=\"index.php?func=viewlist&amp;viewstyle=0&amp;idnum=$idnum&amp;sortfield=priceru&amp;sortby=desc&amp;of=$of\"><img src=\"images/down.gif\"></a>"
             . _PRICERU
             . ''
             . "<a href=\"index.php?func=viewlist&amp;viewstyle=0&amp;idnum=$idnum&amp;sortfield=priceru&amp;sortby=asc&amp;of=$of\"><img src=\"images/up.gif\" border=\"0\"></a></td>\n"
             . '<td align="center">'
             . _ITEMBOX
             . "</td>\n"
             . '<td align="center">'
             . _COMMENT
             . "</td>\n"
             . "<td align=\"center\"><a href=\"index.php?func=viewlist&amp;viewstyle=0&amp;idnum=$idnum&amp;sortfield=dealerid&amp;sortby=desc&amp;of=$of\"><img src=\"images/down.gif\" border=\"0\"></a>"
             . _DEALERNAME
             . ''
             . "<a href=\"index.php?func=viewlist&amp;viewstyle=0&amp;idnum=$idnum&amp;sortfield=dealerid&amp;sortby=asc&amp;of=$of\"><img src=\"images/up.gif\" border=\"0\"></a></td>\n"
             . "<td align=\"center\"><a href=\"index.php?func=viewlist&amp;viewstyle=0&amp;idnum=$idnum&amp;sortfield=dealerid&amp;sortby=desc&amp;of=$of\"><img src=\"images/down.gif\" border=\"0\"></a>"
             . _DEALERCITY
             . ''
             . "<a href=\"index.php?func=viewlist&amp;viewstyle=0&amp;idnum=$idnum&amp;sortfield=dealerid&amp;sortby=asc&amp;of=$of\"><img src=\"images/up.gif\" border=\"0\"></a></td></tr>\n";

        $selitem = $xoopsDB->query('select itid, itemname, maker, priceus, priceru, itembox, comment, dealerid, catid from ' . $xoopsDB->prefix('pricelist_items') . " where catid = $idnum order by $sortfield $sortby limit $of,$limit");

        while (list($itid, $itemname, $maker, $priceus, $priceru, $itembox, $comment, $dealerid, $catid) = $xoopsDB->fetchRow($selitem)) {
            $seldealername = $xoopsDB->query('select dealername, dealercity, dealeruid from ' . $xoopsDB->prefix('pricelist_dealers') . " where deid=$dealerid");

            [$dealername, $dealercity, $dealeruid] = $xoopsDB->fetchRow($seldealername);

            if ($priceus <= 0) {
                $priceus = '';
            }

            if ($priceru <= 0) {
                $priceru = '';
            }

            echo "<tr class='item_head'><td valign=\"top\" class=''>$itemname</td>"
                 . "<td valign=\"top\" class=''>$maker</td>"
                 . "<td valign=\"top\" align=\"right\" class=''>$priceus</td>"
                 . "<td valign=\"top\" align=\"right\" class=''>$priceru</td>"
                 . "<td valign=\"top\" class=''>$itembox</td>"
                 . "<td valign=\"top\" class=''>$comment</td>"
                 . "<td valign=\"top\" class=''>";

            if ($dealeruid > 0) {
                echo '<a href="' . XOOPS_URL . "/userinfo.php?uid=$dealeruid\">$dealername</a>";
            } else {
                echo "<a href=\"index.php?func=dealerdetails&amp;iden=$dealerid\">$dealername</a>";
            }

            echo "</td><td valign=\"top\" class=''>$dealercity</td>\n";
        }

        echo '</tr></table>';

        navigation($of, $nrows, $idnum, $viewstyle, $sortfield, $sortby);

        echo "</center>\n";

        CloseTable();
    }

    if (1 == $viewstyle) {
        echo "<br>\n";

        $result = $xoopsDB->query('select dealername, dealercity, dealeruid from ' . $xoopsDB->prefix('pricelist_dealers') . " where deid='$idnum'");

        [$dealername, $dealercity, $dealeruid] = $xoopsDB->fetchRow($result);

        $resultcount = $selcat = $xoopsDB->query('select dealerid from ' . $xoopsDB->prefix('pricelist_items') . " where dealerid='$idnum'");

        $nrows = $xoopsDB->getRowsNum($resultcount);

        OpenTable();

        echo '<center>' . _VIEWINGITEMSSOLDBY . ': ';

        if ($dealeruid > 0) {
            echo '<a href="' . XOOPS_URL . "/userinfo.php?uid=$dealeruid\">$dealername</a>";
        } else {
            echo "<a href=\"index.php?func=dealerdetails&amp;iden=$idnum\">$dealername</a>";
        }

        echo '<br>'
             . _FOUND_TEXT
             . " $nrows "
             . _MATCHES_TEXT
             . "</center><br>\n"
             . '<center><table><tr>'
             . "<td align=\"center\" class=''><a href=\"index.php?func=viewlist&amp;viewstyle=1&amp;idnum=$idnum&amp;sortfield=itemname&amp;sortby=desc&amp;of=$of\"><img src=\"images/down.gif\" ></a>"
             . _ITEMNAME
             . ''
             . "<a href=\"index.php?func=viewlist&amp;viewstyle=1&amp;idnum=$idnum&amp;sortfield=itemname&amp;sortby=asc&amp;of=$of\"><img src=\"images/up.gif\"></a></td>\n"
             . "<td align=\"center\" class=''><a href=\"index.php?func=viewlist&amp;viewstyle=1&amp;idnum=$idnum&amp;sortfield=maker&amp;sortby=desc&amp;of=$of\"><img src=\"images/down.gif\"></a>"
             . _MAKER
             . ''
             . "<a href=\"index.php?func=viewlist&amp;viewstyle=1&amp;idnum=$idnum&amp;sortfield=maker&amp;sortby=asc&amp;of=$of\"><img src=\"images/up.gif\"></a></td>\n"
             . "<td align=\"center\" class=''><a href=\"index.php?func=viewlist&amp;viewstyle=1&amp;idnum=$idnum&amp;sortfield=priceus&amp;sortby=desc&amp;of=$of\"><img src=\"images/down.gif\"></a>"
             . _PRICEUS
             . ''
             . "<a href=\"index.php?func=viewlist&amp;viewstyle=1&amp;idnum=$idnum&amp;sortfield=priceus&amp;sortby=asc&amp;of=$of\"><img src=\"images/up.gif\"></a></td>\n"
             . "<td align=\"center\" class=''><a href=\"index.php?func=viewlist&amp;viewstyle=1&amp;idnum=$idnum&amp;sortfield=priceru&amp;sortby=desc&amp;of=$of\"><img src=\"images/down.gif\"></a>"
             . _PRICERU
             . ''
             . "<a href=\"index.php?func=viewlist&amp;viewstyle=1&amp;idnum=$idnum&amp;sortfield=priceru&amp;sortby=asc&amp;of=$of\"><img src=\"images/up.gif\"></a></td>\n"
             . "<td align=\"center\" class=''>"
             . _ITEMBOX
             . "</td>\n"
             . "<td align=\"center\" class=''>"
             . _COMMENT
             . "</td>\n"
             . "<td align=\"center\" class=''><a href=\"index.php?func=viewlist&amp;viewstyle=1&amp;idnum=$idnum&amp;sortfield=catid&amp;sortby=desc&amp;of=$of\"><img src=\"images/down.gif\" border=\"0\"></a>"
             . _CATNAME
             . ''
             . "<a href=\"index.php?func=viewlist&amp;viewstyle=1&amp;idnum=$idnum&amp;sortfield=catid&amp;sortby=asc&amp;of=$of\"><img src=\"images/up.gif\" border=\"0\"></a></td>\n";

        // $q = "select itid, itemname, maker, priceus, priceru, itembox, dealerid, catid from ".$xoopsDB->prefix("pricelist_items")." where dealerid = $idnum order by $sortfield $sortby limit 0,$nrows";

        // echo "$q<br>";

        $selitem = $xoopsDB->query('select itid, itemname, maker, priceus, priceru, itembox, comment, dealerid, catid from ' . $xoopsDB->prefix('pricelist_items') . " where dealerid = $idnum order by $sortfield $sortby limit $of,$limit");

        while (list($itid, $itemname, $maker, $priceus, $priceru, $itembox, $comment, $dealerid, $catid) = $xoopsDB->fetchRow($selitem)) {
            $selcatname = $xoopsDB->query('select catname from ' . $xoopsDB->prefix('pricelist_categories') . " where caid=$catid");

            [$catname] = $xoopsDB->fetchRow($selcatname);

            if ($priceus <= 0) {
                $priceus = '';
            }

            if ($priceru <= 0) {
                $priceru = '';
            }

            echo "<tr bgcolor=\"$tccolor\">"
                 . "<td valign=\"top\" class=''>$itemname</td>"
                 . "<td valign=\"top\" class=''>$maker</td>"
                 . "<td valign=\"top\" align=\"right\" class=''>$priceus</td>"
                 . "<td valign=\"top\" align=\"right\" class=''>$priceru</td>"
                 . "<td valign=\"top\" class=''>$itembox</td>"
                 . "<td valign=\"top\" class=''>$comment</td>"
                 . "<td valign=\"top\" class=''>$catname</td>\n";
        }

        echo '</tr></table>';

        navigation($of, $nrows, $idnum, $viewstyle, $sortfield, $sortby);

        echo "</center>\n";

        CloseTable();
    }

    BotPart();
}

function navigations($of, $nrows, $sortfield, $sortby, $query, $bool, $secsearch, $catid, $dealerid)
{
    global $galleryvar, $xoopsDB, $limit;

    if (!$of) {
        $of = 0;
    }

    $nav = '<table><tr><td><center>';

    $pages = (int)($nrows / $limit);

    if ($nrows > 0 & $pages >= 1) {
        if (0 != $of) {
            $pof = $of - $limit;

            $nav = "<a href=\"index.php?func=search&amp;sortfield=$sortfield&amp;sortby=$sortby&amp;of=$pof&amp;query=$query&amp;bool=$bool&amp;secsearch=$secsearch&amp;catid=$catid&amp;dealerid=$dealerid\"><img src=\"images/left.gif\" border=\"0\"></a> . ";
        }

        if ($nrows % $limit) {
            $pages++;
        }

        for ($i = 1; $i <= $pages; $i++) {
            $nof = $limit * ($i - 1);

            if ($nof == $of) {
                $nav .= '';

                if (1 == $i) {
                    if (1 == $pages) {
                        $nav .= '<img src="images/left.gif"> - ' . $i . ' - <img src="images/right.gif">';
                    } else {
                        $nav .= '<img src="images/left.gif"> - ' . $i . ' . ';
                    }
                } elseif ($i == $pages) {
                    $nav .= $i . ' - <img src="images/right.gif">';
                } else {
                    $nav .= $i . ' - ';
                }

                $nav .= '';
            } else {
                $nav .= "<a href=\"index.php?func=search&amp;sortfield=$sortfield&amp;sortby=$sortby&amp;of=$nof&amp;query=$query&amp;bool=$bool&amp;secsearch=$secsearch&amp;catid=$catid&amp;dealerid=$dealerid\">$i</a> . ";
            }
        }

        if (!(($of / $limit) == ($pages - 1)) && 1 != $pages) {
            $nof = $of + $limit;

            $nav .= "<a href=\"index.php?func=search&amp;sortfield=itemname&amp;sortby=desc&amp;of=$nof&amp;query=$query&amp;bool=$bool&amp;secsearch=$secsearch&amp;catid=$catid&amp;dealerid=$dealerid\"><img src=\"images/right.gif\"></a>";
        }
    }

    $nav .= '</center></td></tr></table>';

    echo (string)$nav;
}

function search($query, $bool, $secsearch, $catid, $dealerid, $sortfield, $sortby, $of)
{
    global $xoopsDB, $limit;

    TopPart();

    echo "<br>\n";

    $search_word = false;

    if ('' != $query) {
        $uquery = mb_strtoupper($query);

        $lquery = mb_strtolower($query);

        $w = [];

        $ws = [];

        $qwords = explode(' ', $query);

        if (phpversion() >= '4.0.0') { // php4
            foreach ($qwords as $word) {
                $w[] = "%$word%";

                $ws[] = $word;
            }
        } else { // php3
            while (list($word) = each($qwords)) {
                $w[] = "%$word%";

                $ws[] = $word;
            }
        }

        $search_word = true;
    }

    $mquery = 'SELECT i.itemname AS itemname, i.maker, i.priceus, i.priceru, i.itembox, i.comment, d.dealername, d.dealercity, d.dealeruid, d.deid, c.catname from ' . $xoopsDB->prefix('pricelist_items') . ' AS i ';

    $mquery .= 'LEFT JOIN ' . $xoopsDB->prefix('pricelist_dealers') . ' AS d ON d.deid=i.dealerid ';

    $mquery .= 'LEFT JOIN ' . $xoopsDB->prefix('pricelist_categories') . ' as c ON c.caid=i.catid WHERE ';

    if ($dealerid) {
        $mquery .= "(i.dealerid=$dealerid)";
    }

    if ($catid && $dealerid) {
        $mquery .= " AND (i.catid=$catid)";
    }

    if ($catid && !$dealerid) {
        $mquery .= "(i.catid=$catid)";
    }

    if ($search_word) {
        if ($catid || $dealerid) {
            $mquery .= ' AND ';
        }

        $flag = false;

        foreach ($w as $word) {
            if ($flag) {
                switch ($bool) {
                    case 'AND':
                        $mquery .= ' AND ';
                        break;
                    case 'OR':
                        $mquery .= ' OR ';
                        break;
                }
            }

            $uword = mb_strtoupper($word);

            $mquery .= '(';

            if (1 == $secsearch) {
                $mquery .= "UCASE(i.itemname) LIKE '%$uword%' OR ";
            }

            if (2 == $secsearch) {
                $mquery .= "UCASE(i.maker) LIKE '%$uword%' OR ";
            }

            if (3 == $secsearch) {
                $mquery .= "UCASE(d.dealercity) LIKE '%$uword%' OR ";
            }

            $mquery .= '0)'; // little bit of filler to reduce code requirements :-)

            $flag = true;
        }
    }

    $mquery .= " ORDER BY $sortfield $sortby";

    $lmquery = $mquery;

    $lmquery .= " LIMIT $of,$limit";

    // echo "$dealerid / $catid / $search_word / $mquery";

    $qanswer = $xoopsDB->query($mquery);

    $nrows = $xoopsDB->getRowsNum($qanswer);

    if ($nrows > 0) {
        OpenTable();

        echo '<center><font class="title">' . _FOUND_TEXT . " $nrows " . _ITEMS_TEXT . ' ' . _CONTAINING_TEXT . ": $query</font></center>\n";

        CloseTable();

        echo '<br>';

        OpenTable();

        echo "<center><table class='even'><tr class='itemHead'>"
             . "<td align=\"center\" class=''><a href=\"index.php?func=search&amp;sortfield=itemname&amp;sortby=desc&amp;of=$of&amp;query=$query&amp;bool=$bool&amp;secsearch=$secsearch&amp;catid=$catid&amp;dealerid=$dealerid\"><img src=\"images/down.gif\"></a>"
             . _ITEMNAME
             . ''
             . "<a href=\"index.php?func=search&amp;sortfield=itemname&amp;sortby=asc&amp;of=$of&amp;query=$query&amp;bool=$bool&amp;secsearch=$secsearch&amp;catid=$catid&amp;dealerid=$dealerid\"><img src=\"images/up.gif\" border=\"0\"></a></td>\n"
             . "<td align=\"center\" class=''><a href=\"index.php?func=search&amp;sortfield=maker&amp;sortby=desc&amp;of=$of&amp;query=$query&amp;bool=$bool&amp;secsearch=$secsearch&amp;catid=$catid&amp;dealerid=$dealerid\"><img src=\"images/down.gif\"></a>"
             . _MAKER
             . ''
             . "<a href=\"index.php?func=search&amp;sortfield=maker&amp;sortby=asc&amp;of=$of&amp;query=$query&amp;bool=$bool&amp;secsearch=$secsearch&amp;catid=$catid&amp;dealerid=$dealerid\"><img src=\"images/up.gif\" border=\"0\"></a></td>\n"
             . "<td align=\"center\" class=''><a href=\"index.php?func=search&amp;sortfield=priceus&amp;sortby=desc&amp;of=$of&amp;query=$query&amp;bool=$bool&amp;secsearch=$secsearch&amp;catid=$catid&amp;dealerid=$dealerid\"><img src=\"images/down.gif\"></a>"
             . _PRICEUS
             . ''
             . "<a href=\"index.php?func=search&amp;sortfield=priceus&amp;sortby=asc&amp;of=$of&amp;query=$query&amp;bool=$bool&amp;secsearch=$secsearch&amp;catid=$catid&amp;dealerid=$dealerid\"><img src=\"images/up.gif\" border=\"0\"></a></td>\n"
             . "<td align=\"center\" class=''><a href=\"index.php?func=search&amp;sortfield=priceru&amp;sortby=desc&amp;of=$of&amp;query=$query&amp;bool=$bool&amp;secsearch=$secsearch&amp;catid=$catid&amp;dealerid=$dealerid\"><img src=\"images/down.gif\"></a>"
             . _PRICERU
             . ''
             . "<a href=\"index.php?func=search&amp;sortfield=priceru&amp;sortby=asc&amp;of=$of&amp;query=$query&amp;bool=$bool&amp;secsearch=$secsearch&amp;catid=$catid&amp;dealerid=$dealerid\"><img src=\"images/up.gif\" ></a></td>\n"
             . "<td align=\"center\" class=''>"
             . _ITEMBOX
             . "</td>\n"
             . "<td align=\"center\" class=''>"
             . _COMMENT
             . "</td>\n"
             . "<td align=\"center\" class=''><a href=\"index.php?func=search&amp;sortfield=catid&amp;sortby=desc&amp;of=$of&amp;query=$query&amp;bool=$bool&amp;secsearch=$secsearch&amp;catid=$catid&amp;dealerid=$dealerid\"><img src=\"images/down.gif\" border=\"0\"></a>"
             . _CATNAME
             . ''
             . "<a href=\"index.php?func=search&amp;sortfield=catid&amp;sortby=asc&amp;of=$of&amp;query=$query&amp;bool=$bool&amp;secsearch=$secsearch&amp;catid=$catid&amp;dealerid=$dealerid\"><img src=\"images/up.gif\"></a></td>\n"
             . "<td align=\"center\" class=''><a href=\"index.php?func=search&amp;sortfield=dealerid&amp;sortby=desc&amp;of=$of&amp;query=$query&amp;bool=$bool&amp;secsearch=$secsearch&amp;catid=$catid&amp;dealerid=$dealerid\"><img src=\"images/down.gif\" border=\"0\"></a>"
             . _DEALERNAME
             . ''
             . "<a href=\"index.php?func=search&amp;sortfield=dealerid&amp;sortby=asc&amp;of=$of&amp;query=$query&amp;bool=$bool&amp;secsearch=$secsearch&amp;catid=$catid&amp;dealerid=$dealerid\"><img src=\"images/up.gif\"></a></td>\n"
             . "<td align=\"center\" class=''><a href=\"index.php?func=search&amp;sortfield=dealerid&amp;sortby=desc&amp;of=$of&amp;query=$query&amp;bool=$bool&amp;secsearch=$secsearch&amp;catid=$catid&amp;dealerid=$dealerid\"><img src=\"images/down.gif\" border=\"0\"></a>"
             . _DEALERCITY
             . ''
             . "<a href=\"index.php?func=search&amp;sortfield=dealerid&amp;sortby=asc&amp;of=$of&amp;query=$query&amp;bool=$bool&amp;secsearch=$secsearch&amp;catid=$catid&amp;dealerid=$dealerid\"><img src=\"images/up.gif\"></a></td></tr>\n";

        $qanswer = $xoopsDB->query($lmquery);

        while (list($itemname, $maker, $priceus, $priceru, $itembox, $comment, $dealername, $dealercity, $dealeruid, $deid, $catname) = $xoopsDB->fetchRow($qanswer)) {
            if ($priceus <= 0) {
                $priceus = '';
            }

            if ($priceru <= 0) {
                $priceru = '';
            }

            if ((1 == $secsearch) && ('' != $query)) {
                foreach ($ws as $word) {
                    if (eregi(sql_regcase($word), $itemname, $spart)) {
                        $itemname = eregi_replace($spart[0], '<u><b>\\0</b></u>', $itemname);
                    }
                }
            }

            echo "<tr><td class='outer'>$itemname</td>";

            if ((2 == $secsearch) && ('' != $query)) {
                foreach ($ws as $word) {
                    if (eregi(sql_regcase($word), $maker, $spart)) {
                        $maker = eregi_replace($spart[0], '<u><b>\\0</b></u>', $maker);
                    }
                }
            }

            echo "<td class='outer'>$maker</td>" . "<td class='outer'>$priceus</td>" . "<td class='outer'>$priceru</td>" . "<td class='outer'>$itembox</td>" . "<td class='outer'>$comment</td>" . "<td class='outer'>$catname</td>";

            if ($dealeruid > 0) {
                echo "<td class='outer'><a href=\"" . XOOPS_URL . "/userinfo.php?uid=$dealeruid\">$dealername</a></td>";
            } else {
                echo "<td class='outer'><a href=\"index.php?func=dealerdetails&amp;iden=$deid\">$dealername</a></td>";
            }

            if ((3 == $secsearch) && ('' != $query)) {
                foreach ($ws as $word) {
                    if (eregi(sql_regcase($word), $dealercity, $spart)) {
                        $dealercity = eregi_replace($spart[0], '<u><b>\\0</b></u>', $dealercity);
                    }
                }
            }

            echo "<td class='outer'>$dealercity</td>";
        }

        echo "</tr></table>\n";

        navigations($of, $nrows, $sortfield, $sortby, $query, $bool, $secsearch, $catid, $dealerid);

        CloseTable();
    } else {
        OpenTable();

        echo '<center>' . _FOUNDNOMATCHING . ": $query</center>\n";

        CloseTable();
    }

    BotPart();
}

function dealerdetails($iden)
{
    global $tbcolor, $xoopsDB;

    $result = $xoopsDB->query('select deid, dealername, dealercity, dealermaker, dealeradd from ' . $xoopsDB->prefix('pricelist_dealers') . " where deid = '$iden'");

    [$deid, $dealername, $dealercity, $dealermaker, $dealeradd] = $xoopsDB->fetchRow($result);

    TopPart();

    echo "<br>\n";

    OpenTable();

    echo "<center class='itemHead'>" . _DETAILSFOR . " $dealername</center><br>\n" . "<center><table class='odd'>\n" . "<tr><td class='outer'>" . _CITY . ": </td><td class='outer'>$dealercity</td></tr>\n" // ."<tr><td class=\"th\">"._MAKER.": </td><td class=\"tc\">$dealermaker</td></tr>\n"
         . "<tr><td class='outer'>" . _COMMENT . ": </td><td class='outer'>$dealeradd</td></tr>\n";

    echo "</table>\n";

    echo '<br><center><input type="button" value="' . _BACK_TEXT . "\" onClick=\"javascript:history.go(-1)\"></center>\n";

    CloseTable();

    BotPart();
}

switch ($func) {
    default:
        AdIndex();
        break;
    case 'viewlist':
        viewlist($viewstyle, $idnum, $sortfield, $sortby, $of);
        break;
    case 'BrowseCat':
        BrowseCat();
        break;
    case 'BrowseDealer':
        BrowseDealer();
        break;
    case 'search':
        search($query, $bool, $secsearch, $catid, $dealerid, $sortfield, $sortby, $of);
        break;
    case 'dealerdetails':
        dealerdetails($iden);
        break;
}
require_once '../../footer.php';
