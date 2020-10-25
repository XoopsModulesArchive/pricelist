<?php

// ------------------------------------------------------------------------- //
// XOOPS - PHP Content Management System //
// <http://xoops.codigolivre.org.br> //
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
// ORIGINAL FILE INFO
include 'admin_header.php';
include '../include/functions.php';
include '../cache/config.php';
require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
require_once XOOPS_ROOT_PATH . '/class/module.errorhandler.php';
$myts = MyTextSanitizer::getInstance();
$eh = new ErrorHandler();
$mytree = new XoopsTree($xoopsDB->prefix('pricelist_cat'), 'cid', 'pid');
function title($title)
{
    xoops_cp_header();

    OpenTable();

    echo "<div align=\"center\"><b>$title</b></div>";

    CloseTable();
}

function AdminAds()
{
    OpenTable();

    title('' . _ADMINADS . '');

    echo '<center><b><strong>·</strong></b> <a href="index.php?op=configedit">'
         . _CONFIGURE
         . '</a> <b><strong>·</strong></b> <a href="index.php?op=managedealers">'
         . _MANAGEDEALERS
         . '</a> <b><strong>·</strong></b> <a href="index.php?op=managecategories">'
         . _MANAGECATS
         . "</a> <b><strong>·</strong></b>\n"
         . '<a href="index.php?op=manageitems">'
         . _MANAGEITEMS
         . '</a> <b><strong>·</strong></b> <a href="index.php?op=csvimport">'
         . _CSVIMPORT
         . '</a> <b><strong>·</strong></b></center>';

    CloseTable();
}

function ManageDealers()
{
    global $xoopsDB;

    title('' . _ADMINADSDEALERS . '');

    OpenTable();

    echo '<center><b><u>'
         . _ADDNEWDEALER
         . "</u></b></center><br>\n"
         . "<center><form action=\"index.php\" method=\"post\">\n"
         . "<table border=\"0\">\n"
         . '<tr><td>'
         . _NAME
         . ": </td><td><input type=\"text\" name=\"dealername\" size=\"28\"></td></tr>\n"
         . '<tr><td>'
         . _CITY
         . ": </td><td><input type=\"text\" name=\"dealercity\" size=\"28\"></td></tr>\n"
         // ."<tr><td>"._MAKER.": </td><td><input type=\"text\" name=\"dealermaker\" size=\"28\"></td></tr>\n"
         . "<input type=\"hidden\" name=\"dealermaker\" value=\"0\">\n"
         . '<tr><td>'
         . _COMMENT
         . ": </td><td><input type=\"text\" name=\"dealeradd\" size=\"28\"></td></tr>\n"
         . "<tr><td>&nbsp;</td><td align=\"right\"><input type=\"hidden\" name=\"op\" value=\"dbadddealer\">\n"
         . '<input type="submit" value="'
         . _SUBMIT
         . "\"></form></td></tr>\n"
         . '</table></center>';

    CloseTable();

    echo '<br>';

    OpenTable();

    echo '<center><b><u>' . _ADDEDDEALER . "</u></b></center><br>\n" . "<center><form action=\"index.php\" method=\"post\">\n" . "<table border=\"0\">\n" . "<tr><td>\n" . "<select name=\"deid\">\n" . '<option>' . _SELDEALER . "</option>\n";

    $selcat = $xoopsDB->query('select deid, dealername from ' . $xoopsDB->prefix('pricelist_dealers') . ' order by dealername');

    while (list($deid, $dealername) = $xoopsDB->fetchRow($selcat)) {
        echo "<option name=\"deid\" value=\"$deid\">$dealername</option>";
    }

    echo "</select><input type=\"hidden\" name=\"op\" value=\"editdeletedealer\">\n" . '</td><td align="center"><input type="submit" value="' . _GO . "\"></td></tr></table>\n" . '</form></center>';

    CloseTable();
}

function EditDeleteDealer($deid)
{
    global $xoopsDB;

    title('' . _ADMINADSAEDEALER . '');

    OpenTable();

    $result = $xoopsDB->query('select dealername, dealercity, dealermaker, dealeradd, dealeruid from ' . $xoopsDB->prefix('pricelist_dealers') . " where deid='$deid'");

    [$dealername, $dealercity, $dealermaker, $dealeradd, $dealeruid] = $xoopsDB->fetchRow($result);

    echo "<center><form action=\"index.php\" method=\"post\">\n"
         . "<table border=\"0\">\n"
         . '<tr><td>'
         . _NAME
         . ": </td><td><input type=\"text\" name=\"dealername\" value=\"$dealername\" size=\"28\"></td></tr>\n"
         . '<tr><td>'
         . _CITY
         . ": </td><td><input type=\"text\" name=\"dealercity\" value=\"$dealercity\" size=\"28\"></td></tr>\n"
         // ."<tr><td>"._MAKER.": </td><td><input type=\"text\" name=\"dealermaker\" value=\"$dealermaker\" size=\"28\"></td></tr>\n"
         . "<input type=\"hidden\" name=\"dealermaker\" value=\"0\">\n"
         . '<tr><td>'
         . _COMMENT
         . ": </td><td><input type=\"text\" name=\"dealeradd\" value=\"$dealeradd\" size=\"28\"></td></tr>\n"
         . '<tr><td>'
         . _BBAFUNC
         . ": </td><td>\n"
         . "<select name=\"funcz\">\n"
         . '<option name="funcz" value="0">'
         . _BBAEDIT
         . '</option><option name="funcz" value="1">'
         . _BBADELETE
         . "</option></select></td></tr>\n"
         . '<tr><td>&nbsp;</td><td align="right">'
         . "<input type=\"hidden\" name=\"dealeruid\" value=\"$dealeruid\">"
         . "<input type=\"hidden\" name=\"deid\" value=\"$deid\">"
         . "<input type=\"hidden\" name=\"op\" value=\"dbeditdeletedealer\">\n"
         . "<input type=\"hidden\" name=\"ok\" value=\"0\">\n"
         . '<input type="submit" value="'
         . _SUBMIT
         . "\"></form></td></tr>\n"
         . '</table></center>';

    CloseTable();
}

