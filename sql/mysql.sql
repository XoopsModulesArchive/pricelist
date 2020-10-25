# phpMyAdmin MySQL-Dump
# version 2.2.2
# http://phpwizard.net/phpMyAdmin/
# http://phpmyadmin.sourceforge.net/ (download page)
#
# -------------------------------------------------------- 
CREATE TABLE pricelist_dealers (
    deid        BIGINT       NOT NULL AUTO_INCREMENT,
    dealername  VARCHAR(255) NOT NULL DEFAULT '',
    dealercity  VARCHAR(255) NOT NULL DEFAULT '',
    dealermaker TINYINT(6)   NOT NULL DEFAULT '0',
    dealeradd   TEXT         NOT NULL,
    dealeruid   BIGINT       NOT NULL DEFAULT '0',
    PRIMARY KEY (deid)
)
    ENGINE = ISAM;
CREATE TABLE pricelist_categories (
    caid    BIGINT       NOT NULL AUTO_INCREMENT,
    catname VARCHAR(255) NOT NULL DEFAULT '',
    parent  BIGINT       NOT NULL DEFAULT '-1',
    PRIMARY KEY (caid)
)
    ENGINE = ISAM;
CREATE TABLE pricelist_items (
    itid     BIGINT         NOT NULL AUTO_INCREMENT,
    itemname VARCHAR(255)   NOT NULL DEFAULT '',
    maker    VARCHAR(255)   NOT NULL DEFAULT '',
    priceus  DECIMAL(10, 2) NOT NULL DEFAULT '0.00',
    priceru  DECIMAL(10, 2) NOT NULL DEFAULT '0.00',
    itembox  VARCHAR(255)   NOT NULL DEFAULT '',
    comment  VARCHAR(255)   NOT NULL DEFAULT '',
    dealerid BIGINT         NOT NULL DEFAULT '0',
    catid    BIGINT         NOT NULL DEFAULT '0',
    PRIMARY KEY (itid)
)
    ENGINE = ISAM;
