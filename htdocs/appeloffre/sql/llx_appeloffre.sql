-- <one line to give the program's name and a brief idea of what it does.>
-- Copyright (C) <year>  <name of author>
--
-- This program is free software: you can redistribute it and/or modify
-- it under the terms of the GNU General Public License as published by
-- the Free Software Foundation, either version 3 of the License, or
-- (at your option) any later version.
--
-- This program is distributed in the hope that it will be useful,
-- but WITHOUT ANY WARRANTY; without even the implied warranty of
-- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
-- GNU General Public License for more details.
--
-- You should have received a copy of the GNU General Public License
-- along with this program.  If not, see <http://www.gnu.org/licenses/>.
CREATE TABLE IF NOT EXISTS `llx_appeloffre` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ref` varchar(128) NOT NULL,
  `label` varchar(256) NOT NULL,
  `fk_tiers` int(11) NOT NULL,
  `visible` tinyint(4) NOT NULL,
  `description` text NOT NULL,
  `date_sortie` varchar(256) NOT NULL,
  `date_create` varchar(256) NOT NULL,
  `date_butoir` varchar(256) NOT NULL,
  `budget_est` float NOT NULL,
  `secteur_geo` varchar(256) NOT NULL,
  `segmentation` varchar(256) NOT NULL,
  `buy_step` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `Adjudicataire` int(11) NOT NULL,
  `amount_attributed` float NOT NULL,
  `fk_contact` int(11) NOT NULL,
  `owner` int(11) NOT NULL,
  `contacts` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ref` (`ref`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;