function DbAddDealer($dealername, $dealercity, $dealermaker, $dealeradd)
{
    global $xoopsDB;

    $dname = mb_strtoupper($dealername);

    $result = $xoopsDB->query('SELECT uid FROM ' . $xoopsDB->prefix('users') . " where UCASE(name) LIKE '$dname'");

    $numrows = $xoopsDB->getRowsNum($result);

    if ($numrows > 0) {
        [$dealeruid] = $xoopsDB->fetchRow($result);
    } else {
        $dealeruid = 0;
    }

    $result = $xoopsDB->query('insert into ' . $xoopsDB->prefix('pricelist_dealers') . " values (NULL, '$dealername', '$dealercity', '$dealermaker', '$dealeradd', '$dealeruid')");

    if (!$result) {
        echo 'Error! Cannot Update!';

        exit();
    }

    redirect_header('index.php', 1, _UPDATED);
}

function DbEditDeleteDealer($funcz, $ok, $deid, $dealername, $dealercity, $dealermaker, $dealeradd, $dealeruid)
{
    global $xoopsDB;

    if (0 == $funcz) {
        $dname = mb_strtoupper($dealername);

        $result = $xoopsDB->query('SELECT uid FROM ' . $xoopsDB->prefix('users') . " where UCASE(name) LIKE '$dname'");

        $numrows = $xoopsDB->getRowsNum($result);

        if ($numrows > 0) {
            [$dealeruid] = $xoopsDB->fetchRow($result);
        } else {
            $dealeruid = 0;
        }

        $xoopsDB->query('update ' . $xoopsDB->prefix('pricelist_dealers') . " set dealername='$dealername', dealercity='$dealercity', dealermaker='$dealermaker', dealeradd='$dealeradd', dealeruid='$dealeruid' where deid=$deid");

        redirect_header('index.php', 1, _UPDATED);
    }

    if (1 == $funcz) {
        title('' . _ADMINDELDEALERS . '');

        OpenTable();

        if (0 == $ok) {
            echo '<center><b>' . _AUTHORDELSURE . " $dealername " . _FRDEALERLISTQ . '</b>' . "<br><br>[ <a href=\"index.php?op=dbdeletedealer&amp;deid=$deid\">" . _YES . '</a> ] [ <a href="index.php?op=adminads">' . _NO . '</a> ]</center>';
        }

        CloseTable();
    }
}

function DbDeleteDealer($deid)
{
    global $xoopsDB;

    $xoopsDB->queryF('DELETE FROM ' . $xoopsDB->prefix('pricelist_dealers') . " WHERE deid=$deid");

    $xoopsDB->queryF('DELETE FROM ' . $xoopsDB->prefix('pricelist_items') . " where dealerid=$deid");

    redirect_header('index.php', 1, _DELETED);
}

function ManageCategories()
{
    global $xoopsDB;

    title('' . _ADMINADSCATS . '');

    OpenTable();

    echo '<center><b><u>' . _ADDNEWCAT . "</u></b></center><br>\n" . "<center><form action=\"index.php\" method=\"post\">\n" . '<table border="0"><tr>' . '<select name="parent"><option name="parent" value="-1" selected>' . _PARENTCATEGORY . "...</option>\n";

    $selcat = $xoopsDB->query('select caid, catname from ' . $xoopsDB->prefix('pricelist_categories') . ' WHERE parent < 0 order by catname');

    while (list($caid, $catname) = $xoopsDB->fetchRow($selcat)) {
        echo "<option name=\"parent\" value=\"$caid\">$catname</option>";
    }

    echo "</select></td>\n" . '<td><input type="text" size="28" name="catname"></td>' . '<td><input type="hidden" name="op" value="dbaddcat">' . '<input type="submit" value="' . _ADD . "\"></td></tr>\n" . "</table></center></form>\n";

    CloseTable();

    echo '<br>';

    OpenTable();

    echo '<center><b><u>' . _ADDEDCAT . "</u></b></center><br>\n" . "<center><form action=\"index.php\" method=\"post\">\n" . '<table border="0">' . "<tr><td>\n" . '<select name="caid"><option>' . _ASELECTCATEGORY . "...</option>\n";

    $selcat = $xoopsDB->query('select caid, catname from ' . $xoopsDB->prefix('pricelist_categories') . ' WHERE parent <0 order by catname');

    while (list($caid, $catname) = $xoopsDB->fetchRow($selcat)) {
        echo "<option name=\"caid\" value=\"$caid\">$catname -</option>";

        $pselcat = $xoopsDB->query('select caid, catname from ' . $xoopsDB->prefix('pricelist_categories') . " WHERE parent = $caid order by catname");

        while (list($caid, $catname) = $xoopsDB->fetchRow($pselcat)) {
            echo "<option name=\"caid\" value=\"$caid\">-- $catname</option>";
        }
    }

    echo "</select>\n" . '</td>' . '<td align="center"><input type="hidden" name="op" value="editdeletecat"><input type="submit" value="' . _GO . "\"></td></tr>\n" . "</table></center></form>\n";

    CloseTable();
}

function DbAddCat($catname, $parent)
{
    global $xoopsDB;

    $result = $xoopsDB->query('insert into ' . $xoopsDB->prefix('pricelist_categories') . " values (NULL, '$catname', '$parent')");

    if (!$result) {
        echo 'Error! Cannot Update!';

        exit();
    }

    redirect_header('index.php', 1, _UPDATED);
}

function DbEditDeleteCat($functz, $ok, $caid, $catname, $parent)
{
    global $xoopsDB;

    if (0 == $functz) {
        $xoopsDB->query('update ' . $xoopsDB->prefix('pricelist_categories') . " set catname='$catname' set parent='$parent' where caid=$caid");

        redirect_header('index.php', 1, _UPDATED);
    }

    if (1 == $functz) {
        title('' . _ADMINADSAECAT . '');

        OpenTable();

        if (0 == $ok) {
            echo '<center><b>' . _AUTHORDELSURE . " $catname " . _FRCATLISTQ . '</b>' . "<br><br>[ <a href=\"index.php?op=dbdeletecat&amp;caid=$caid\">" . _YES . '</a> ] [ <a href="index.php?op=adminads">' . _NO . '</a> ]</center>';
        }

        CloseTable();
    }
}

function DbDeleteCat($caid)
{
    global $xoopsDB;

    $xoopsDB->queryF('delete from ' . $xoopsDB->prefix('pricelist_categories') . " where caid='$caid'");

    $xoopsDB->queryF('delete from ' . $xoopsDB->prefix('pricelist_categories') . " where parent='$caid'");

    $xoopsDB->queryF('delete from ' . $xoopsDB->prefix('pricelist_items') . " where catid='$caid'");

    redirect_header('index.php', 1, _UPDATED);
}

function EditDeleteCat($caid)
{
    global $xoopsDB;

    title('' . _ADMINADSAECAT . '');

    OpenTable();

    $result = $xoopsDB->query('select catname, parent from ' . $xoopsDB->prefix('pricelist_categories') . " where caid='$caid'");

    [$catname, $parent] = $xoopsDB->fetchRow($result);

    $catname = stripslashes($catname);

    echo "<center><form action=\"index.php\" method=\"post\">\n"
         . '<table border="0"><tr><td>'
         . _OLDNAME
         . ": </td><td>$catname</td><tr><td>"
         . _NEWNAME
         . ": </td><td><input type=\"text\" name=\"catname\" value=\"$catname\" size=\"28\"></td</tr>\n"
         . '<tr><td colspan="2" align="right"><select name="functz"><option name="functz" value="0">'
         . _BBAEDIT
         . '</option><option name="functz" value="1">'
         . _BBADELETE
         . "</option></select>\n"
         . "<input type=\"hidden\" name=\"caid\" value=\"$caid\"><input type=\"hidden\" name=\"parent\" value=\"$parent\"><input type=\"hidden\" name=\"ok\" value=\"0\"><input type=\"hidden\" name=\"op\" value=\"dbeditdeletecat\"><input type=\"submit\" value=\""
         . _SUBMIT
         . '"></td></tr></table></form>';

    CloseTable();
}

function ManageItems()
{
    global $xoopsDB;

    title('' . _ADMINADSITEMS . '');

    OpenTable();

    echo '<center><b><u>'
         . _ADDNEWITEM
         . "</u></b></center><br>\n"
         . "<center><form action=\"index.php\" method=\"post\">\n"
         . "<table border=\"0\">\n"
         . '<tr><td>'
         . _NAME
         . ": </td><td><input type=\"text\" name=\"itemname\" size=\"28\"></td></tr>\n"
         . '<tr><td>'
         . _MAKER
         . ": </td><td><input type=\"text\" name=\"maker\" size=\"28\"></td></tr>\n"
         . '<tr><td>'
         . _CATEGORY
         . ": </td><td><select name=\"catid\">\n"
         . '<option>'
         . _ASELECTCATEGORY
         . "...</option>\n";

    $selcat = $xoopsDB->query('select caid, catname from ' . $xoopsDB->prefix('pricelist_categories') . ' order by catname');

    while (list($caid, $catname) = $xoopsDB->fetchRow($selcat)) {
        echo "<option name=\"catid\" value=\"$caid\">$catname</option>";
    }

    echo "</select></td></tr>\n" . '<tr><td>' . _DEALER . ": </td><td><select name=\"dealerid\">\n" . '<option>' . _SELDEALER . "</option>\n";

    $selcat = $xoopsDB->query('select deid, dealername from ' . $xoopsDB->prefix('pricelist_dealers') . ' order by dealername');

    while (list($deid, $dealername) = $xoopsDB->fetchRow($selcat)) {
        echo "<option name=\"dealerid\" value=\"$deid\">$dealername</option>";
    }

    echo "</select></td></tr>\n"
         . '<tr><td>'
         . _PRICEUS
         . ": </td><td><input type=\"text\" size=\"18\" name=\"priceus\" value=\"0.00\"></td></tr>\n"
         . '<tr><td>'
         . _PRICERU
         . ": </td><td><input type=\"text\" size=\"18\" name=\"priceru\" value=\"0.00\"></td></tr>\n"
         . '<tr><td>'
         . _ITEMBOX
         . ": </td><td><input type=\"text\" size=\"18\" name=\"itembox\" value=\"\"></td></tr>\n"
         . '<tr><td>'
         . _COMMENT
         . ": </td><td><input type=\"text\" size=\"18\" name=\"comment\" value=\"\"></td></tr>\n"
         . "<tr><td colspan=\"2\" align=\"right\"><input type=\"hidden\" name=\"op\" value=\"dbadditem\">\n"
         . '<input type="submit" value="'
         . _SUBMIT
         . "\"></form></td></tr>\n"
         . '</table></center>';

    CloseTable();

    echo '<br>';

    OpenTable();

    echo '<center><b><u>'
         . _ADDEDITEM
         . "</u></b></center><br>\n"
         . "<center><form action=\"index.php\" method=\"post\">\n"
         . '<table border="0"><tr><td>'
         . _VIEWACCTO
         . ': <select name="listname">'
         . '<option>'
         . _SELLISTMETHOD
         . '</option>'
         . '<option name="listname" value="0">'
         . _CATEGORY
         . "</option>\n"
         . '<option name="listname" value="1">'
         . _DEALERNAME
         . "</option>\n"
         . '</select>'
         . '</td><td><input type="hidden" name="expand" value="0"><input type="hidden" name="iden" value="0">'
         . '<input type="hidden" name="op" value="editdeleteitem">'
         . '<input type="submit" value="'
         . _GO
         . "\"></td></tr>\n"
         . "</table></center></form>\n";

    CloseTable();
}

function EditDeleteItem($listname, $expand, $iden)
{
    global $xoopsDB;

    title('' . _ADMINADSITEMS . '');

    OpenTable();

    if (0 == $listname) {
        echo "<form action=\"index.php\" method=\"post\">\n" . "<center><table border=\"0\">\n" . "<tr>\n" . '<td><select name="iden"><option>' . _SELCAT . "</option>\n";

        $selcategory = $xoopsDB->query('select caid, catname from ' . $xoopsDB->prefix('pricelist_categories') . ' order by catname');

        while (list($caid, $catname) = $xoopsDB->fetchRow($selcategory)) {
            echo "<option name=\"iden\" value=\"$caid\">$catname</option>\n";
        }

        echo '</select><input type="hidden" name="listname" value="0">' . '</td>' . '<td><input type="submit" value="' . _GO . '"></td></tr></table></center>' . "<input type=\"hidden\" name=\"expand\" value=\"1\"><input type=\"hidden\" name=\"op\" value=\"editdeleteitem\"></form><br>\n";

        if (1 == $expand) {
            echo '<center><table border="1">'
                 . '<tr><td align="center"><b>'
                 . _ITEM
                 . '</b></td>'
                 . '<td align="center"><b>'
                 . _MAKER
                 . '</b></td>'
                 . '<td align="center"><b>'
                 . _DEALER
                 . '</b></td>'
                 . '<td align="center"><b>'
                 . _PRICEUS
                 . '</b></td>'
                 . '<td align="center"><b>'
                 . _PRICERU
                 . '</b></td>'
                 . '<td align="center"><b>'
                 . _ITEMBOX
                 . "</b></td>\n"
                 . '<td align="center"><b>'
                 . _COMMENT
                 . "</b></td>\n"
                 . '<td align="center"><b>'
                 . _BBAFUNC
                 . "</b></td></tr>\n";

            $selitem = $xoopsDB->query('select itid, itemname, maker, priceus, priceru, itembox, comment, dealerid from ' . $xoopsDB->prefix('pricelist_items') . " where (catid = '$iden')");

            while (list($itid, $itemname, $maker, $priceus, $priceru, $itembox, $comment, $dealerid) = $xoopsDB->fetchRow($selitem)) {
                $seldealername = $xoopsDB->query('select dealername from ' . $xoopsDB->prefix('pricelist_dealers') . " where deid=$dealerid");

                [$dealername] = $xoopsDB->fetchRow($seldealername);

                echo "<tr><td valign=\"top\">$itemname</td>"
                     . "<td valign=\"top\">$maker</td>"
                     . "<td valign=\"top\">$dealername</td>"
                     . "<td valign=\"top\" align=\"right\">$priceus</td>"
                     . "<td valign=\"top\" align=\"right\">$priceru</td>"
                     . "<td valign=\"top\">$itembox</td>\n"
                     . "<td valign=\"top\">$comment</td>\n";

                echo "<td align=\"center\"><a href=\"index.php?op=doedititem&amp;itid=$itid\">" . _BBAEDIT . "</a> / <a href=\"index.php?op=dodeleteitem&amp;itid=$itid&amp;ok=0\">" . _BBADELETE . '</a></td></tr>';
            }

            echo "</table></center>\n";
        }
    }

    if (1 == $listname) {
        echo "<form action=\"index.php\" method=\"post\">\n" . "<center><table border=\"0\">\n" . "<tr>\n" . '<td><select name="iden"><option>' . _SELDEALER . "</option>\n";

        $seldealer = $xoopsDB->query('select deid, dealername from ' . $xoopsDB->prefix('pricelist_dealers') . ' order by dealername');

        while (list($deid, $dealername) = $xoopsDB->fetchRow($seldealer)) {
            echo "<option name=\"iden\" value=\"$deid\">$dealername</option>\n";
        }

        echo '</select><input type="hidden" name="listname" value="1">' . '</td>' . '<td><input type="submit" value="' . _GO . '"></td></tr></table></center>' . "<input type=\"hidden\" name=\"expand\" value=\"1\"><input type=\"hidden\" name=\"op\" value=\"editdeleteitem\"></form><br>\n";

        if (1 == $expand) {
            echo '<center><table border="1"><tr>'
                 . '<td align="center"><b>'
                 . _ITEM
                 . '</b></td>'
                 . '<td align="center"><b>'
                 . _MAKER
                 . '</b></td>'
                 . '<td align="center"><b>'
                 . _CATEGORY
                 . "</b></td>\n"
                 . '<td align="center"><b>'
                 . _PRICEUS
                 . '</b></td>'
                 . '<td align="center"><b>'
                 . _PRICERU
                 . '</b></td>'
                 . '<td align="center"><b>'
                 . _ITEMBOX
                 . "</b></td>\n"
                 . '<td align="center"><b>'
                 . _COMMENT
                 . "</b></td>\n"
                 . '<td align="center"><b>'
                 . _BBAFUNC
                 . "</b></td></tr>\n";

            $selitem = $xoopsDB->query('select itid, itemname, maker, priceus, priceru, itembox, comment, catid from ' . $xoopsDB->prefix('pricelist_items') . " where (dealerid = '$iden')");

            while (list($itid, $itemname, $maker, $priceus, $priceru, $itembox, $comment, $catid) = $xoopsDB->fetchRow($selitem)) {
                $selcatname = $xoopsDB->query('select catname from ' . $xoopsDB->prefix('pricelist_categories') . " where caid=$catid");

                [$catname] = $xoopsDB->fetchRow($selcatname);

                echo "<tr><td valign=\"top\">$itemname</td>"
                     . "<td valign=\"top\">$maker</td>"
                     . "<td valign=\"top\">$catname</td>"
                     . "<td valign=\"top\" align=\"right\">$priceus</td>"
                     . "<td valign=\"top\" align=\"right\">$priceru</td>"
                     . "<td valign=\"top\">$itembox</td>\n"
                     . "<td valign=\"top\">$comment</td>\n";

                echo "<td align=\"center\"><a href=\"index.php?op=doedititem&amp;itid=$itid\">" . _BBAEDIT . "</a> / <a href=\"index.php?op=dodeleteitem&amp;itid=$itid&amp;ok=0\">" . _BBADELETE . '</a></td></tr>';
            }

            echo "</table></center>\n";
        }
    }

    CloseTable();
}

function DoDeleteItem($itid, $ok)
{
    global $xoopsDB;

    if (0 == $ok) {
        title('' . _ADMINADSDITEM . '');

        OpenTable();

        $selitem = $xoopsDB->query('select itemname from ' . $xoopsDB->prefix('pricelist_items') . " where itid=$itid");

        [$itemname] = $xoopsDB->fetchRow($selitem);

        echo '<center><b>' . _AUTHORDELSURE . " $itemname?</b><br><br>" . "[ <a href=\"index.php?op=dodeleteitem&amp;itid=$itid&amp;ok=1\">" . _YES . '</a> ] &nbsp;&nbsp; [ <a href="index.php?op=adminads">' . _NO . "</a> ]</center>\n";

        CloseTable();
    }

    if (1 == $ok) {
        $xoopsDB->queryF('delete from ' . $xoopsDB->prefix('pricelist_items') . " where itid='$itid'");

        redirect_header('index.php', 1, _UPDATED);
    }
}

function DoEditItem($itid)
{
    global $xoopsDB;

    title('' . _ADMINADSEITEM . '');

    OpenTable();

    $idpointer = $id;

    $result = $xoopsDB->query('select itemname, maker, priceus, priceru, itembox, comment, dealerid, catid from ' . $xoopsDB->prefix('pricelist_items') . " where itid='$itid'");

    [$itemname, $maker, $priceus, $priceru, $itembox, $comment, $dealerid, $catid] = $xoopsDB->fetchRow($result);

    echo "<center><form action=\"index.php\" method=\"post\">\n"
         . "<table border=\"0\">\n"
         . '<tr><td>'
         . _NAME
         . ": </td><td><input type=\"text\" name=\"itemname\" value=\"$itemname\" size=\"28\"></td></tr>\n"
         . '<tr><td>'
         . _MAKER
         . ": </td><td><input type=\"text\" name=\"maker\" value=\"$maker\" size=\"28\"></td></tr>\n"
         . '<tr><td>'
         . _CATEGORY
         . ": </td><td><select name=\"catid\">\n"
         . '<option>'
         . _ASELECTCATEGORY
         . "...</option>\n";

    $selcat = $xoopsDB->query('select caid, catname from ' . $xoopsDB->prefix('pricelist_categories') . ' order by catname');

    while (list($caid, $catname) = $xoopsDB->fetchRow($selcat)) {
        if ($caid == $catid) {
            $sel = 'selected';
        } else {
            $sel = '';
        }

        echo "<option name=\"catid\" value=\"$caid\" $sel>$catname</option>";
    }

    echo "</select></td></tr>\n" . '<tr><td>' . _DEALER . ": </td><td><select name=\"dealerid\">\n" . '<option>' . _SELDEALER . "</option>\n";

    $selcat = $xoopsDB->query('select deid, dealername from ' . $xoopsDB->prefix('pricelist_dealers') . ' order by dealername');

    while (list($deid, $dealername) = $xoopsDB->fetchRow($selcat)) {
        if ($deid == $dealerid) {
            $sel = 'selected';
        } else {
            $sel = '';
        }

        echo "<option name=\"dealerid\" value=\"$deid\" $sel>$dealername</option>";
    }

    echo "</select></td></tr>\n"
         . '<tr><td>'
         . _PRICEUS
         . ": </td><td><input type=\"text\" size=\"18\" name=\"priceus\" value=\"$priceus\"></td></tr>\n"
         . '<tr><td>'
         . _PRICERU
         . ": </td><td><input type=\"text\" size=\"18\" name=\"priceru\" value=\"$priceru\"></td></tr>\n"
         . '<tr><td>'
         . _ITEMBOX
         . ": </td><td><input type=\"text\" size=\"18\" name=\"itembox\" value=\"$itembox\"></td></tr>\n"
         . '<tr><td>'
         . _COMMENT
         . ": </td><td><input type=\"text\" size=\"18\" name=\"comment\" value=\"$comment\"></td></tr>\n"
         . "<tr><td colspan=\"2\" align=\"right\"><input type=\"hidden\" name=\"itid\" value=\"$itid\"><input type=\"hidden\" name=\"op\" value=\"dbedititem\">\n"
         . '<input type="submit" value="'
         . _SUBMIT
         . "\"></form></td></tr>\n"
         . '</table></center>';

    CloseTable();
}

function DbAddItem($itemname, $maker, $priceus, $priceru, $itembox, $comment, $dealerid, $catid)
{
    global $xoopsDB;

    $result = $xoopsDB->query('insert into ' . $xoopsDB->prefix('pricelist_items') . " values (NULL, '$itemname', '$maker', '$priceus', '$priceru', '$itembox', '$comment', '$dealerid', '$catid')");

    if (!$result) {
        echo "Error! Cannot Update!\n";

        exit();
    }

    redirect_header('index.php', 1, _UPDATED);
}

function DbEditItem($itid, $itemname, $maker, $priceus, $priceru, $itembox, $comment, $dealerid, $catid)
{
    global $xoopsDB;

    $result = $xoopsDB->query('update ' . $xoopsDB->prefix('pricelist_items') . " set itemname='$itemname', maker='$maker', priceus='$priceus', priceru='$priceru', itembox='$itembox', comment='$comment', dealerid='$dealerid', catid='$catid' where itid=$itid");

    if (!$result) {
        echo "Error! Cannot Update!\n";

        exit();
    }

    redirect_header('index.php', 1, _UPDATED);
}

function traite_nom_fichier($nom)
{
    $max_caracteres = 128;

    $nom = stripslashes($nom);

    $nom = str_replace("'", '', $nom);

    $nom = str_replace('"', '', $nom);

    $nom = str_replace('"', '', $nom);

    $nom = str_replace('&', '', $nom);

    $nom = str_replace(',', '', $nom);

    $nom = str_replace(';', '', $nom);

    $nom = str_replace('/', '', $nom);

    $nom = str_replace('\\', '', $nom);

    $nom = str_replace('`', '', $nom);

    $nom = str_replace('<', '', $nom);

    $nom = str_replace('>', '', $nom);

    $nom = str_replace(' ', '_', $nom);

    $nom = str_replace(':', '', $nom);

    $nom = str_replace('*', '', $nom);

    $nom = str_replace('|', '', $nom);

    $nom = str_replace('?', '', $nom);

    $nom = str_replace('é', 'e', $nom);

    $nom = str_replace('è', 'e', $nom);

    $nom = str_replace('ç', 'c', $nom);

    $nom = str_replace('@', '', $nom);

    $nom = str_replace('â', 'a', $nom);

    $nom = str_replace('ê', 'e', $nom);

    $nom = str_replace('î', 'i', $nom);

    $nom = str_replace('ô', 'o', $nom);

    $nom = str_replace('û', 'u', $nom);

    $nom = str_replace('ù', 'u', $nom);

    $nom = str_replace('à', 'a', $nom);

    $nom = str_replace('!', '', $nom);

    $nom = str_replace('§', '', $nom);

    $nom = str_replace('+', '', $nom);

    $nom = str_replace('^', '', $nom);

    $nom = str_replace('(', '', $nom);

    $nom = str_replace(')', '', $nom);

    $nom = str_replace('#', '', $nom);

    $nom = str_replace('=', '', $nom);

    $nom = str_replace('$', '', $nom);

    $nom = str_replace('%', '', $nom);

    $nom = str_replace('ä', 'ae', $nom);

    $nom = str_replace('Ä', 'Ae', $nom);

    $nom = str_replace('ö', 'oe', $nom);

    $nom = str_replace('Ö', 'Oe', $nom);

    $nom = str_replace('ü', 'ue', $nom);

    $nom = str_replace('Ü', 'Ue', $nom);

    $nom = str_replace('ß', 'ss', $nom);

    if (mb_strlen($nom) > $max_caracteres) {
        $ext = mb_substr($nom, (mb_strrpos($nom, '.') + 1));

        $nom = mb_substr($nom, 0, $max_caracteres - 4);

        $nom .= '.' . $ext;
    }

    return $nom;
}

function csvimport()
{
    global $xoopsDB, $max_size;

    title('' . _ADMINADSCSV . '');

    OpenTable();

    echo '<center><form enctype="multipart/form-data" method="post" action="index.php">' . '<b>' . _CSVADDFILE . '</b><br><br>' . '' . _DEALER . ": <select name=\"dealerid\">\n" . '<option name="dealerid" value="-1" selected>' . _SELDEALER . "</option>\n";

    $seldeal = $xoopsDB->query('select deid, dealername from ' . $xoopsDB->prefix('pricelist_dealers') . ' order by dealername');

    while (list($deid, $dealername) = $xoopsDB->fetchRow($seldeal)) {
        echo "<option name=\"dealerid\" value=\"$deid\">$dealername</option>";
    }

    echo "</select><br><br>\n";

    echo ''
         . _DEALERACTION
         . ": <select name=\"dealadd\">\n"
         . '<option name="dealadd" value="0" selected>'
         . _DEALERREWITEM
         . "</option>\n"
         . '<option name="dealadd" value="1">'
         . _DEALERADDITEM
         . "</option>\n"
         . '</select><br><br>'
         . ''
         . _CATEGORY
         . ": <select name=\"catid\">\n"
         . '<option value="-1" selected>'
         . _ASELECTCATEGORY
         . "...</option>\n";

    $selcat = $xoopsDB->query('select caid, catname from ' . $xoopsDB->prefix('pricelist_categories') . ' order by catname');

    while (list($caid, $catname) = $xoopsDB->fetchRow($selcat)) {
        echo "<option name=\"catid\" value=\"$caid\">$catname</option>";
    }

    echo "</select><br><br>\n"
         . '<input type="hidden" name="op" value="docsvimport">'
         . "<INPUT TYPE=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$max_size\">"
         . ''
         . _FILENAME
         . ':<input type="file" name="userfile" size="14"><br><br>'
         . '<input type="submit" value="'
         . _ADDCSV
         . '">'
         . '</form></center>';

    CloseTable();
}

function docsvimport($userfile, $userfile_name, $userfile_size, $dealerid, $catid, $dealadd)
{
    global $xoopsDB, $xoopsConfig, $max_size, $max_time;

    if (-1 == $dealerid) {
        redirect_header('index.php', 1, _SELDEALER);

        exit;
    }

    $destination = XOOPS_ROOT_PATH . '/modules/pricelist/cache';

    if ((0 == $userfile_size) || ($userfile_size > $max_size)) {
        $ok = 0;
    }

    if ('none' == $userfile) {
        $ok = 0;
    }

    if ('none' != $userfile && 0 != $userfile_size) {
        $userfile_name = traite_nom_fichier($userfile_name);

        $patch = "$destination/$userfile_name";

        if (file_exists((string)$patch)) {
            unlink((string)$patch);
        }

        if (!@move_uploaded_file($userfile, (string)$patch)) {
            $ok = 0;
        } else {
            @chmod((string)$patch, 0777);

            $ok = 1;
        }
    }

    if (1 == $ok) {
        $zip = zip_open($patch);

        if ($zip) {
            $name = mb_substr($patch, 0, (mb_strrpos($patch, '.')));

            $cpatch = $name . '.csv';

            $file = fopen($cpatch, 'wb');

            while ($zip_entry = zip_read($zip)) {
                if (zip_entry_open($zip, $zip_entry, 'r')) {
                    $buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

                    fwrite($file, $buf);

                    zip_entry_close($zip_entry);
                }
            }

            fclose($file);

            zip_close($zip);

            if (file_exists((string)$patch)) {
                unlink((string)$patch);
            }

            $patch = $cpatch;
        }

        $fps = fopen($patch, 'rb');

        if (!$fps) {
            redirect_header('index.php', 1, _CSVERRORS);

            exit;
        }

        set_time_limit($max_time);

        if ('0' == $dealadd) {
            $xoopsDB->queryF('DELETE FROM ' . $xoopsDB->prefix('pricelist_items') . " where dealerid=$dealerid");
        }

        OpenTable();

        title('' . _ADMINADSCSV . '');

        echo '<div align="center">';

        $curcatname = '';

        $pat = ';';

        $cnt = 0;

        echo "<table border=\"1\" cellspacing=\"0\" width=\"100%\">\n";

        while (!feof($fps)) {
            $data = fgets($fps, 1024);

            $cnt += 1;

            $tl = mb_substr_count($data, $pat);

            if (('' != $data) && ($tl >= 3) && ($tl <= 6)) {
                $arr = preg_split($pat, $data);

                $itemname = trim($arr[0]);

                $itemname = eregi_replace("[^[:alnum:]|^[À-ß() .,%\/-_]|^[à-ÿ]]", '', $itemname);

                $maker = trim($arr[1]);

                $maker = eregi_replace("[^[:alnum:]|^[À-ß() .,%\/-_]|^[à-ÿ]]", '', $maker);

                $itembox = trim($arr[2]);

                $itembox = eregi_replace("[^[:alnum:]|^[À-ß() .,%\/-_]|^[à-ÿ]]", '', $itembox);

                $priceus = trim($arr[3]);

                $priceus = eregi_replace('[^[0-9.,]]', '', $priceus);

                $priceus = strtr($priceus, ',', '.');

                if (mb_substr_count($priceus, '.') > 1) {
                    $priceus = 0.0;
                }

                $priceus = (float)$priceus;

                if ($tl > 3) {
                    $priceru = trim($arr[4]);

                    $priceru = eregi_replace('[^[0-9.,]', '', $priceru);

                    $priceru = strtr($priceru, ',', '.');

                    if (mb_substr_count($priceru, '.') > 1) {
                        $priceru = 0.0;
                    }

                    $priceru = (float)$priceru;
                } else {
                    $priceru = 0.0;
                }

                if ($tl > 4) {
                    $comment = trim($arr[5]);

                    $comment = eregi_replace("[^[:alnum:]|^[À-ß() .,%\/]|^[à-ÿ]]", '', $comment);
                } else {
                    $comment = '';
                }

                if (('' != $itemname) && (($priceus > 0.0) || ($priceru > 0.0))) {
                    // $selitem = $xoopsDB->query("SELECT itid FROM ".$xoopsDB->prefix("pricelist_items")." WHERE ((UCASE(itemname) LIKE UCASE($itemname)) AND (UCASE(maker) LIKE UCASE($maker)) AND (catid=$catid) AND (dealerid=$dealerid)) LIMIT 1");

                    // list($itid) = $xoopsDB->fetchRow($selitem);

                    // if (!$itid) {

                    $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('pricelist_items') . " values (NULL, '$itemname', '$maker', '$priceus', '$priceru', '$itembox', '$comment', '$dealerid', '$catid')");

                    echo '<tr><td>' . _THEENTRY . " $cnt</td><td>$itemname</td><td>$maker</td><td>$priceus</td><td>$priceru</td><td>$itembox</td><td>$comment</td><td>" . _ADDEDTODB . "</td></tr>\n";

                // } else {
                        // $result = $xoopsDB->query("UPDATE ".$xoopsDB->prefix("pricelist_items")." SET itemname='$itemname', maker='$maker', priceus='$priceus', priceru='$priceru', itembox='$itembox', comment='$comment', dealerid='$dealerid', catid='$catid' where itid=$itid" );
                        // echo "<tr><td>"._THEENTRY." $cnt $itid</td><td>$itemname</td><td>$maker</td><td>$priceus</td><td>$priceru</td><td>$itembox</td><td>$comment</td><td>"._UPDATEINDB."</td></tr>\n";
                        // } //else presen
                } else { //if !=""
                        echo '<tr><td>' . _THEENTRY . " $cnt</td><td>$itemname</td><td>$maker</td><td>$priceus</td><td>$priceru</td><td>$itembox</td><td>$comment</td><td><b>" . _CSVERRORS . "</b></td></tr>\n";
                }
            } else { //if data
                echo '<tr><td>' . _THEENTRY . " $cnt</td><td colspan=\"6\">$data</td><td><b>" . _CSVERRORS . "</b></td></tr>\n";
            }
        } //end while

        fclose($fps);

        echo "</table></div>\n";

        //end else fps

        CloseTable();

        if (file_exists((string)$patch)) {
            unlink((string)$patch);
        }
    } //if ok

    else {
        redirect_header('index.php', 1, _CSVERRORS);

        exit;
    }

    OpenTable();

    echo '<center><b>' . _CONTINUE . '</b><br><br>' . '[ <a href="index.php">' . _YES . "</a> ]</center>\n";

    CloseTable();
}

function configedit()
{
    global $thcolor, $tccolor, $tbcolor, $thfontface, $thfontcolor, $thfontstyle, $tcfontface, $tcfontcolor, $tcfontstyle, $limit, $max_size, $max_time;

    title('' . _ADMINADSCONF . '');

    OpenTable();

    echo '<center><b><u>'
         . _CONFIGURE
         . "</u></b></center><br>\n"
         . "<center><form action=\"index.php\" method=\"post\">\n"
         . "<table border=\"0\">\n"
         // ."<tr><td>"._XTHCOLOR.": </td><td><input type=\"text\" name=\"xthcolor\" value =\"$thcolor\" size=\"7\"></td></tr>\n"
         // ."<tr><td>"._XTCCOLOR.": </td><td><input type=\"text\" name=\"xtccolor\" value =\"$tccolor\" size=\"7\"></td></tr>\n"
         // ."<tr><td>"._XTBCOLOR.": </td><td><input type=\"text\" name=\"xtbcolor\" value =\"$tbcolor\" size=\"7\"></td></tr>\n"
         // ."<tr><td>"._XTHFONTFACE.": </td><td><input type=\"text\" name=\"xthfontface\" value =\"$thfontface\" size=\"28\"></td></tr>\n"
         // ."<tr><td>"._XTHFONTCOLOR.": </td><td><input type=\"text\" name=\"xthfontcolor\" value =\"$thfontcolor\" size=\"7\"></td></tr>\n"
         // ."<tr><td>"._XTHFONTSTYLE.": </td><td><input type=\"text\" name=\"xthfontstyle\" value =\"$thfontstyle\" size=\"28\"></td></tr>\n"
         // ."<tr><td>"._XTCFONTFACE.": </td><td><input type=\"text\" name=\"xtcfontface\" value =\"$tcfontface\" size=\"28\"></td></tr>\n"
         // ."<tr><td>"._XTCFONTCOLOR.": </td><td><input type=\"text\" name=\"xtcfontcolor\" value =\"$tcfontcolor\" size=\"7\"></td></tr>\n"
         // ."<tr><td>"._XTCFONTSTYLE.": </td><td><input type=\"text\" name=\"xtcfontstyle\" value =\"$tcfontstyle\" size=\"28\"></td></tr>\n"
         . '<tr><td>'
         . _XLIMIT
         . ": </td><td><input type=\"text\" name=\"xlimit\" value =\"$limit\" size=\"3\"></td></tr>\n"
         . '<tr><td>'
         . _XMAX_SIZE
         . ": </td><td><input type=\"text\" name=\"xmax_size\" value =\"$max_size\" size=\"7\"></td></tr>\n"
         . '<tr><td>'
         . _XMAX_TIME
         . ": </td><td><input type=\"text\" name=\"xmax_time\" value =\"$max_time\" size=\"3\"></td></tr>\n"
         . '<tr><td colspan="2"><center><input type="hidden" name="op" value="configchange"><input type="submit" value="'
         . _GO
         . "\"></center></td></tr>\n"
         . "</table></center></form>\n";

    CloseTable();
}

function configchange()
{
    global $_POST;

    $xthcolor = $_POST['xthcolor'];

    $xtccolor = $_POST['xtccolor'];

    $xtbcolor = $_POST['xtbcolor'];

    $xthfontface = $_POST['xthfontface'];

    $xthfontcolor = $_POST['xthfontcolor'];

    $xthfontstyle = $_POST['xthfontstyle'];

    $xtcfontface = $_POST['xtcfontface'];

    $xtcfontcolor = $_POST['xtcfontcolor'];

    $xtcfontstyle = $_POST['xtcfontstyle'];

    $xlimit = $_POST['xlimit'];

    $xmax_size = $_POST['xmax_size'];

    $xmax_time = $_POST['xmax_time'];

    $filename = XOOPS_ROOT_PATH . '/modules/pricelist/cache/config.php';

    $file = fopen($filename, 'wb');

    $content = '';

    $content .= "<?php\n";

    $content .= "\n";

    $content .= "############################################################################\n";

    $content .= "# pricelist v1.0  #\n";

    $content .= "# $thcolor // table header colour #\n";

    $content .= "# $tccolor // table content colour #\n";

    $content .= "# $tbcolor // table border colour #\n";

    $content .= "# $thfontface // table header font family (separate by comma) #\n";

    $content .= "# $thfontcolor // table header font colour #\n";

    $content .= "# $thfontstyle // table header font style (separate by space) #\n";

    $content .= "# $tcfontface // table content font family (separate by comma) #\n";

    $content .= "# $tcfontcolor // table content font colour #\n";

    $content .= "# $limit // max items per page limit #\n";

    $content .= "# $max_size // max CSV file size #\n";

    $content .= "# $max_time // max time in secund execution of CSV import #\n";

    $content .= "############################################################################\n";

    $content .= "\n";

    $content .= "\$thcolor = \"$xthcolor\"; \n";

    $content .= "\$tccolor = \"$xtccolor\";\n";

    $content .= "\$tbcolor = \"$xtbcolor\";\n";

    $content .= "\$thfontface = \"$xthfontface\";\n";

    $content .= "\$thfontcolor = \"$xthfontcolor\";\n";

    $content .= "\$thfontstyle = \"$xthfontstyle\";\n";

    $content .= "\$tcfontface = \"$xtcfontface\";\n";

    $content .= "\$tcfontcolor = \"$xtcfontcolor\";\n";

    $content .= "\$tcfontstyle = \"$xtcfontstyle\";\n";

    $content .= "\$limit = $xlimit;\n";

    $content .= "\$max_size = $xmax_size;\n";

    $content .= "\$max_time = $xmax_time;\n";

    $content .= "\n";

    $content .= "?>\n";

    fwrite($file, $content);

    fclose($file);

    redirect_header('index.php', 1, _CONFUPDATED);

    exit;
}

switch ($op) {
    case 'adminads':
        AdminAds();
        break;
    case 'managedealers':
        ManageDealers();
        break;
    case 'managecategories':
        ManageCategories();
        break;
    case 'manageitems':
        ManageItems();
        break;
    case 'dbadddealer':
        DbAddDealer($dealername, $dealercity, $dealermaker, $dealeradd);
        break;
    case 'editdeletedealer':
        EditDeleteDealer($deid);
        break;
    case 'dbeditdeletedealer':
        DbEditDeleteDealer($funcz, $ok, $deid, $dealername, $dealercity, $dealermaker, $dealeradd, $dealeruid);
        break;
    case 'dbdeletedealer':
        DbDeleteDealer($deid);
        break;
    case 'dbaddcat':
        DbAddCat($catname, $parent);
        break;
    case 'editdeletecat':
        EditDeleteCat($caid);
        break;
    case 'dbeditdeletecat':
        DbEditDeleteCat($functz, $ok, $caid, $catname, $parent);
        break;
    case 'dbadditem':
        DbAddItem($itemname, $maker, $priceus, $priceru, $itembox, $comment, $dealerid, $catid);
        break;
    case 'dbdeletecat':
        DbDeleteCat($caid);
        break;
    case 'editdeleteitem':
        EditDeleteItem($listname, $expand, $iden);
        break;
    case 'dodeleteitem':
        DoDeleteItem($itid, $ok);
        break;
    case 'doedititem':
        DoEditItem($itid);
        break;
    case 'dbedititem':
        DbEditItem($itid, $itemname, $maker, $priceus, $priceru, $itembox, $comment, $dealerid, $catid);
        break;
    case 'csvimport':
        csvimport();
        break;
    case 'docsvimport':
        docsvimport($userfile, $userfile_name, $userfile_size, $dealerid, $catid, $dealadd);
        break;
    case 'configedit':
        configedit();
        break;
    case 'configchange':
        configchange();
        break;
    default:
        AdminAds();
        break;
}
include 'admin_footer.php';
