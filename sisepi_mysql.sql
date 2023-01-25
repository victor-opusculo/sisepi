-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 25-Jan-2023 às 18:03
-- Versão do servidor: 8.0.26
-- versão do PHP: 8.0.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sisepi`
--

DELIMITER $$
--
-- Funções
--
CREATE DEFINER=`root`@`localhost` FUNCTION `convertOldToNewSubscription` (`template` JSON, `cryptoKey` VARCHAR(300), `socialName` VARBINARY(300), `phoneNumber` VARBINARY(300), `birthDate` DATE, `gender` VARBINARY(300), `nationality` VARBINARY(300), `race` VARBINARY(300), `schoolingLevel` VARBINARY(300), `stateUf` VARBINARY(300), `occupation` VARBINARY(300), `accessibilityFeatureNeeded` VARBINARY(300), `agreesWithConsentForm` BOOLEAN) RETURNS VARBINARY(4000) BEGIN
DECLARE outjson JSON;
SET outjson = template;

SET outjson = JSON_SET(outjson, 
      '$.questions[0].value', CAST(AES_DECRYPT(socialName, cryptoKey) AS CHAR),
      '$.questions[1].value', CAST(AES_DECRYPT(phoneNumber, cryptoKey) AS CHAR),
      '$.questions[2].value', birthDate,
      '$.questions[3].value', CAST(AES_DECRYPT(gender, cryptoKey) AS CHAR),
      '$.questions[4].value', CAST(AES_DECRYPT(nationality, cryptoKey) AS CHAR),
      '$.questions[5].value', CAST(AES_DECRYPT(race, cryptoKey) AS CHAR),
      '$.questions[6].value', CAST(AES_DECRYPT(schoolingLevel, cryptoKey) AS CHAR),
      '$.questions[7].value', CAST(AES_DECRYPT(stateUf, cryptoKey) AS CHAR),
      '$.questions[8].value', CAST(AES_DECRYPT(occupation, cryptoKey) AS CHAR),
      '$.questions[9].value', CAST(AES_DECRYPT(accessibilityFeatureNeeded, cryptoKey) AS CHAR),
      '$.terms[0].value', 1, 
      '$.terms[1].value', agreesWithConsentForm
);

RETURN AES_ENCRYPT(outjson, cryptoKey);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `artattachments`
--

CREATE TABLE `artattachments` (
  `id` int UNSIGNED NOT NULL,
  `artPieceId` int UNSIGNED NOT NULL,
  `fileName` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Extraindo dados da tabela `artattachments`
--

INSERT INTO `artattachments` (`id`, `artPieceId`, `fileName`) VALUES
(1, 1, '1.jpg'),
(2, 2, '2.jpg'),
(3, 3, '3.jpg'),
(4, 4, '4.jpg'),
(5, 5, '5.jpg'),
(6, 6, '6.jpg'),
(7, 7, '7.jpg'),
(8, 8, '8.jpg'),
(9, 9, '9.jpg'),
(10, 10, '10.jpg'),
(11, 11, '11.jpg'),
(12, 12, '12.jpg'),
(13, 13, '13.jpg'),
(14, 14, '14.jpg'),
(15, 15, '15.jpg'),
(16, 16, '16.jpg'),
(17, 17, '17.jpg'),
(18, 18, '18.jpg'),
(19, 19, '19.jpg'),
(20, 20, '20.jpg'),
(21, 21, '21.jpg'),
(22, 22, '22.jpg'),
(23, 23, '23.jpg'),
(24, 24, '24.jpg'),
(25, 25, '25.jpg'),
(26, 26, '26.jpg'),
(27, 27, '27.jpg'),
(28, 28, '28.jpg'),
(29, 29, '29.jpg'),
(30, 30, '30.jpg'),
(31, 31, '31.jpg'),
(32, 32, '32.jpg'),
(33, 33, '33.jpg'),
(34, 34, '34.jpg'),
(35, 35, '35.jpg'),
(36, 36, '36.jpg'),
(37, 37, '37.jpg'),
(38, 38, '38.jpg'),
(39, 39, '39.jpg'),
(40, 40, '40.jpg'),
(41, 41, '41.jpg'),
(42, 42, '42.jpg'),
(43, 43, '43.jpg'),
(44, 44, '44.jpg'),
(45, 45, '45.jpg'),
(46, 46, '46.jpg'),
(47, 47, '47.jpg'),
(48, 48, '48.jpg'),
(49, 49, '49.jpg'),
(50, 50, '50.jpg'),
(51, 51, '51.jpg'),
(52, 52, '52.jpg'),
(53, 53, '53.jpg'),
(54, 54, '54.jpg'),
(55, 55, '55.jpg'),
(56, 56, '56.jpg'),
(57, 57, '57.jpg'),
(58, 58, '58.jpg'),
(59, 59, '59.jpg'),
(60, 60, '60.jpg'),
(61, 61, '61.jpg'),
(62, 62, '62.jpg'),
(63, 63, '63.jpg'),
(64, 64, '64.jpg'),
(65, 65, '65.jpg'),
(66, 66, '66.jpg'),
(67, 67, '67.jpg'),
(68, 68, '68.jpg'),
(69, 69, '69.jpg'),
(70, 70, '70.jpg'),
(71, 71, '71.jpg'),
(72, 72, '72.jpg'),
(73, 73, '73.jpg'),
(74, 74, '74.jpg'),
(75, 75, '75.jpg'),
(76, 76, '76.jpg'),
(77, 77, '77.jpg'),
(78, 78, '78.jpg'),
(79, 79, '79.jpg'),
(80, 80, '80.jpg'),
(81, 81, '81.jpg'),
(82, 82, '82.jpg'),
(83, 83, '83.jpg'),
(84, 84, '84.jpg'),
(85, 85, '85.jpg'),
(86, 86, '86.jpg'),
(87, 87, '87.jpg'),
(88, 88, '88.jpg'),
(89, 89, '89.jpg'),
(90, 90, '90.jpg'),
(91, 91, '91.jpg'),
(92, 92, '92.jpg'),
(93, 93, '93.jpg'),
(94, 94, '94.jpg'),
(95, 95, '95.jpg'),
(96, 96, '96.jpg'),
(97, 97, '97.jpg'),
(98, 98, '98.jpg'),
(99, 99, '99.jpg'),
(100, 100, '100.jpg'),
(101, 101, '101.jpg'),
(102, 102, '102.jpg'),
(103, 103, '103.jpg'),
(104, 104, '104.jpg'),
(105, 105, '105.jpg'),
(106, 106, '106.jpg'),
(107, 107, '107.jpg'),
(108, 108, '108.jpg'),
(109, 109, '109.jpg'),
(110, 110, '110.jpg'),
(111, 111, '111.jpg'),
(112, 112, '112.jpg'),
(113, 113, '113.jpg'),
(114, 114, '114.jpg'),
(115, 115, '115.jpg'),
(116, 116, '116.jpg'),
(117, 117, '117.jpg'),
(118, 118, '118.jpg'),
(119, 119, '119.jpg'),
(120, 120, '120.jpg'),
(121, 121, '121.jpg'),
(122, 122, '122.jpg'),
(123, 123, '123.jpg'),
(124, 124, '124.jpg'),
(125, 125, '125.jpg'),
(126, 126, '126.jpg'),
(127, 127, '127.jpg'),
(128, 128, '128.jpg'),
(129, 129, '129.jpg'),
(130, 130, '130.jpg'),
(131, 131, '131.jpg'),
(132, 132, '132.jpg'),
(133, 133, '133.jpg'),
(134, 134, '134.jpg'),
(135, 135, '135.jpg'),
(136, 136, '136.jpg'),
(137, 137, '137.jpg'),
(138, 138, '138.jpg'),
(139, 139, '139.jpg'),
(140, 140, '140.jpg'),
(141, 141, '141.jpg'),
(142, 142, '142.jpg'),
(143, 143, '143.jpg'),
(144, 144, '144.jpg'),
(145, 145, '145.jpg'),
(146, 146, '146.jpg'),
(147, 147, '147.jpg'),
(148, 148, '148.jpg'),
(149, 149, '149.jpg'),
(150, 150, '150.jpg'),
(151, 151, '151.jpg'),
(152, 152, '152.jpg'),
(153, 153, '153.jpg'),
(154, 154, '154.jpg'),
(155, 155, '155.jpg'),
(156, 156, '156.jpg'),
(157, 157, '157.jpg'),
(158, 158, '158.jpg'),
(159, 159, '159.jpg'),
(160, 160, '160.jpg'),
(161, 161, '161.jpg'),
(162, 162, '162.jpg'),
(163, 163, '163.jpg'),
(164, 164, '164.jpg'),
(165, 165, '165.jpg'),
(166, 166, '166.jpg'),
(167, 167, '167.jpg'),
(168, 168, '168.jpg'),
(169, 169, '169.jpg'),
(170, 170, '170.jpg'),
(171, 171, '171.jpg'),
(172, 172, '172.jpg'),
(173, 173, '173.jpg'),
(174, 174, '174.jpg'),
(175, 175, '175.jpg'),
(176, 176, '176.jpg'),
(177, 177, '177.jpg'),
(178, 178, '178.jpg'),
(179, 179, '179.jpg'),
(180, 180, '180.jpg'),
(181, 181, '181.jpg'),
(182, 182, '182.jpg'),
(183, 183, '183.jpg'),
(184, 184, '184.jpg'),
(185, 185, '185.jpg'),
(186, 186, '186.jpg'),
(187, 187, '187.jpg'),
(188, 188, '188.jpg'),
(189, 189, '189.jpg'),
(190, 190, '190.jpg'),
(191, 191, '191.jpg'),
(192, 192, '192.jpg'),
(193, 193, '193.jpg'),
(194, 194, '194.jpg'),
(195, 195, '195.jpg'),
(196, 196, '196.jpg'),
(197, 197, '197.jpg'),
(198, 198, '198.jpg'),
(199, 199, '199.jpg'),
(200, 200, '200.jpg'),
(201, 201, '201.jpg'),
(202, 202, '202.jpg'),
(203, 203, '203.jpg'),
(204, 204, '204.jpg'),
(205, 205, '205.jpg'),
(206, 206, '206.jpg'),
(207, 207, '207.jpg'),
(208, 208, '208.jpg'),
(209, 209, '209.jpg'),
(210, 210, '210.jpg'),
(211, 211, '211.jpg'),
(212, 212, '212.jpg'),
(213, 213, '213.jpg'),
(214, 214, '214.jpg'),
(215, 215, '215.jpg'),
(216, 216, '216.jpg'),
(217, 217, '217.jpg'),
(218, 218, '218.jpg'),
(219, 219, '219.jpg'),
(220, 220, '220.jpg'),
(221, 221, '221.jpg'),
(222, 222, '222.jpg'),
(223, 223, '223.jpg'),
(224, 224, '224.jpg'),
(225, 225, '225.jpg'),
(226, 226, '226.jpg'),
(227, 227, '227.jpg'),
(228, 228, '228.jpg'),
(229, 229, '229.jpg'),
(230, 230, '230.jpg'),
(231, 231, '231.jpg'),
(232, 232, '232.jpg'),
(233, 233, '233.jpg'),
(234, 234, '234.jpg'),
(235, 235, '235.jpg'),
(236, 236, '236.jpg'),
(237, 237, '237.jpg'),
(238, 238, '238.jpg'),
(239, 239, '239.jpg'),
(240, 240, '240.jpg'),
(241, 241, '241.jpg'),
(242, 242, '242.jpg'),
(243, 243, '243.jpg'),
(244, 244, '244.jpg'),
(245, 245, '245.jpg'),
(246, 246, '246.jpg'),
(247, 247, '247.jpg'),
(248, 248, '248.jpg'),
(249, 249, '249.jpg'),
(250, 250, '250.jpg'),
(251, 251, '251.jpg'),
(252, 252, '252.jpg'),
(253, 253, '253.jpg'),
(254, 254, '254.jpg'),
(255, 255, '255.jpg'),
(256, 256, '256.jpg'),
(257, 257, '257.jpg'),
(258, 258, '258.jpg'),
(259, 259, '259.jpg'),
(260, 260, '260.jpg'),
(261, 261, '261.jpg'),
(262, 262, '262.jpg'),
(263, 263, '263.jpg'),
(264, 264, '264.jpg'),
(265, 265, '265.jpg'),
(266, 266, '266.jpg'),
(267, 267, '267.jpg'),
(268, 268, '268.jpg'),
(269, 269, '269.jpg'),
(270, 270, '270.jpg'),
(271, 271, '271.jpg'),
(272, 272, '272.jpg'),
(273, 273, '273.jpg'),
(274, 274, '274.jpg'),
(275, 275, '275.jpg'),
(276, 276, '276.jpg'),
(277, 277, '277.jpg'),
(278, 278, '278.jpg'),
(279, 279, '279.jpg'),
(280, 280, '280.jpg'),
(281, 281, '281.jpg'),
(282, 282, '282.jpg'),
(283, 283, '283.jpg'),
(284, 284, '284.jpg'),
(285, 285, '285.jpg'),
(286, 286, '286.jpg'),
(287, 287, '287.jpg'),
(288, 288, '288.jpg'),
(289, 289, '289.jpg'),
(290, 290, '290.jpg'),
(291, 291, '291.jpg'),
(292, 292, '292.jpg'),
(293, 293, '293.jpg'),
(294, 294, '294.jpg'),
(295, 295, '295.jpg'),
(296, 296, '296.jpg'),
(297, 297, '297.jpg'),
(298, 298, '298.jpg'),
(299, 299, '299.jpg'),
(300, 300, '300.jpg'),
(301, 301, '301.jpg'),
(302, 302, '302.jpg'),
(303, 303, '303.jpg'),
(304, 304, '304.jpg'),
(305, 305, '305.jpg'),
(306, 306, '306.jpg'),
(307, 307, '307.jpg'),
(308, 308, '308.jpg'),
(309, 309, '309.jpg'),
(310, 310, '310.jpg'),
(311, 311, '311.jpg'),
(312, 312, '312.jpg'),
(313, 313, '313.jpg'),
(314, 314, '314.jpg'),
(315, 315, '315.jpg'),
(316, 316, '316.jpg'),
(317, 317, '317.jpg'),
(318, 318, '318.jpg'),
(319, 319, '319.jpg'),
(320, 320, '320.jpg'),
(321, 321, '321.jpg'),
(322, 322, '322.jpg'),
(323, 323, '323.jpg'),
(324, 324, '324.jpg'),
(325, 325, '325.jpg'),
(326, 326, '326.jpg'),
(327, 327, '327.jpg'),
(328, 328, '328.jpg'),
(329, 329, '329.jpg'),
(330, 330, '330.jpg'),
(331, 331, '331.jpg'),
(332, 332, '332.jpg'),
(333, 333, '333.jpg'),
(334, 334, '334.jpg'),
(335, 335, '335.jpg'),
(336, 336, '336.jpg'),
(337, 337, '337.jpg'),
(338, 338, '338.jpg'),
(339, 339, '339.jpg'),
(340, 340, '340.jpg'),
(341, 341, '341.jpg'),
(342, 342, '342.jpg'),
(343, 343, '343.jpg'),
(344, 344, '344.jpg'),
(345, 345, '345.jpg'),
(346, 346, '346.jpg'),
(347, 347, '347.jpg'),
(348, 348, '348.jpg'),
(349, 349, '349.jpg');

-- --------------------------------------------------------

--
-- Estrutura da tabela `artpieces`
--

CREATE TABLE `artpieces` (
  `id` int UNSIGNED NOT NULL,
  `CMI_propertyNumber` int DEFAULT NULL,
  `name` varchar(120) NOT NULL,
  `artist` varchar(120) DEFAULT NULL,
  `technique` varchar(120) DEFAULT NULL,
  `year` int DEFAULT NULL,
  `size` varchar(80) DEFAULT NULL,
  `donor` varchar(120) DEFAULT NULL,
  `value` decimal(13,2) DEFAULT NULL,
  `location` varchar(120) DEFAULT NULL,
  `description` text,
  `mainImageAttachmentFileName` varchar(120) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='Tabela das obras do museu de arte';

--
-- Extraindo dados da tabela `artpieces`
--

INSERT INTO `artpieces` (`id`, `CMI_propertyNumber`, `name`, `artist`, `technique`, `year`, `size`, `donor`, `value`, `location`, `description`, `mainImageAttachmentFileName`) VALUES
(1, NULL, 'Explosão', 'Leontina Maria Franco', 'óleo sobre tela', NULL, '60,5 x 90 ', 'Emanuel von Lauenstein Massarani', '10.00', '', '', '1.jpg'),
(2, NULL, 'Ilustracionismo 1', 'Vera Pavanelli', 'obra encáustica', NULL, '20 x 100 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '2.jpg'),
(3, NULL, 'Ilustracionismo 2', 'Vera Pavanelli', 'obra encáustica', NULL, '20 x 100 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '3.jpg'),
(4, NULL, 'Espiral', 'Sônia Delaunay (1875 – 1979)', 'gravura a cores sobre papel ', NULL, '71,5 x 91,5 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '4.jpg'),
(5, NULL, 'Tatu dorminhoco', 'Ruth Bess', 'gravura sobre papel ', 1974, '57,5 x 76,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '5.jpg'),
(6, NULL, 'Paisagem', 'Amanda Galvão Delboni', 'óleo sobre papel', NULL, '40 x 32', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '6.jpg'),
(7, NULL, 'Confluência Azul ', 'Cleu Hilarius', 'óleo sobre tela', 1979, '73 x 73', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '7.jpg'),
(8, NULL, 'No espaço', 'Arthur Luiz Piza ', 'gravura sobre papel', 1928, '66 x 51 ', 'Emanuel von Lauenstein Massarani', '10.00', '', '', '8.jpg'),
(9, NULL, 'Nós', 'Rosemunde Krebeck', 'gravura sobre papel', NULL, '65,5 x 50,5 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '9.jpg'),
(10, NULL, 'Origem de Itapevi', 'Raphael Piglinelli Stefanini', 'cartaz com fotografia', 2015, '70 x 53', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '10.jpg'),
(11, NULL, 'Estação de Itapevi', 'Raphael Piglinelli Stefanini', 'cartaz com fotografia', 2015, '70 x 53', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '11.jpg'),
(12, NULL, 'Igrejas e Templos de Itapevi', 'Raphael Piglinelli Stefanini', 'cartaz com fotografia', 2015, '70 x 53', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '12.jpg'),
(13, NULL, 'Estação de Itapevi', 'Ita Shopping – Itapevi 50 anos', 'fotografia', 1875, '94,5 x 65', 'Câmara Municipal de itapevi', '10.00', NULL, NULL, '13.jpg'),
(14, NULL, 'Vista aérea de Itapevi', 'Ita Shopping – Itapevi 50 anos', 'fotografia', 1939, '94,5 x 65', 'Câmara Municipal de itapevi', '10.00', NULL, NULL, '14.jpg'),
(15, NULL, 'Emancipadores', 'Ita Shopping – Itapevi 50 anos', 'fotografia', NULL, '94,5 x 65', 'Câmara Municipal de itapevi', '10.00', NULL, NULL, '15.jpg'),
(16, NULL, 'Rua Joaquim Nunes', 'Ita Shopping – Itapevi 50 anos', 'fotografia', 1951, '94,5 x 65', 'Câmara Municipal de itapevi', '10.00', NULL, NULL, '16.jpg'),
(17, NULL, 'Todos pela emancipação', 'Ita Shopping – Itapevi 50 anos', 'fotografia', NULL, '94,5 x 65', 'Câmara Municipal de itapevi', '10.00', NULL, NULL, '17.jpg'),
(18, NULL, 'Emancipadores na Rádio Record', 'Ita Shopping – Itapevi 50 anos', 'fotografia', NULL, '94,5 x 65', 'Câmara Municipal de itapevi', '10.00', NULL, NULL, '18.jpg'),
(19, NULL, 'Rubens Caramez e vereadores diplomados', 'Ita Shopping – Itapevi 50 anos', 'fotografia', 1959, '94,5 x 65', 'Câmara Municipal de itapevi', '10.00', NULL, NULL, '19.jpg'),
(20, NULL, 'Advogando', 'Susana Meyer', 'óleo sobre tela', NULL, '100 x 100', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '20.jpg'),
(21, NULL, 'Manufatura', 'Johanna Baudou', 'óleo sobre tela', 2014, '100 x 100', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '21.jpg'),
(22, NULL, 'O Coletor de lixo', 'G. E. P.', 'óleo sobre tela', NULL, '100 x 100', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '22.jpg'),
(23, NULL, 'A escultura satisfeita', 'Malé', 'óleo sobre tela', 1990, '100 x 100', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '23.jpg'),
(24, NULL, 'Dança indígena', 'Heinz Budweg', 'óleo sobre tela', 2014, '100 x 100', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '24.jpg'),
(25, NULL, 'Cozinheira baiana', 'Raphael Piglinelli Stefanini', 'óleo sobre tela', 2018, '100 x 100', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '25.jpg'),
(26, NULL, 'Plantadores de cana', 'Eufrázia Fregonezi', 'óleo sobre tela', NULL, '100 x 100', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '26.jpg'),
(27, NULL, 'Vaso de Dálias', 'Antônio C. Moraes (1940 – 1999)', 'óleo sobre tela', NULL, '76,5 x 75,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '27.jpg'),
(28, NULL, 'Metal com laranjas', 'Mary Ricci', 'óleo sobre tela', NULL, '81,5 x 101,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '28.jpg'),
(29, NULL, 'Flores da Primavera', 'Guiomar de Souza (1918 – 2007)', 'óleo sobre tela', NULL, '53 x 63', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '29.jpg'),
(30, NULL, 'Brasópolis', 'P. Alberti', 'óleo sobre tela', NULL, '155 x 101', 'Roberto Eduardo Lamari', '10.00', NULL, NULL, '30.jpg'),
(31, NULL, 'Frutas', 'Michel Gorayeb', 'óleo sobre tela', NULL, '45,5 x 51 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '31.jpg'),
(32, NULL, 'Variation noir violet 1', 'Marcos Francini', 'gravura sobre papel', NULL, '52 x 64', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '32.jpg'),
(33, NULL, 'Variation noir violet 3', 'Marcos Francini', 'gravura sobre papel', NULL, '52 x 64', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '33.jpg'),
(34, NULL, 'Caminho', 'Jorge Bussab', 'gravura sobre papel', NULL, '55,5 x 67', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '34.jpg'),
(35, NULL, 'Protomogravura V', 'Domênico Calabrone', 'gravura sobre papel', 1961, '41,5 x 57', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '35.jpg'),
(36, NULL, 'Protomogravura VI', 'Domênico Calabrone', 'gravura sobre papel', 1961, '41,5 x 57', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '36.jpg'),
(37, NULL, 'A passagem', 'Antônio Peticov', 'serigrafia', NULL, ' 68 x 65', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '37.jpg'),
(38, NULL, 'O menino no inverno', 'Adolfo Eloy', 'desenho a nanquim', NULL, '30,5 x 43', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '38.jpg'),
(39, NULL, 'No aguardo', 'Carlos Scliar (1920 – 2000)', 'gravura sobre papel', NULL, '41 x 47', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '39.jpg'),
(40, NULL, 'Espectro 16/75', 'H. Martinez', 'gravura sobre papel', NULL, '71 x 96', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '40.jpg'),
(41, NULL, 'Lançador de Disco', 'Valéria Yasbeck', 'óleo sobre cartão', NULL, '65 x 65 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '41.jpg'),
(42, NULL, 'Repouso na rede', 'Dongrar', 'óleo sobre tela', 1969, ' 98 x 78', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '42.jpg'),
(43, NULL, 'Handbol', 'Ricardo Amadeo ', 'óleo sobre cartão', NULL, '65 x 65', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '43.jpg'),
(44, NULL, 'Intenãs de Licen', 'Gilberto Macrina', 'óleo sobre tela', NULL, '50 x 65', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '44.jpg'),
(45, NULL, 'Visão da Pinacoteca', 'Sueli Alvarenga', 'óleo sobre tela', NULL, '100 x 80', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '45.jpg'),
(46, NULL, 'Edificações', 'Sylvia Lira', 'óleo sobre tela', NULL, '60 x 60', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '46.jpg'),
(47, NULL, 'Figura misteriosa', 'Júlio Vieira', 'desenho a nanquim', NULL, '42 x 56', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '47.jpg'),
(48, NULL, 'O encontro', 'Júlio Vieira', 'desenho a nanquim', NULL, '42 x 56', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '48.jpg'),
(49, NULL, 'Napoleão e seus mensageiros', 'William Tode', 'gravura sobre papel', NULL, '72 x 92', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '49.jpg'),
(50, NULL, 'Paisagem na França', 'William Tode', 'gravura sobre papel', NULL, '72 x 88', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '50.jpg'),
(51, NULL, 'Paisagem carioca', 'Heinz Budweg', 'óleo sobre tela', NULL, '103 x 93', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '51.jpg'),
(52, NULL, 'O trote', 'Álvaro Siza', 'gravura sobre papel', NULL, '54 x 72 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '52.jpg'),
(53, NULL, 'Empinando', 'Álvaro Siza', 'gravura sobre papel', NULL, '54 x 72 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '53.jpg'),
(54, NULL, 'Cais do porto', 'R. Morvan', 'gravura original sobre papel', 1875, '63 x 83', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '54.jpg'),
(55, NULL, 'Paisagem 1 (P/E)', 'Arturo Cármassi (1925 – 2015)', 'gravura a cores no cartão', NULL, '62 x 81', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '55.jpg'),
(56, NULL, 'Paisagem 2 (P/E)', 'Arturo Cármassi (1925 – 2015)', 'gravura a cores no cartão', NULL, '62 x 81', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '56.jpg'),
(57, NULL, 'Le menestrel', 'Alceu Polvora (1952 – 1989)', 'gravura sobre papel', NULL, '57 x 77', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '57.jpg'),
(58, NULL, 'Le Voyage', 'Alceu Polvora (1952 – 1989)', 'gravura sobre papel', NULL, '57 x 77', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '58.jpg'),
(59, NULL, 'La nui des enchantés', 'Alceu Polvora (1952 – 1989)', 'gravura sobre papel', NULL, '57 x 77', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '59.jpg'),
(60, NULL, 'Universo mágico', 'Leila Bertelli', 'óleo sobre tela', NULL, '53 x 83', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '60.jpg'),
(61, NULL, 'Acaso, por acaso, Ocaso ', 'Silvia Synder', 'olestrata sobre tela', NULL, '104 x 64', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '61.jpg'),
(62, NULL, 'O bloqueio do Ser Humano (10/10)', 'Antônio Carlos Maciel ', 'gravura em metal sobre papel', 1972, '65 x 80,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '62.jpg'),
(63, NULL, 'Observando (5/30)', 'Dayse Grumyel', 'gravura sobre papel', NULL, '51 x 66 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '63.jpg'),
(64, NULL, 'O casamento de Rebeca e Isaac', 'R. Petroff', 'gravura a cores sobre papel', 1970, '64,5 x 91', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '64.jpg'),
(65, NULL, 'Les murs de la ville', 'Mariza Dias Costa', 'gravura sobre papel', 1971, '42,5 x 70,5  ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '65.jpg'),
(66, NULL, 'Deus e o Diabo na terra do sol', 'Domenico Calabrone', 'gravura sobre papel', 1963, '49 x 34', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '66.jpg'),
(67, NULL, 'Astrea', 'Jorge Bussab', 'gravura sobre papel', 1978, '55,5 x 70', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '67.jpg'),
(68, NULL, 'Momento', 'Jorge Bussab', 'gravura sobre papel', 1978, '55,5 x 70', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '68.jpg'),
(69, NULL, 'O arquiteto imaginário', 'Thomaz Ianelli (1932 – 2010)', 'gravura sobre cartão', NULL, '40 x 50', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '69.jpg'),
(70, NULL, 'Paisagem imaginária', 'Marilda Silva', 'gravura sobre papel', 1975, '52 x 67', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '70.jpg'),
(71, NULL, 'Interpretação da natureza 1', 'Roberto Camargo Figueiredo', 'fotomontagem digital a cores', NULL, '76 x 77 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '71.jpg'),
(72, NULL, 'Interpretação da natureza 2', 'Roberto Camargo Figueiredo', 'fotomontagem digital a cores', NULL, '76 x 77 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '72.jpg'),
(73, NULL, 'Percorrendo a vila', 'Jorge Bussab', 'óleo sobre tela', NULL, '100 x 100 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '73.jpg'),
(74, NULL, 'Contemplando flores', 'Cristina Fagundes', 'óleo sobre tela', NULL, '100 x 100 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '74.jpg'),
(75, NULL, 'Dilemas da virgindade (P/E)', 'Boris', 'gravura sobre papel', 1969, '37,5 x 45', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '75.jpg'),
(76, NULL, 'O Guitarrista', 'Rômulo Pereira', 'óleo sobre tela', NULL, '100 x 100 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '76.jpg'),
(77, NULL, 'Colheita em Taubaté', 'Carlos Herglotz', 'óleo sobre tela', NULL, '100 x 100 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '77.jpg'),
(78, NULL, 'Homenagem a Aleijadinho  ', 'Alfredo del Santo', 'óleo sobre tela', NULL, '70 x 100', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '78.jpg'),
(79, NULL, 'Garimpo 1', 'Iwao Nakagima', 'mosaico', NULL, '26 x 49', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '79.jpg'),
(80, NULL, 'A fantasia', 'João Ponce Paz', 'mosaico', NULL, '43,5 x 54', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '80.jpg'),
(81, NULL, 'Garimpo 2', 'Iwao Nakagima', 'mosaico', NULL, '26 x 49', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '81.jpg'),
(82, NULL, 'Expressionismo abstrato (19/100)', 'Fayga Ostrower ', 'gravura sobre papel', 1974, '42 x 56', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '82.jpg'),
(83, NULL, 'Enaltecendo os filhos', 'J. C. Canato', 'policromática', NULL, '36 x 44 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '83.jpg'),
(84, NULL, 'Primeiros passos', 'J. C. Canato', 'policromática', NULL, '43 x 40 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '84.jpg'),
(85, NULL, 'Cavalgando', 'J. C. Canato', 'policromática', NULL, '39 x 52 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '85.jpg'),
(86, NULL, 'Amor de perdição', 'IDNACH – Israel Dias Machado', 'desenho aquarelado', 2004, '40 x 54', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '86.jpg'),
(87, NULL, 'Admirando a rosa', 'IDNACH – Israel Dias Machado', 'desenho aquarelado', 2004, '40 x 54', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '87.jpg'),
(88, NULL, 'O piloto vigarista', 'Marcelo Gomes', 'óleo sobre tela', NULL, '100 x 100 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '88.jpg'),
(89, NULL, 'Destroços 1 - prancha original', 'Maria Bonomi', 'gravura em madeira', NULL, '56,5 x 59,5 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '89.jpg'),
(90, NULL, 'Destroços 2 - prancha original', 'Maria Bonomi', 'gravura em madeira', NULL, '50 x 60,5 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '90.jpg'),
(91, NULL, 'Destino', 'Jorge Bussab', 'óleo sobre tela', NULL, '94 x 114', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '91.jpg'),
(92, NULL, 'Circo dos 4 irmãos ', 'Jorge Bussab', 'óleo sobre tela', NULL, '110 x 130 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '92.jpg'),
(93, NULL, 'Formação', 'Jorge Bussab', 'óleo sobre tela', NULL, '88 x 108', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '93.jpg'),
(94, NULL, 'Um grito de revolta', 'Giuseppe Ranzini', 'óleo sobre tela', NULL, '50 x 70 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '94.jpg'),
(95, NULL, 'Vaso de flor', 'Ida Massarani', 'óleo sobre tela', NULL, '52 x 62', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '95.jpg'),
(96, NULL, 'Maço de flores', 'Thomaz Gomide', 'óleo sobre tela', 2007, '27,5 x 33,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '96.jpg'),
(97, NULL, 'No jardim das margaridas', 'Maria Auxiliadora da Silva', 'óleo sobre tela', 1957, '31,5 x 45', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '97.jpg'),
(98, NULL, 'No café da manhã', 'Iwao Nakajima', 'óleo sobre tela', NULL, '58 x 65,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '98.jpg'),
(99, NULL, 'Vaso com flores', 'Mitiko Ianagui', 'óleo sobre tela', NULL, '38,5 x 48,5 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '99.jpg'),
(100, NULL, 'O despertar do Beija-flor', 'Hosana Ortiz', 'aquarela', NULL, '26 x 30', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '100.jpg'),
(101, NULL, 'Pássaro no ninho', 'Hosana Ortiz', 'aquarela', NULL, '33,5 x 33,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '101.jpg'),
(102, NULL, 'Presente da Primavera', 'Nur Chap Chap', 'óleo sobre tela', NULL, '47 x 57', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '102.jpg'),
(103, NULL, 'Luz na destruição', 'Elaine Garcêz', 'óleo sobre tela', NULL, '20 x 48', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '103.jpg'),
(104, NULL, 'Na floresta', 'João Pio de Almeida Prado', 'óleo sobre tela', NULL, '35 x 42,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '104.jpg'),
(105, NULL, 'Testa di cavallo', 'Dhiego Damasceno', 'óleo sobre tela', NULL, '25 x 50 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '105.jpg'),
(106, NULL, 'Caminhos suspensos', 'Gustavo Ulson', 'gravura sobre papel', NULL, '37 x 52,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '106.jpg'),
(107, NULL, 'Asas da imaginação', 'Gustavo Ulson', 'gravura sobre papel', NULL, '37 x 52,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '107.jpg'),
(108, NULL, 'Alucinação', 'George Schiriacritica', 'óleo sobre tela', NULL, '17 x 40', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '108.jpg'),
(109, NULL, 'Nossa chegada ao Brasil 1', 'Mitiko Ianagui', 'shadow art', NULL, '40,5 x 49', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '109.jpg'),
(110, NULL, 'Nossa chegada ao Brasil 2', 'Mitiko Ianagui', 'shadow art', NULL, '40,5 x 48,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '110.jpg'),
(111, NULL, 'Nossa chegada ao Brasil 3', 'Mitiko Ianagui', 'shadow art', NULL, '39,5 x 49', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '111.jpg'),
(112, NULL, 'Nossa chegada ao Brasil 4', 'Mitiko Ianagui', 'shadow art', NULL, '49,5 x 58', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '112.jpg'),
(113, NULL, 'Nossa chegada ao Brasil 5', 'Mitiko Ianagui', 'shadow art', NULL, '40 x 46,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '113.jpg'),
(114, NULL, 'Nossa chegada ao Brasil 6', 'Mitiko Ianagui', 'shadow art', NULL, '34 x 49', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '114.jpg'),
(115, NULL, 'Nossa chegada ao Brasil 7', 'Mitiko Ianagui', 'shadow art', NULL, '40 x 49,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '115.jpg'),
(116, NULL, 'Nossa chegada ao Brasil 8', 'Mitiko Ianagui', 'shadow art', NULL, '33 x 50', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '116.jpg'),
(117, NULL, 'Pássaro silvestre', 'Heinz Budweg', 'óleo sobre tela', NULL, '49 x 58', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '117.jpg'),
(118, NULL, 'Águia da Independência', 'Heinz Budweg', 'óleo sobre tela', NULL, '83 x 83', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '118.jpg'),
(119, NULL, 'Terpsicore a musa rodopiante da dança', 'Giuseppe Ranzini', 'arte desciforme', NULL, '38 x 50', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '119.jpg'),
(120, NULL, 'Olvidar', 'Lutax Bueno', 'óleo sobre tela', NULL, '33,5 x 43,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '120.jpg'),
(121, NULL, 'Solmaior', 'Jorge Bussab', 'sobre papel', NULL, '54 x 72', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '121.jpg'),
(122, NULL, 'Circo de Outrora', 'Jorge Bussab', 'óleo sobre tela', NULL, '110 x 130', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '122.jpg'),
(123, NULL, 'Irmãs', 'Jorge Bussab', 'sobre papel', NULL, '64,5 x 75', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '123.jpg'),
(124, NULL, 'Inovação', 'Anna Maria MatarazzoTrunkl ', 'acrílica sobre tela', 2002, '80 x 80', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '124.jpg'),
(125, NULL, 'Pesquisando', 'Anna Maria MatarazzoTrunkl', 'acrílica sobre tela', 2019, '62,5 x 82', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '125.jpg'),
(126, NULL, 'Chez Antonella', 'Morago', 'óleo sobre tela', NULL, '53 x 53', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '126.jpg'),
(127, NULL, 'As mensagens na vila', 'Hans B. Guecazinssai', 'gravura', 1975, '50 x 60', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '127.jpg'),
(128, NULL, 'A cor é uma força criante', 'Ricardo', 'aquarela', NULL, '47 x 66 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '128.jpg'),
(129, NULL, 'Jardim da Câmara Municipal', 'Marcos Jorge Bataglia', 'fotografia ', 2011, '49,5 x 36,5', 'Marcos Jorge Bataglia', '10.00', NULL, NULL, '129.jpg'),
(130, NULL, 'Olhar enigmático', 'Cleu Hilarius', 'aquarela', 1981, '69 x 95', 'Roberto Eduardo Lamari', '10.00', NULL, NULL, '130.jpg'),
(131, NULL, 'Lago Titisee na Suíça', 'Inarassam', 'fotografia preto e branco', 1960, '36 x 47', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '131.jpg'),
(132, NULL, 'O fugitivo ', 'Lausane', 'fotografia preto e branco', 2007, '37 x 42', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '132.jpg'),
(133, NULL, 'O barroco mineiro', 'Fliy', 'fotografia', NULL, '37 x 46', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '133.jpg'),
(134, NULL, 'Canal de Milão', 'Manuk Poladian', 'fotografia a cores', NULL, '37 x 47', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '134.jpg'),
(135, NULL, 'Barco em Veneza', 'Manuk Poladian', 'fotografia a cores', NULL, '37 x 47', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '135.jpg'),
(136, NULL, 'O entardecer na água', 'Roberto Santos', 'fotografia a cores', NULL, '48 x 68 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '136.jpg'),
(137, NULL, 'Olhar felino', 'Johnny', 'fotografia a cores', NULL, '51,5 x 67 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '137.jpg'),
(138, NULL, 'Avenida São Luiz', 'Edson Souza', 'óleo sobre cartão', NULL, '26,5 x 31', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '138.jpg'),
(139, NULL, 'Avenida Paulista', 'Edson Souza', 'óleo sobre cartão', NULL, '26,5 x 31', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '139.jpg'),
(140, NULL, 'Oca do Ibirapuera', 'Luiz Marinho', 'fotografia a cores', 2000, '41 x 51', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '140.jpg'),
(141, NULL, 'Museu do Ipiranga', 'Luiz Marinho', 'fotografia a cores', 2000, '41 x 51', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '141.jpg'),
(142, NULL, 'Via di Torino', 'Fotógrafo piemontese', 'fotografia preto e branco ', NULL, '32,5 x 42,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '142.jpg'),
(143, NULL, 'La cattedrale di Torino', 'Fotógrafo piemontese', 'fotografia preto e branco ', NULL, '32,5 x 42,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '143.jpg'),
(144, NULL, 'Imagens de Ouro Preto', 'Isabella Freitas da Costa', 'fotografia', NULL, '31 x 37 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '144.jpg'),
(145, NULL, 'Composição', 'Jorge Bussab', 'óleo sobre tela', NULL, '83 x 63', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '145.jpg'),
(146, NULL, 'Central Park', 'Jorge Bussab', 'óleo sobre tela', NULL, '84 x 103 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '146.jpg'),
(147, NULL, 'Primavera', 'Jorge Bussab', 'óleo sobre tela', NULL, '53 x 63 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '147.jpg'),
(148, NULL, 'Evolução', 'Jorge Bussab', 'óleo sobre tela', NULL, '61,5 x 82,5 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '148.jpg'),
(149, NULL, 'L’eglise', 'Jorge Bussab', 'sobre papel', NULL, '67 x 87 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '149.jpg'),
(150, NULL, 'Paraty', 'Jorge Bussab', 'óleo sobre tela', NULL, '57 x 48 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '150.jpg'),
(151, NULL, 'Ladeira do amor', 'Jorge Bussab', 'óleo sobre tela', NULL, '57 x 48 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '151.jpg'),
(152, NULL, 'Paixão', 'Jorge Bussab', 'óleo sobre tela', NULL, '57 x 48 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '152.jpg'),
(153, NULL, 'Operários em ação', 'Connie Betavas', 'acrílica sobre tela', NULL, '77 x 101 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '153.jpg'),
(154, NULL, 'Maças na fruteira', 'Inos Corradin', 'óleo sobre tela', NULL, '64,5 94,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '154.jpg'),
(155, NULL, 'Sucata', 'Inos Corradin', 'óleo sobre tela', NULL, '67 x 97,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '155.jpg'),
(156, NULL, 'Paisagem do campo', 'Anna Maria MatarazzoTrunkl', 'óleo sobre tela', NULL, '63,5 x 53,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '156.jpg'),
(157, NULL, 'Cavalo no pasto', 'Anna Maria MatarazzoTrunkl', 'óleo sobre tela', NULL, '53,5 x 43,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '157.jpg'),
(158, NULL, 'Fazenda de gado', 'Anna Maria MatarazzoTrunkl', 'óleo sobre tela', NULL, '73,5 x 53,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '158.jpg'),
(159, NULL, 'La Terazza', 'Anna Maria MatarazzoTrunkl', 'óleo sobre tela', NULL, '73,5 x 53,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '159.jpg'),
(160, NULL, 'Canal em Veneza', 'Anna Maria MatarazzoTrunkl', 'óleo sobre tela', NULL, ' 53,5 x 73,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '160.jpg'),
(161, NULL, 'Casa de veraneio', 'Antônio Pontado', 'óleo sobre tela', 1897, '74,5 x 59', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '161.jpg'),
(162, NULL, 'Paisagem japonesa', 'Mitiko Ianagui', 'arte mista', NULL, '48,5 x 58,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '162.jpg'),
(163, NULL, 'Paisagem holandesa', 'Mitiko Ianagui', 'arte mista', NULL, '38,5 x 48,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '163.jpg'),
(164, NULL, 'Meu quarto', 'Mitiko Ianagui', 'arte mista', NULL, '38,5 x 48,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '164.jpg'),
(165, NULL, 'II Palazzo Vecchio - TORINO', 'André Chapuy (1822 – 1941)', 'gravura a cores', NULL, '41 x 33', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '165.jpg'),
(166, NULL, 'Veduto de S. Giovani - TORINO', 'André Chapuy (1822 – 1941)', 'gravura a cores', NULL, '41 x 33', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '166.jpg'),
(167, NULL, 'Torre de Belém', 'Ivo Blasi', 'óleo sobre tela', NULL, '37,5 x 47', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '167.jpg'),
(168, NULL, 'Era uma vez em Veneza', 'Fátima Marques', 'arte realista –  óleo sobre tela', NULL, '59,5 x 80', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '168.jpg'),
(169, NULL, 'Figura geométrica 1', 'Eugênio Carmi (1920 – 2016)', 'serigrafia geométrica sobre madeira', NULL, '43 x 43', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '169.jpg'),
(170, NULL, 'Figura geométrica 2', 'Eugênio Carmi (1920 – 2016)', 'serigrafia geométrica sobre madeira', NULL, '43 x 43', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '170.jpg'),
(171, NULL, 'Tudo azul', 'Gabi Fornari', 'óleo sobre tela', NULL, '29,5 x 29,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '171.jpg'),
(172, NULL, 'Busca no espaço 51/70', 'Veríssimo Medeiros', 'litogravura sobre papel ', NULL, '50 x 70', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '172.jpg'),
(173, NULL, 'O silêncio da noite', 'Raymond Asseo', 'cromografia', 1980, '39,5 x 43', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '173.jpg'),
(174, NULL, 'Composição geométrica', 'Fernando Durão', 'óleo sobre tela', NULL, '50 x 50', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '174.jpg'),
(175, NULL, 'Trêti', 'R. Isca', 'arte mista', NULL, '25 x 25', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '175.jpg'),
(176, NULL, 'Unti', 'R. Isca', 'arte mista', NULL, '25 x 25', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '176.jpg'),
(177, NULL, 'Geometria do futuro', 'Alitula', 'óleo sobre tela', NULL, '57 x 68 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '177.jpg'),
(178, NULL, 'A espera da família', 'Mário Beltrame', 'óleo sobre tela', 1980, '41,5 x 61,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '178.jpg'),
(179, NULL, 'Relembrando o meu bairro', 'Graciete Borges', 'óleo sobre tela', NULL, '53 x 73', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '179.jpg'),
(180, NULL, 'Litoral', 'Lúcia Helena', 'óleo sobre tela', 2019, '53 x 43', 'Lúcia Helena Rodrigues de Oliveira', '10.00', NULL, NULL, '180.jpg'),
(181, NULL, 'Visão de São Paulo', 'Vinicius Pellegrino', 'desenho', NULL, '35 x 43,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '181.jpg'),
(182, NULL, 'Relembrando a Grécia', 'Vinicius Pellegrino', 'desenho', NULL, '35 x 43,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '182.jpg'),
(183, NULL, 'Antiga Roma', 'Vinicius Pellegrino', 'desenho', NULL, '35 x 43,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '183.jpg'),
(184, NULL, 'Viridis II', 'Jorge Bussab', 'gravura', NULL, '36,5 x 49', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '184.jpg'),
(185, NULL, 'Visita ao jardim', 'Ana Cristina Andrade', 'gravura', NULL, '39,5 x 55', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '185.jpg'),
(186, NULL, 'O desaparecimento do Tietê', 'Antônio Orbetelli', 'gravura sobre tela', 2019, '53,5 x 73,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '186.jpg'),
(187, NULL, 'Inverno suíço', 'J. Duprat', 'gravura sobre papel', NULL, '46 x 56', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '187.jpg'),
(188, NULL, 'Saudade da Bahia', 'Renot', 'óleo sobre tela', NULL, '26 x 32', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '188.jpg'),
(189, NULL, 'E a neve chegou...', 'N. Favarger', 'gravura sobre papel', NULL, '30 x 40', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '189.jpg'),
(190, NULL, 'Paisagem oriental', 'Kenji Fukuda', 'aquarela', NULL, '44 x 53', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '190.jpg'),
(191, NULL, 'Langue D’ inverno ', 'Guido Botta', 'serigrafia', NULL, '50,5 x 70,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '191.jpg'),
(192, NULL, 'Paisagem memorável 1', 'Elizabeth Tudisco', 'óleo sobre tela', NULL, '32 x 36', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '192.jpg'),
(193, NULL, 'Paisagem memorável 2', 'Elizabeth Tudisco', 'óleo sobre tela', NULL, '30 x 36', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '193.jpg'),
(194, NULL, 'Paisagem Prisioneira', 'Eduardo Iglesias', 'aquarela e lápis de cor', NULL, '53 x 76 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '194.jpg'),
(195, NULL, 'Eternizando Santo Amaro', 'Jorge Bussab', 'gravura sobre papel', 1973, '58 x 73', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '195.jpg'),
(196, NULL, 'Barco a deriva', 'Ernesto Lia', 'óleo sobre tela', NULL, '34 x 42,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '196.jpg'),
(197, NULL, 'A maré subiu', 'Ernesto Lia', 'óleo sobre tela', 1958, '41 x 51', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '197.jpg'),
(198, NULL, 'A visitante', 'Guiomar de Souza', 'óleo sobre tela', NULL, '58,5 x 68,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '198.jpg'),
(199, NULL, 'O trem', 'Marcos Jorge Bataglia', 'óleo sobre tela', NULL, '99 x 79', 'Marcos Jorge Bataglia', '10.00', NULL, NULL, '199.jpg'),
(200, NULL, 'Abstracionismo', 'Nikolai Dragus', 'óleo sobre tela', 1979, '60 x 100', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '200.jpg'),
(201, NULL, 'História de Nair – Série Macunaíma', 'Martins de Porangaba', 'óleo sobre tela', NULL, '35 x 55', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '201.jpg'),
(202, NULL, 'A cotia deu um banho', 'Martins de Porangaba', 'óleo sobre tela', 1982, '39,5 x 59,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '202.jpg'),
(203, NULL, 'Reunião dominical', 'Martins de Porangaba', 'óleo sobre tela', 1978, '30 x 97', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '203.jpg'),
(204, NULL, 'Diálogo', 'Martins de Porangaba', 'óleo sobre tela', 1979, '50 x 62', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '204.jpg'),
(205, NULL, 'Devaneio', 'Samira Darviche', 'óleo sobre tela', 1977, '37 x 46', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '205.jpg'),
(206, NULL, 'Giardino Giamlovese', 'Joseph Pace', 'gravura sobre papel', NULL, '49 x 57,8', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '206.jpg'),
(207, NULL, 'SURREAL V', 'Ybirajá', 'pastel', 2012, '36,5 x 51', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '207.jpg'),
(208, NULL, 'Periferia', 'Joseph Pace', 'gravura sobre papel', NULL, '45 x 52', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '208.jpg'),
(209, NULL, 'SURREAL IV', 'Ybirajá', 'pastel', 2012, '36,5 x 51', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '209.jpg'),
(210, NULL, 'É essencial manter a essência', 'Joseph Pace', 'gravura sobre papel', NULL, '45 x 52', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '210.jpg'),
(211, NULL, 'Retrato de Buda', 'Joseph Pace', 'óleo sobre cartão', NULL, '35,5 x 48', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '211.jpg'),
(212, NULL, 'Abstração', 'Joseph Pace', 'óleo sobre cartão', NULL, '44,5 x 51,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '212.jpg'),
(213, NULL, 'Figura Sergipana 1', 'Antônio Maia (1928 – 2008)', 'acrílica sobre tela', NULL, '34,5 x 42', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '213.jpg'),
(214, NULL, 'Figura Sergipana 2', 'Antônio Maia (1928 – 2008)', 'acrílica sobre tela', NULL, '38 x 43', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '214.jpg'),
(215, NULL, 'Figura Sergipana 3', 'Antônio Maia (1928 – 2008)', 'acrílica sobre tela', NULL, '29,5 x 47,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '215.jpg'),
(216, NULL, 'Figura Sergipana 4', 'Antônio Maia (1928 – 2008)', 'acrílica sobre tela', NULL, '45 x 53', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '216.jpg'),
(217, NULL, 'Figura Sergipana 5', 'Antônio Maia (1928 – 2008)', 'acrílica sobre tela', NULL, '58 x 73', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '217.jpg'),
(218, NULL, 'Figura Sergipana 6', 'Antônio Maia (1928 – 2008)', 'acrílica sobre tela', NULL, '40 x 47', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '218.jpg'),
(219, NULL, 'Figura Sergipana 7', 'Antônio Maia (1928 – 2008)', 'acrílica sobre tela', NULL, '28,5 x 36,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '219.jpg'),
(220, NULL, 'Figura Sergipana 8', 'Antônio Maia (1928 – 2008)', 'acrílica sobre tela', NULL, '33,5 x 41', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '220.jpg'),
(221, NULL, 'Releitura de Leonardo da Vinci', 'Marlene de Andrade ', 'óleo sobre tela', NULL, '60 x 78', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '221.jpg'),
(222, NULL, 'A prece', 'Nikolai Dragus', 'óleo sobre tela', NULL, '50 x 70', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '222.jpg'),
(223, NULL, 'Nu artístico', 'Fabri', 'desenho sobre papel', NULL, '30 x 40', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '223.jpg'),
(224, NULL, 'Aspiration', 'Rosemunde Krebeck', 'sobre papel', NULL, '39 x 55', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '224.jpg'),
(225, NULL, 'A espera do amor ', 'Iaponi Araújo', 'desenho a nanquim', 1971, '42,5 x 42,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '225.jpg'),
(226, NULL, 'Las Claus novidenãs posadonas para foto', 'Verônica Pacheco', 'aquarela', NULL, '64,5 x 68', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '226.jpg'),
(227, NULL, 'Señora', 'Verônica Pacheco', 'aquarela', NULL, '23 x 29', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '227.jpg'),
(228, NULL, 'La Arlequina Coquetona Fiorella Fionita', 'Verônica Pacheco', 'óleo sobre tela', NULL, '61,5 x 61,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '228.jpg'),
(229, NULL, 'O Curupira está muito machucado', 'Getsusen Kobayashi', 'óleo sobre tela', NULL, '78,5 x 78,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '229.jpg'),
(230, NULL, 'Imagem velada', 'Oskar Schlemmer', 'aquarela', 1928, '35,5 x 43', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '230.jpg'),
(231, NULL, 'Confabulando', 'Antônio Henrique Amaral (1935 – 2015)', 'xilogravuras', 1969, '31 x 40', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '231.jpg'),
(232, NULL, 'O meu e o seu', 'Antônio Henrique Amaral (1935 – 2015)', 'xilogravuras', NULL, '48,5 x 60,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '232.jpg'),
(233, NULL, 'Tempestade ', 'Túlio Rezende', 'óleo sobre tela', 1983, '54 x 60', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '233.jpg'),
(234, NULL, 'Explosão', 'Walter Bravo', 'óleo sobre tela', NULL, '94 x 94', 'Emanuel von Lauenstein Massarani', '10.00', '', '', '234.jpg'),
(235, NULL, 'Sintonizando', 'Artista não identificado', 'abstrata ', NULL, '53,5 x 69', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '235.jpg'),
(236, NULL, 'Lagoinha', 'Jorge Bussab', 'óleo sobre tela', NULL, '48 x 56', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '236.jpg'),
(237, NULL, 'Dádiva', 'Jorge Bussab', 'óleo sobre tela', NULL, '48 x 56', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '237.jpg'),
(238, NULL, 'Jazz', 'Jorge Bussab', 'óleo sobre tela', NULL, '110 x 129', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '238.jpg'),
(239, NULL, 'Caminho do sentimento', 'Jorge Bussab', 'sobre papel', 1973, '51 x 66', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '239.jpg'),
(240, NULL, 'Vila querida', 'Jorge Bussab', 'sobre papel', 1972, '50,5 x 70,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '240.jpg'),
(241, NULL, 'Paris', 'Jorge Bussab', 'sobre papel', 1972, '51 x 57', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '241.jpg'),
(242, NULL, 'Essa casa é feliz com você', 'Jorge Bussab', 'óleo sobre tela', NULL, '53 x 63', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '242.jpg'),
(243, NULL, 'Jesuino', 'Jorge Bussab', 'óleo sobre tela', NULL, '53 x 63', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '243.jpg'),
(244, NULL, 'Paisagem', 'Jorge Bussab', 'óleo sobre tela', NULL, '53 x 63', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '244.jpg'),
(245, NULL, 'Barcos no porto', 'Jorge Bussab', 'óleo sobre tela', NULL, '53 x 63', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '245.jpg'),
(246, NULL, 'A espera comentada', 'Jorge Bussab', 'óleo sobre tela', NULL, '53 x 63', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '246.jpg'),
(247, NULL, 'Outono em Giverni', 'Geanete Reinis', 'óleo sobre tela', NULL, '80 x 100', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '247.jpg'),
(248, NULL, 'Estilizando prédios', 'Rosely Kavaleski', 'óleo sobre tela', NULL, '100 x 70', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '248.jpg'),
(249, NULL, 'Número 2', 'Eufrosina Setsu Umezawa', 'óleo sobre tela', NULL, '70 x 100', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '249.jpg'),
(250, NULL, 'Otoño (Outono)', 'Laura Martinez', 'óleo sobre tela', NULL, '100 x 80', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '250.jpg'),
(251, NULL, 'Gaucho', 'J. Nabiles', 'gravura sobre cartão', 1915, '27,5 x 32', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '251.jpg'),
(252, NULL, 'Danças nordestinas', 'J. Nabiles', 'gravura sobre cartão', 1915, '27,5 x 32', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '252.jpg'),
(253, NULL, 'Arte floral nipônica 1', 'Mitiko Ianagui', 'shadow art', 2008, '49,5 x 40', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '253.jpg'),
(254, NULL, 'Arte floral nipônica 2', 'Mitiko Ianagui', 'shadow art', 2008, '49,5 x 40', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '254.jpg'),
(255, NULL, 'Certificado de Menção Honrosa ', 'ABEL', 'certificado', 2018, '37 x 28', 'Câmara Municipal de itapevi', '10.00', NULL, NULL, '255.jpg'),
(256, NULL, 'Certificado de Participação', 'Voto Consciente', 'certificado', 2018, '37 x 28', 'Câmara Municipal de itapevi', '10.00', NULL, NULL, '256.jpg'),
(257, NULL, 'Transferindo conhecimento', 'Merly Stiguers', 'óleo sobre tela', NULL, '100 x 100', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '257.jpg'),
(258, NULL, 'Imagens de Itapevi 1', 'Raphael Piglinelli Stefanini', 'fotografia', 2015, '45 x 30', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '258.jpg'),
(259, NULL, 'Quadro de Medalhas e Honrarias', 'CMI', 'medalhas', NULL, '44 x 64', 'Câmara Municipal de itapevi', '10.00', NULL, NULL, '259.jpg'),
(260, NULL, 'Imagens de Itapevi 2', 'Raphael Piglinelli Stefanini', 'fotografia', 2015, '45 x 30', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '260.jpg'),
(261, NULL, 'O início da obra', 'Aleixa de Oliveira', 'desenho aquarelado', 2014, '100 x 100', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '261.jpg'),
(262, NULL, 'A cerejeira', 'Agnes Franchini', 'óleo sobre tela', NULL, '60 x 90', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '262.jpg'),
(263, NULL, 'Nossa chegada ao Brasil 9', 'Mitiko Ianagui', 'shadow art', NULL, '50, x 40,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '263.jpg'),
(264, NULL, 'Nossa chegada ao Brasil 10', 'Mitiko Ianagui', 'shadow art', NULL, '46,5, x 40', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '264.jpg'),
(265, NULL, 'Pinos', 'Jaques Lurita', 'gravura sobre papel', NULL, '22 x 30', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '265.jpg'),
(266, NULL, 'O encontro com o Anjo', 'Flávia Gianini', 'bordado a cores', NULL, '37,5 x 37,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '266.jpg'),
(267, NULL, 'Retrato de sua Santidade João Paulo II  ', 'Gaetano Miani', 'reprodução gráfica de tela a óleo', 1975, '57 x 77 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '267.jpg'),
(268, NULL, 'Pausa do Palhaço', 'Túlio Rezende', 'óleo sobre tela', 1962, '70 x 80', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '268.jpg'),
(269, NULL, 'Alegria infantil', 'Gustavo Lima', 'óleo sobre tela', NULL, '88 x 68', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '269.jpg'),
(270, NULL, 'Plenitude', 'Maria Luiza Florentino', 'óleo sobre tela', NULL, '70 x 100', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '270.jpg'),
(271, NULL, 'Lendas', 'Giorgio de Chirico (1888 – 1978)', 'gravura sobre papel', NULL, '51 x 42', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '271.jpg'),
(272, NULL, 'A Mulata da rua vermelha', 'Di Cavalcanti', 'reprodução gráfica de original', 1960, '40x 50', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '272.jpg'),
(273, NULL, 'Remando', 'Raphael Piglinelli Stefanini', 'reprodução de  a óleo sobre papel ', NULL, '65 x 65', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '273.jpg'),
(274, NULL, 'Surfando', 'Raphael Piglinelli Stefanini', 'reprodução de  a óleo sobre papel ', NULL, '65 x 65', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '274.jpg'),
(275, NULL, 'Maratona', 'Raphael Piglinelli Stefanini', 'reprodução de  a óleo sobre papel ', NULL, '65 x 65', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '275.jpg'),
(276, NULL, 'Partida de futebol', 'Raphael Piglinelli Stefanini', 'reprodução de  a óleo sobre papel ', NULL, '65 x 65', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '276.jpg'),
(277, NULL, 'Voleibol', 'Raphael Piglinelli Stefanini', 'reprodução de  a óleo sobre papel ', NULL, '65 x 65', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '277.jpg'),
(278, NULL, 'Ollie', 'Sabrina Coelho', 'óleo sobre papel', NULL, '65 x 65', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '278.jpg'),
(279, NULL, 'Manobra radical', 'Raphael Piglinelli Stefanini', 'reprodução de  a óleo sobre papel ', NULL, '65 x 65', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '279.jpg'),
(280, NULL, 'Operários', 'Ida Zami', 'reprodução fotográfica', 2014, '100 x 100', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '280.jpg'),
(281, NULL, 'Ivre', 'Borbon', 'óleo sobre tela', NULL, '100 x 100', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '281.jpg'),
(282, NULL, 'Médicos em ação', 'Gabi Fornari', 'óleo sobre tela', NULL, '100 x 100', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '282.jpg'),
(283, NULL, 'Grãos de café', 'Aleixa de Oliveira', 'reprodução fotográfica', NULL, '94 x 102', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '283.jpg'),
(284, NULL, 'Explosão no espaço 14/20', 'Lorenzo di Stefano', 'gravura sobre papel  ', 1967, '42,5 x 42,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '284.jpg'),
(285, NULL, 'Evolução de recortes', 'K. A.', 'Imported landscape', 1976, '45,5 x 45,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '285.jpg'),
(286, NULL, 'Forró', 'F. Bispo', 'óleo sobre tela', 2004, '43 x 33', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '286.jpg'),
(287, NULL, 'Escultura em madeira ', 'João Carlos Bellani', 'fotografia', NULL, '72 x 52', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '287.jpg'),
(288, NULL, 'Comemorando o Natal azul', 'Eduardo Iglesias', 'óleo sobre cartão', 1976, '9 x 9,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '288.jpg'),
(289, NULL, 'O abstrato', 'César Romero', 'aquarela sobre papel', 2008, '14 x 12', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '289.jpg'),
(290, NULL, 'Imagens de Itapevi 3', 'Raphael Piglinelli Stefanini', 'fotografia', 2015, '36,5 x 29', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '290.jpg'),
(291, NULL, 'Requestar', 'Ricardo Amadeo ', 'óleo sobre tela', NULL, '73 x 53', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '291.jpg'),
(292, NULL, 'Inspiração 13/20', 'De Lamani', 'gravura sobre papel ', 1961, '52 x 71,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '292.jpg'),
(293, NULL, 'Fim da Guerra', 'J. Vesalio', 'desenho a nanquim', 1973, '38 x 43,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '293.jpg'),
(294, NULL, 'Cidade Amarela', 'G. Luiz', 'gravura sobre papel', 1971, '41,5 x 34,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '294.jpg'),
(295, NULL, 'Eclipse no céu de Picinguaba', 'Edval Ramosa', 'areia sobre madeira', 2004, '19,5 x 70', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '295.jpg'),
(296, NULL, 'Brasília', 'Frank', 'desenho a lápis', 1961, '87,5 x 70,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '296.jpg'),
(297, NULL, 'Sobre as ondas do rio mar', 'Inarassam', 'escultura de parede', 1959, '54,5 x 42,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '297.jpg'),
(298, NULL, 'Igreja Matriz de São Bernardo do Campo', 'Carlos Marcos ', 'óleo sobre tela', 1978, '30 x 40', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '298.jpg'),
(299, NULL, 'SURREAL VI', 'Ybirajá', 'pastel', 2012, '36,5 x 51', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '299.jpg'),
(300, NULL, 'SURREAL III', 'Ybirajá', 'pastel', 2012, '36,5 x 51', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '300.jpg'),
(301, NULL, 'SURREAL II', 'Ybirajá', 'pastel', 2012, '36,5 x 51', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '301.jpg'),
(302, NULL, 'Tarde de Frutas', 'Guido Tottoli ', 'óleo sobre tela', NULL, '81 x 67 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '302.jpg'),
(303, NULL, 'Abstação Técnica', 'Ida Zami', 'técnica mista', NULL, '100 x 100 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '303.jpg'),
(304, NULL, 'Explosão Afetiva', 'Navarro', 'óleo sobre tela', NULL, '94 x 121 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '304.jpg'),
(305, NULL, 'Misure', 'Silvana Galeone', 'técnica mista', NULL, '80 x 100 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '305.jpg'),
(306, NULL, 'La Tempete (A tempestade)', 'Vernet', 'gravura original do século XVII', NULL, '70 x 83 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '306.jpg'),
(307, NULL, 'Matrimônio Lilás séc. XIX', 'Gracita Garcia Bueno', 'técnica mista - óleo sobre tela e colagem', 2001, '80 x 80', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '307.jpg'),
(308, NULL, 'Autoretatro', 'Litho', 'fotografia', 2000, '32 x 28', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '308.jpg'),
(309, NULL, 'Desafio ', 'Nicolai Dragos', 'óleo sobre tela', 2011, '120 x 100', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '309.jpg'),
(310, NULL, 'Futuro nº1', 'Kazuko Iwai', 'arte do shippô em metal', NULL, '39 x 19,5', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '310.jpg'),
(311, NULL, 'Colônia de São Vicente', 'Vinicius Pellegrino', 'nanquim e aquarela sobre papel', 2021, '14 x 21', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '311.jpg'),
(312, NULL, 'Evolução de Personagens ', 'J. C. CANATO', 'pó de mármore', NULL, '26 x 26 ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '312.jpg'),
(313, NULL, 'Escultura: Busto Emanuel Massarani', 'Afinowicz ', 'granito dourado', 2008, '45 de altura', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '313.jpg'),
(314, NULL, 'Escultura: Robot', 'Jorge Bussab', 'metal pintado', NULL, '152 de altura ', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '314.jpg'),
(315, NULL, 'Escultura: O Balanço do Pássaro', 'Lúcio Bittencourt', 'metal pintado e madeira', NULL, '66 de altura', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '315.jpg'),
(316, NULL, 'Escultura: Concepção 1', 'Lúcio Bittencourt', 'sucata de metal', NULL, '144,5 de altura', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '316.jpg'),
(317, NULL, 'Escultura: Concepção 2', 'Lúcio Bittencourt', 'sucata de metal', NULL, '134 de altura', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '317.jpg');
INSERT INTO `artpieces` (`id`, `CMI_propertyNumber`, `name`, `artist`, `technique`, `year`, `size`, `donor`, `value`, `location`, `description`, `mainImageAttachmentFileName`) VALUES
(318, NULL, 'Escultura: Concepção 3', 'Lúcio Bittencourt', 'sucata de metal', NULL, '210 de altura', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '318.jpg'),
(319, NULL, 'Escultura: Concepção 4', 'Lúcio Bittencourt', 'sucata de metal', NULL, '95 de altura', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '319.jpg'),
(320, NULL, 'Escultura: Visão do Alto', 'Rodrigo Bittencourt', 'metal pintado', NULL, '44  de altura', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '320.jpg'),
(321, NULL, 'Escultura: Dupla visão', 'Ricardo Amadeo ', 'massa plástica', 2020, '53 de altura', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '321.jpg'),
(322, NULL, 'Escultura: O Mundo dos Parafusos', 'Inarassam', 'ferro pintado', NULL, '28 de altura', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '322.jpg'),
(323, NULL, 'Escultura: Tensão', 'Inarassam', 'granito dourado', 1980, '28 de altura', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '323.jpg'),
(324, NULL, 'Escultura: A chama da vida', 'Inarassam', 'acrílico vermelho', NULL, '52 de altura', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '324.jpg'),
(325, NULL, 'Escultura: Corpo humano', 'Inarassam', 'acrílico azul', NULL, '48 de altura', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '325.jpg'),
(326, NULL, 'Escultura: Visão de Marte', 'Inarassam', 'massa plástica dourada', NULL, '56 de altura', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '326.jpg'),
(327, NULL, 'Escultura: Forte Afeição', 'Inarassam', 'metal sob madeira', NULL, '26  de altura', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '327.jpg'),
(328, NULL, 'Escultura: Maria', 'Marilda Dib', 'acrílico azul', NULL, '53 de altura', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '328.jpg'),
(329, NULL, 'Escultura: O Nascimento de Cristo', 'Ida Zami', 'marmore', NULL, '25,5 de altura', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '329.jpg'),
(330, NULL, 'Escultura: Futebol na Vila do Panamericano ', 'Nino Ferraz', 'metal pintado', 2007, '48  de altura', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '330.jpg'),
(331, NULL, 'Escultura: Diversificação', 'Claudio Silberberg', 'metal  ', NULL, '77,5 de altura', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '331.jpg'),
(332, NULL, 'Escultura: O olhar de metal', 'Claudio Silberberg', 'metal', NULL, '73 de altura', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '332.jpg'),
(333, NULL, 'Escultura: Na curva da vida', 'Claudio Silberberg', 'metal', NULL, '73,5 de altura', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '333.jpg'),
(334, NULL, 'Escultura: Raios de sol', 'Claudio Silberberg', 'metal', NULL, '143 de altura', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '334.jpg'),
(335, NULL, 'Escultura: Andorinhas ao vento', 'Claudio Silberberg', 'ferro', NULL, '44  de altura', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '335.jpg'),
(336, NULL, 'Escultura: Rotatividade', 'Claudio Silberberg', 'ferro pintado', NULL, '52  de altura', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '336.jpg'),
(337, NULL, 'Escultura: Flauta Indígena', 'Claudio Silberberg', 'ferro pintado', NULL, '63  de altura', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '337.jpg'),
(338, NULL, 'Escultura: Engrenagens', 'Claudio Silberberg', 'ferro pintado', NULL, '110  de altura', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '338.jpg'),
(339, NULL, 'Escultura: Insetos enclausurados', 'Claudio Silberberg', 'metal pintado', NULL, '65  de altura', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '339.jpg'),
(340, NULL, 'Escultura: Florescendo', 'Claudio Silberberg', 'metal pintado', NULL, '110  de altura', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '340.jpg'),
(341, NULL, 'Escultura: Tulípa', 'Claudio Silberberg', 'ferro pintado', NULL, '93  de altura', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '341.jpg'),
(342, NULL, 'Escultura: Meteoro', 'Fukuyama', 'granito e metal', NULL, '35  de altura', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '342.jpg'),
(343, NULL, 'Escultura: Bonsai', 'Roberto Camargo Figueiredo', 'madeira', NULL, '35  de altura', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '343.jpg'),
(344, NULL, 'Escultura: Cavalo', 'José Guerra', 'acrílico', NULL, '23  de altura', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '344.jpg'),
(345, NULL, 'Escultura: Personagens Infantis', 'Nino Millan', 'metal pintado', NULL, '32  de altura', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '345.jpg'),
(346, NULL, 'Escultura: Memórias', 'Artista não identificado', 'metal e barro queimado', NULL, '63 de altura', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '346.jpg'),
(347, NULL, 'Escultura: Luminária', 'Artista não identificado', 'alumínio cromado', NULL, '155  de altura', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '347.jpg'),
(348, NULL, 'Escultura: Luminária', 'Artista não identificado', 'alumínio cromado', NULL, '155  de altura', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '348.jpg'),
(349, NULL, 'Escultura: Vênus de Milo', 'Alexandre de Antioquia', 'reprodução em plástica de parte realizada no Museu do Louvre em Paris  ', NULL, '102 de altura', 'Emanuel von Lauenstein Massarani', '10.00', NULL, NULL, '349.jpg'),
(351, NULL, 'Teste Teste', 'Teste Teste', 'Teste Teste', 1995, '', 'Teste Teste', '10.00', '', '', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `calendardates`
--

CREATE TABLE `calendardates` (
  `id` int UNSIGNED NOT NULL,
  `parentId` int UNSIGNED DEFAULT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(140) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(280) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` date NOT NULL,
  `beginTime` time DEFAULT NULL,
  `endTime` time DEFAULT NULL,
  `styleJson` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `calendardates`
--

INSERT INTO `calendardates` (`id`, `parentId`, `type`, `title`, `description`, `date`, `beginTime`, `endTime`, `styleJson`) VALUES
(1, NULL, 'privatesimpleevent', 'Reunião de equipe', 'Na sala de reuniões..', '2022-03-15', '14:00:00', '16:00:00', NULL),
(2, NULL, 'holiday', 'Feriado fictício', NULL, '2022-03-16', NULL, NULL, NULL),
(3, NULL, 'publicsimpleevent', 'Aniversário de Fulano', 'Aniversário de Fulano da Silva', '2022-03-23', NULL, NULL, NULL),
(4, NULL, 'privatesimpleevent', 'aaaccc', 'fgfd', '2022-03-17', '17:10:49', '19:00:00', NULL),
(7, NULL, 'privatesimpleevent', 'Aniversário do Victor', 'Aniversário do Victor Opusculo Oliveira Ventura de Almeida', '2022-06-09', NULL, NULL, NULL),
(25, NULL, 'publicsimpleevent', 'Teste Cores', 'aaaaaaaaaaaaaaaaaasss2222', '2022-05-18', NULL, NULL, '{\"textColor\": \"#30019d\", \"backgroundColor\": \"#718dfe\"}'),
(30, 4, 'privatesimpleevent', 'aaaccc', 'fgfd', '2022-03-18', '12:00:00', '13:00:00', NULL),
(37, NULL, 'publicsimpleevent', 'jfhjhgjfjfhjf', 'fghfghfg', '2022-11-11', NULL, NULL, NULL),
(38, 37, 'publicsimpleevent', 'jfhjhgjfjfhjf', 'fghfghfg', '2022-11-12', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `calendardatestraits`
--

CREATE TABLE `calendardatestraits` (
  `calendarDateId` int UNSIGNED NOT NULL,
  `traitId` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Extraindo dados da tabela `calendardatestraits`
--

INSERT INTO `calendardatestraits` (`calendarDateId`, `traitId`) VALUES
(1, 1),
(1, 3),
(4, 1),
(4, 3),
(30, 1),
(30, 3),
(37, 1),
(38, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `certificates`
--

CREATE TABLE `certificates` (
  `id` int NOT NULL,
  `eventId` int NOT NULL,
  `dateTime` datetime NOT NULL,
  `email` varbinary(140) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Certificados emitidos para posterior autenticação';

--
-- Extraindo dados da tabela `certificates`
--

INSERT INTO `certificates` (`id`, `eventId`, `dateTime`, `email`) VALUES
(1, 2, '2022-02-26 22:06:24', 0x28fae367060eb0be260fa074b53ed700019964d75705021bee716092d0ed9895);

-- --------------------------------------------------------

--
-- Estrutura da tabela `enums`
--

CREATE TABLE `enums` (
  `type` varchar(10) NOT NULL,
  `id` int UNSIGNED NOT NULL,
  `value` varchar(120) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

--
-- Extraindo dados da tabela `enums`
--

INSERT INTO `enums` (`type`, `id`, `value`) VALUES
('GENDER', 1, 'Homem'),
('GENDER', 2, 'Mulher'),
('GENDER', 3, 'Homem transexual'),
('GENDER', 4, 'Mulher transexual/Travesti'),
('GENDER', 5, 'Prefiro não dizer'),
('EVENT', 1, 'Curso'),
('EVENT', 2, 'Palestra'),
('EVENT', 3, 'Live'),
('EVENT', 4, 'Debate'),
('OCCUPATION', 1, 'Servidor da Câmara Municipal de Itapevi'),
('OCCUPATION', 2, 'Servidor da Prefeitura Municipal de itapevi'),
('OCCUPATION', 3, 'Setor Público em geral'),
('OCCUPATION', 4, 'Setor Privado'),
('OCCUPATION', 5, 'Estudante'),
('OCCUPATION', 6, 'Aposentado'),
('OCCUPATION', 7, 'Desempregado'),
('SCHOOLING', 1, 'Ensino fundamental/Ensino médio'),
('SCHOOLING', 2, 'Superior incompleto'),
('SCHOOLING', 3, 'Superior completo'),
('SCHOOLING', 4, 'Pós-graduação/Especialização'),
('SCHOOLING', 5, 'Mestrado'),
('SCHOOLING', 6, 'Doutorado'),
('NATION', 1, 'Brasileiro(a)'),
('NATION', 2, 'Estrangeiro(a)'),
('RACE', 1, 'Amarela'),
('RACE', 2, 'Branca'),
('RACE', 3, 'Indígena'),
('RACE', 4, 'Parda'),
('RACE', 5, 'Preta'),
('RACE', 6, 'Prefiro não declarar'),
('UF', 1, 'Acre'),
('UF', 2, 'Alagoas'),
('UF', 3, 'Amapá'),
('UF', 4, 'Amazonas'),
('UF', 5, 'Bahia'),
('UF', 6, 'Ceará'),
('UF', 7, 'Distrito Federal'),
('UF', 8, 'Espírito Santo'),
('UF', 9, 'Goiás'),
('UF', 10, 'Maranhão'),
('UF', 11, 'Mato Grosso'),
('UF', 12, 'Mato Grosso Do Sul'),
('UF', 13, 'Minas Gerais'),
('UF', 14, 'Paraná'),
('UF', 15, 'Paraíba'),
('UF', 16, 'Pará'),
('UF', 17, 'Pernambuco'),
('UF', 18, 'Piauí'),
('UF', 19, 'Rio De Janeiro'),
('UF', 20, 'Rio Grande Do Norte'),
('UF', 21, 'Rio Grande Do Sul'),
('UF', 22, 'Rondônia'),
('UF', 23, 'Roraima'),
('UF', 24, 'Santa Catarina'),
('UF', 25, 'Sergipe'),
('UF', 26, 'São Paulo'),
('UF', 27, 'Tocantins'),
('UF', 28, 'EXTERIOR'),
('LIBCOLTYPE', 1, 'Livros'),
('LIBCOLTYPE', 2, 'Guias/Cartilhas/Manuais'),
('LIBCOLTYPE', 3, 'Revista Científica'),
('LIBACQTYPE', 1, 'Doação'),
('LIBUSRTYPE', 1, 'Vereador'),
('LIBUSRTYPE', 2, 'Funcionário efetivo da CMI'),
('LIBUSRTYPE', 3, 'Funcionário comissionado da CMI'),
('LIBUSRTYPE', 4, 'Estagiário da CMI'),
('LIBUSRTYPE', 5, 'Público externo'),
('LIBCOLTYPE', 4, 'Anuários/Almanaques/Catálogos'),
('LIBCOLTYPE', 5, 'Obras Especiais'),
('LIBCOLTYPE', 6, 'Caderno'),
('LIBCOLTYPE', 7, 'Coleções'),
('LIBCOLTYPE', 8, 'Dicionários'),
('LIBCOLTYPE', 9, 'Doc. / Cartográficos'),
('LIBCOLTYPE', 10, 'Doc. / Manuscritos'),
('LIBCOLTYPE', 11, 'Doc. / Textos Impressos'),
('LIBCOLTYPE', 12, 'Enciclopédia'),
('LIBCOLTYPE', 13, 'Estudos Técnicos'),
('LIBCOLTYPE', 14, 'Fóruns/Conferências'),
('LIBCOLTYPE', 15, 'Fotografias'),
('LIBCOLTYPE', 16, 'Jornais'),
('LIBCOLTYPE', 17, 'Monografias'),
('LIBCOLTYPE', 18, 'Obras E Arte'),
('LIBCOLTYPE', 19, 'Relíquias'),
('LIBCOLTYPE', 20, 'Revista Do Senado'),
('LIBCOLTYPE', 21, 'Revista Jurídica'),
('LIBCOLTYPE', 22, 'Revista/Periódico'),
('LIBCOLTYPE', 23, 'Revista Legislativa'),
('LIBCOLTYPE', 24, 'Série'),
('LIBCOLTYPE', 25, 'Relatórios'),
('LIBCOLTYPE', 26, 'Artigos Científicos'),
('LIBCOLTYPE', 27, 'Dissertações'),
('LIBCOLTYPE', 28, 'CDs, DVDs e Mídias'),
('LIBCOLTYPE', 29, 'Legislação (Leis e códigos)'),
('LIBCOLTYPE', 30, 'Apostila Curso'),
('LIBACQTYPE', 2, 'Compra'),
('LIBPERIOD', 1, 'Anual'),
('LIBPERIOD', 2, 'Semestral'),
('LIBPERIOD', 3, 'Trimestral'),
('LIBPERIOD', 4, 'Bimestral'),
('LIBPERIOD', 5, 'Quinzenal'),
('LIBPERIOD', 6, 'Semanal'),
('LIBPERIOD', 7, 'Diária'),
('LIBPERIOD', 8, 'Mensal'),
('LIBACQTYPE', 3, 'Edição Própria');

-- --------------------------------------------------------

--
-- Estrutura da tabela `eventattachments`
--

CREATE TABLE `eventattachments` (
  `id` int UNSIGNED NOT NULL,
  `eventId` int UNSIGNED NOT NULL,
  `fileName` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='Tabelo de anexos de eventos';

--
-- Extraindo dados da tabela `eventattachments`
--

INSERT INTO `eventattachments` (`id`, `eventId`, `fileName`) VALUES
(12, 2, 'EPI_O-CIDADAO-NA-COUNCIACAO-PUBLICA.jpeg'),
(13, 2, 'Calendario.pdf'),
(25, 19, 'woman.jpg');

-- --------------------------------------------------------

--
-- Estrutura da tabela `eventchecklists`
--

CREATE TABLE `eventchecklists` (
  `id` int NOT NULL,
  `finalized` tinyint(1) NOT NULL DEFAULT '1',
  `checklistJson` json NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Extraindo dados da tabela `eventchecklists`
--

INSERT INTO `eventchecklists` (`id`, `finalized`, `checklistJson`) VALUES
(5, 0, '{\"preevent\": [{\"type\": \"check\", \"title\": \"O corpo docente já está confirmado?\", \"value\": \"0\", \"optional\": true, \"responsibleUser\": 1}, {\"type\": \"check\", \"title\": \"Já existe um cronograma?\", \"optional\": false, \"responsibleUser\": 1}, {\"type\": \"check\", \"title\": \"O local já está reservado?\", \"value\": \"1\", \"optional\": false, \"responsibleUser\": 1}, {\"type\": \"text\", \"title\": \"Local onde ocorrerá\", \"value\": \"Plenário\", \"optional\": false, \"responsibleUser\": 0}, {\"type\": \"checkListWithResponsibleUser\", \"title\": \"Material Gráfico e Divulgação - Mídia impressa\", \"optional\": false, \"checkList\": [{\"name\": \"Cartaz\", \"optional\": true, \"responsibleUser\": 1}, {\"name\": \"Ofício\", \"optional\": true, \"responsibleUser\": 1}]}, {\"type\": \"checkListWithResponsibleUser\", \"title\": \"Material Gráfico e Divulgação - Mídia digital\", \"optional\": false, \"checkList\": [{\"name\": \"Cartaz\", \"optional\": true, \"responsibleUser\": 1}, {\"name\": \"E-mail\", \"optional\": true, \"responsibleUser\": 1}, {\"name\": \"Site\", \"optional\": true, \"responsibleUser\": 1}, {\"name\": \"Redes Sociais\", \"optional\": true, \"responsibleUser\": 1}]}, {\"type\": \"checkList\", \"title\": \"Foi feita a reserva dos equipamentos?\", \"optional\": false, \"checkList\": [{\"name\": \"Notebook\", \"optional\": true}, {\"name\": \"Data Show\", \"optional\": true}, {\"name\": \"Telão\", \"optional\": true}, {\"name\": \"Som\", \"optional\": true}, {\"name\": \"Microfone\", \"optional\": true}, {\"name\": \"Filmagem\", \"optional\": true}, {\"name\": \"Lousa\", \"optional\": true}, {\"name\": \"Canetas para lousa\", \"optional\": true}, {\"name\": \"Apagador\", \"optional\": true}], \"responsibleUser\": 1}, {\"type\": \"check\", \"title\": \"Memorando comunicando os Vereadores e Servidores?\", \"optional\": false, \"responsibleUser\": 1}, {\"type\": \"check\", \"title\": \"Os e-mails de confirmação das inscrições deferidas já foram encaminhados?\", \"optional\": false, \"responsibleUser\": 1}, {\"type\": \"check\", \"title\": \"O serviço de limpeza e copa já foi solicitado?\", \"optional\": false, \"responsibleUser\": 1}, {\"type\": \"checkList\", \"title\": \"Será utilizado material de apoio?\", \"optional\": false, \"checkList\": [{\"name\": \"Pasta\", \"optional\": true}, {\"name\": \"Caneta\", \"optional\": true}, {\"name\": \"Bloco de papel\", \"optional\": true}, {\"name\": \"Programação do evento\", \"optional\": true}, {\"name\": \"Folder da Escola\", \"optional\": true}, {\"name\": \"QR Code para lista de presença\", \"optional\": true}], \"responsibleUser\": 1}, {\"type\": \"check\", \"title\": \"Necessitará de transporte ou translado?\", \"optional\": false, \"responsibleUser\": 1}], \"postevent\": [{\"type\": \"check\", \"title\": \"O memorando para a realização do empenho já foi elaborado e enviado?\", \"value\": \"1\", \"optional\": false, \"responsibleUser\": 1}]}'),
(6, 0, '{\"eventdate\": [{\"type\": \"checkList\", \"title\": \"Verificar serviços de limpeza e copa:\", \"optional\": false, \"checkList\": [{\"name\": \"Plenário ou sala estão limpos\", \"optional\": true}, {\"name\": \"Banheiros higienizados\", \"optional\": true}, {\"name\": \"Filtro de água está cheio\", \"optional\": true}, {\"name\": \"Copos plásticos\", \"optional\": true}, {\"name\": \"Copo de água para os professores e palestrantes\", \"optional\": true}, {\"name\": \"Chá e café\", \"optional\": true}], \"responsibleUser\": 1}, {\"type\": \"checkList\", \"title\": \"Conferir materiais e equipamentos:\", \"optional\": false, \"checkList\": [{\"name\": \"Som\", \"optional\": true}, {\"name\": \"Microfone\", \"optional\": true}, {\"name\": \"Notebook\", \"optional\": true}, {\"name\": \"Data Show\", \"optional\": true}, {\"name\": \"Lousa\", \"optional\": true}, {\"name\": \"Canetas para quadro branco\", \"optional\": true}, {\"name\": \"Apagador\", \"optional\": true}, {\"name\": \"Telão\", \"optional\": true}, {\"name\": \"Filmagem\", \"optional\": true}], \"responsibleUser\": 1}, {\"type\": \"checkList\", \"title\": \"Acolhimento:\", \"optional\": false, \"checkList\": [{\"name\": \"Recepcionar os participantes\", \"optional\": true}, {\"name\": \"Observar se há algum participante que ainda não tenha efetuado a inscrição e orientar para o preenchimento da inscrição tardia\", \"optional\": true}, {\"name\": \"Passar a lista de presença\", \"optional\": true}, {\"name\": \"Entregar material de apoio\", \"optional\": true}], \"responsibleUser\": 1}, {\"type\": \"checkListWithResponsibleUser\", \"title\": \"Mídia:\", \"optional\": false, \"checkList\": [{\"name\": \"Fotografar\", \"optional\": true, \"responsibleUser\": 1}, {\"name\": \"Filmar\", \"optional\": true, \"responsibleUser\": 1}, {\"name\": \"Redes Sociais\", \"optional\": true, \"responsibleUser\": 1}]}, {\"type\": \"checkList\", \"title\": \"Cerimonial:\", \"optional\": false, \"checkList\": [{\"name\": \"Cuidar do translado das autoridades, professores e palestrantes\", \"optional\": true}, {\"name\": \"Recepcionar os convidados\", \"optional\": true}, {\"name\": \"Organizar o mini currículo dos convidados para apresentação\", \"optional\": true}, {\"name\": \"Zelar pela manutenção dos horários fixados\", \"optional\": true}, {\"name\": \"Abertura e encerramento dos trabalhos\", \"optional\": true}], \"responsibleUser\": 1}]}'),
(14, 1, '{\"preevent\": [{\"type\": \"check\", \"title\": \"O corpo docente já está confirmado?\", \"value\": \"1\", \"optional\": false, \"responsibleUser\": 1}, {\"type\": \"check\", \"title\": \"Já existe um cronograma?\", \"value\": \"1\", \"optional\": false, \"responsibleUser\": 1}, {\"type\": \"check\", \"title\": \"O local já está reservado?\", \"value\": \"1\", \"optional\": false, \"responsibleUser\": 1}, {\"type\": \"text\", \"title\": \"Local onde ocorrerá\", \"value\": \"Sala de aula1\", \"optional\": false, \"responsibleUser\": 0}, {\"type\": \"checkListWithResponsibleUser\", \"title\": \"Material Gráfico e Divulgação - Mídia impressa\", \"optional\": false, \"checkList\": [{\"name\": \"Cartaz\", \"value\": \"2\", \"optional\": true, \"responsibleUser\": 1}, {\"name\": \"Ofício\", \"value\": \"2\", \"optional\": true, \"responsibleUser\": 1}]}, {\"type\": \"checkListWithResponsibleUser\", \"title\": \"Material Gráfico e Divulgação - Mídia digital\", \"optional\": false, \"checkList\": [{\"name\": \"Cartaz\", \"value\": \"1\", \"optional\": true, \"responsibleUser\": 1}, {\"name\": \"E-mail\", \"value\": \"1\", \"optional\": true, \"responsibleUser\": 1}, {\"name\": \"Site\", \"value\": \"1\", \"optional\": true, \"responsibleUser\": 1}, {\"name\": \"Redes Sociais\", \"value\": \"1\", \"optional\": true, \"responsibleUser\": 1}]}, {\"type\": \"checkList\", \"title\": \"Foi feita a reserva dos equipamentos?\", \"optional\": false, \"checkList\": [{\"name\": \"Notebook\", \"value\": \"1\", \"optional\": true}, {\"name\": \"Data Show\", \"value\": \"1\", \"optional\": true}, {\"name\": \"Telão\", \"value\": \"1\", \"optional\": true}, {\"name\": \"Som\", \"value\": \"1\", \"optional\": true}, {\"name\": \"Microfone\", \"value\": \"1\", \"optional\": true}, {\"name\": \"Filmagem\", \"value\": \"1\", \"optional\": true}, {\"name\": \"Lousa\", \"value\": \"1\", \"optional\": true}, {\"name\": \"Canetas para lousa\", \"value\": \"1\", \"optional\": true}, {\"name\": \"Apagador\", \"value\": \"1\", \"optional\": true}], \"responsibleUser\": 1}, {\"type\": \"check\", \"title\": \"Memorando comunicando os Vereadores e Servidores?\", \"value\": \"1\", \"optional\": false, \"responsibleUser\": 1}, {\"type\": \"check\", \"title\": \"Os e-mails de confirmação das inscrições deferidas já foram encaminhados?\", \"value\": \"1\", \"optional\": false, \"responsibleUser\": 1}, {\"type\": \"check\", \"title\": \"O serviço de limpeza e copa já foi solicitado?\", \"value\": \"1\", \"optional\": false, \"responsibleUser\": 1}, {\"type\": \"checkList\", \"title\": \"Será utilizado material de apoio?\", \"optional\": false, \"checkList\": [{\"name\": \"Pasta\", \"value\": \"1\", \"optional\": true}, {\"name\": \"Caneta\", \"value\": \"1\", \"optional\": true}, {\"name\": \"Bloco de papel\", \"value\": \"1\", \"optional\": true}, {\"name\": \"Programação do evento\", \"value\": \"1\", \"optional\": true}, {\"name\": \"Folder da Escola\", \"value\": \"1\", \"optional\": true}, {\"name\": \"QR Code para lista de presença\", \"value\": \"1\", \"optional\": true}], \"responsibleUser\": 1}, {\"type\": \"check\", \"title\": \"Necessitará de transporte ou translado?\", \"value\": \"1\", \"optional\": false, \"responsibleUser\": 1}], \"postevent\": [{\"type\": \"check\", \"title\": \"O memorando para a realização do empenho já foi elaborado e enviado?\", \"value\": \"1\", \"optional\": false, \"responsibleUser\": 1}]}'),
(15, 0, '{\"eventdate\": [{\"type\": \"checkList\", \"title\": \"Verificar serviços de limpeza e copa:\", \"optional\": false, \"checkList\": [{\"name\": \"Plenário ou sala estão limpos\", \"optional\": true}, {\"name\": \"Banheiros higienizados\", \"optional\": true}, {\"name\": \"Filtro de água está cheio\", \"optional\": true}, {\"name\": \"Copos plásticos\", \"optional\": true}, {\"name\": \"Copo de água para os professores e palestrantes\", \"optional\": true}, {\"name\": \"Chá e café\", \"optional\": true}], \"responsibleUser\": 1}, {\"type\": \"checkList\", \"title\": \"Conferir materiais e equipamentos:\", \"optional\": false, \"checkList\": [{\"name\": \"Som\", \"optional\": true}, {\"name\": \"Microfone\", \"optional\": true}, {\"name\": \"Notebook\", \"optional\": true}, {\"name\": \"Data Show\", \"optional\": true}, {\"name\": \"Lousa\", \"optional\": true}, {\"name\": \"Canetas para quadro branco\", \"optional\": true}, {\"name\": \"Apagador\", \"optional\": true}, {\"name\": \"Telão\", \"optional\": true}, {\"name\": \"Filmagem\", \"optional\": true}], \"responsibleUser\": 1}, {\"type\": \"checkList\", \"title\": \"Acolhimento:\", \"optional\": false, \"checkList\": [{\"name\": \"Recepcionar os participantes\", \"optional\": true}, {\"name\": \"Observar se há algum participante que ainda não tenha efetuado a inscrição e orientar para o preenchimento da inscrição tardia\", \"optional\": true}, {\"name\": \"Passar a lista de presença\", \"optional\": true}, {\"name\": \"Entregar material de apoio\", \"optional\": true}], \"responsibleUser\": 1}, {\"type\": \"checkListWithResponsibleUser\", \"title\": \"Mídia:\", \"optional\": false, \"checkList\": [{\"name\": \"Fotografar\", \"optional\": true, \"responsibleUser\": 1}, {\"name\": \"Filmar\", \"optional\": true, \"responsibleUser\": 1}, {\"name\": \"Redes Sociais\", \"optional\": true, \"responsibleUser\": 1}]}, {\"type\": \"checkList\", \"title\": \"Cerimonial:\", \"optional\": false, \"checkList\": [{\"name\": \"Cuidar do translado das autoridades, professores e palestrantes\", \"optional\": true}, {\"name\": \"Recepcionar os convidados\", \"optional\": true}, {\"name\": \"Organizar o mini currículo dos convidados para apresentação\", \"optional\": true}, {\"name\": \"Zelar pela manutenção dos horários fixados\", \"optional\": true}, {\"name\": \"Abertura e encerramento dos trabalhos\", \"optional\": true}], \"responsibleUser\": 1}]}'),
(27, 0, '{\"eventdate\": [{\"type\": \"checkList\", \"title\": \"Verificar serviços de limpeza e copa:\", \"optional\": false, \"checkList\": [{\"name\": \"Plenário ou sala estão limpos\", \"optional\": true}, {\"name\": \"Banheiros higienizados\", \"optional\": true}, {\"name\": \"Filtro de água está cheio\", \"optional\": true}, {\"name\": \"Copos plásticos\", \"optional\": true}, {\"name\": \"Copo de água para os professores e palestrantes\", \"optional\": true}, {\"name\": \"Chá e café\", \"optional\": true}], \"responsibleUser\": 1}, {\"type\": \"checkList\", \"title\": \"Conferir materiais e equipamentos:\", \"optional\": false, \"checkList\": [{\"name\": \"Som\", \"optional\": true}, {\"name\": \"Microfone\", \"optional\": true}, {\"name\": \"Notebook\", \"optional\": true}, {\"name\": \"Data Show\", \"optional\": true}, {\"name\": \"Lousa\", \"optional\": true}, {\"name\": \"Canetas para quadro branco\", \"optional\": true}, {\"name\": \"Apagador\", \"optional\": true}, {\"name\": \"Telão\", \"optional\": true}, {\"name\": \"Filmagem\", \"optional\": true}], \"responsibleUser\": 1}, {\"type\": \"checkList\", \"title\": \"Acolhimento:\", \"optional\": false, \"checkList\": [{\"name\": \"Recepcionar os participantes\", \"optional\": true}, {\"name\": \"Observar se há algum participante que ainda não tenha efetuado a inscrição e orientar para o preenchimento da inscrição tardia\", \"optional\": true}, {\"name\": \"Passar a lista de presença\", \"optional\": true}, {\"name\": \"Entregar material de apoio\", \"optional\": true}], \"responsibleUser\": 1}, {\"type\": \"checkListWithResponsibleUser\", \"title\": \"Mídia:\", \"optional\": false, \"checkList\": [{\"name\": \"Fotografar\", \"optional\": true, \"responsibleUser\": 1}, {\"name\": \"Filmar\", \"optional\": true, \"responsibleUser\": 1}, {\"name\": \"Redes Sociais\", \"optional\": true, \"responsibleUser\": 1}]}, {\"type\": \"checkList\", \"title\": \"Cerimonial:\", \"optional\": false, \"checkList\": [{\"name\": \"Cuidar do translado das autoridades, professores e palestrantes\", \"optional\": true}, {\"name\": \"Recepcionar os convidados\", \"optional\": true}, {\"name\": \"Organizar o mini currículo dos convidados para apresentação\", \"optional\": true}, {\"name\": \"Zelar pela manutenção dos horários fixados\", \"optional\": true}, {\"name\": \"Abertura e encerramento dos trabalhos\", \"optional\": true}], \"responsibleUser\": 1}]}'),
(28, 0, '{\"preevent\": [{\"type\": \"check\", \"title\": \"O corpo docente já está confirmado?\", \"optional\": false, \"responsibleUser\": 1}, {\"type\": \"check\", \"title\": \"Já existe um cronograma?\", \"optional\": false, \"responsibleUser\": 1}, {\"type\": \"check\", \"title\": \"O local já está reservado?\", \"optional\": false, \"responsibleUser\": 1}, {\"type\": \"text\", \"title\": \"Local onde ocorrerá\", \"optional\": false, \"responsibleUser\": 0}, {\"type\": \"checkListWithResponsibleUser\", \"title\": \"Material Gráfico e Divulgação - Mídia impressa\", \"optional\": false, \"checkList\": [{\"name\": \"Cartaz\", \"optional\": true, \"responsibleUser\": 1}, {\"name\": \"Ofício\", \"optional\": true, \"responsibleUser\": 1}]}, {\"type\": \"checkListWithResponsibleUser\", \"title\": \"Material Gráfico e Divulgação - Mídia digital\", \"optional\": false, \"checkList\": [{\"name\": \"Cartaz\", \"optional\": true, \"responsibleUser\": 1}, {\"name\": \"E-mail\", \"optional\": true, \"responsibleUser\": 1}, {\"name\": \"Site\", \"optional\": true, \"responsibleUser\": 1}, {\"name\": \"Redes Sociais\", \"optional\": true, \"responsibleUser\": 1}]}, {\"type\": \"checkList\", \"title\": \"Foi feita a reserva dos equipamentos?\", \"optional\": false, \"checkList\": [{\"name\": \"Notebook\", \"optional\": true}, {\"name\": \"Data Show\", \"optional\": true}, {\"name\": \"Telão\", \"optional\": true}, {\"name\": \"Som\", \"optional\": true}, {\"name\": \"Microfone\", \"optional\": true}, {\"name\": \"Filmagem\", \"optional\": true}, {\"name\": \"Lousa\", \"optional\": true}, {\"name\": \"Canetas para lousa\", \"optional\": true}, {\"name\": \"Apagador\", \"optional\": true}], \"responsibleUser\": 1}, {\"type\": \"check\", \"title\": \"Memorando comunicando os Vereadores e Servidores?\", \"optional\": false, \"responsibleUser\": 1}, {\"type\": \"check\", \"title\": \"Os e-mails de confirmação das inscrições deferidas já foram encaminhados?\", \"optional\": false, \"responsibleUser\": 1}, {\"type\": \"check\", \"title\": \"O serviço de limpeza e copa já foi solicitado?\", \"optional\": false, \"responsibleUser\": 1}, {\"type\": \"checkList\", \"title\": \"Será utilizado material de apoio?\", \"optional\": false, \"checkList\": [{\"name\": \"Pasta\", \"optional\": true}, {\"name\": \"Caneta\", \"optional\": true}, {\"name\": \"Bloco de papel\", \"optional\": true}, {\"name\": \"Programação do evento\", \"optional\": true}, {\"name\": \"Folder da Escola\", \"optional\": true}, {\"name\": \"QR Code para lista de presença\", \"optional\": true}], \"responsibleUser\": 1}, {\"type\": \"check\", \"title\": \"Necessitará de transporte ou translado?\", \"optional\": false, \"responsibleUser\": 1}], \"postevent\": [{\"type\": \"check\", \"title\": \"O memorando para a realização do empenho já foi elaborado e enviado?\", \"optional\": false, \"responsibleUser\": 1}]}');

-- --------------------------------------------------------

--
-- Estrutura da tabela `eventdates`
--

CREATE TABLE `eventdates` (
  `id` int UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `beginTime` time NOT NULL,
  `endTime` time DEFAULT NULL,
  `name` varchar(120) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `presenceListNeeded` tinyint(1) DEFAULT NULL,
  `presenceListPassword` varchar(80) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `locationId` int DEFAULT NULL,
  `locationInfosJson` json DEFAULT NULL,
  `eventId` int UNSIGNED NOT NULL,
  `checklistId` int DEFAULT NULL,
  `calendarStyleJson` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='Tabela de datas de eventos';

--
-- Extraindo dados da tabela `eventdates`
--

INSERT INTO `eventdates` (`id`, `date`, `beginTime`, `endTime`, `name`, `presenceListNeeded`, `presenceListPassword`, `locationId`, `locationInfosJson`, `eventId`, `checklistId`, `calendarStyleJson`) VALUES
(3, '2021-09-13', '21:00:00', '22:30:00', 'Aula 1', 1, '1234', 3, '{\"url\": \"http://aaaaccccccccbbbbbbbbbhhhhhhbbbbbbbbbb.com.br/\", \"infos\": \"\"}', 2, NULL, NULL),
(4, '2021-09-14', '21:00:00', '22:00:00', 'Aula 2', 1, '1234', NULL, '{\"url\": \"\", \"infos\": \"\"}', 2, NULL, NULL),
(6, '2021-09-15', '21:00:00', '22:30:00', 'Aula 3', 1, '1234', NULL, '{\"url\": \"http://aaaaccccccccbbbbbbbbbhhhhhhbbbbbbbbbb.com.br/\", \"infos\": \"\"}', 2, NULL, NULL),
(7, '2021-09-16', '18:00:00', '19:00:00', 'Aula 4', 1, '1234', NULL, '{\"url\": \"\", \"infos\": \"\"}', 2, NULL, NULL),
(9, '2021-10-01', '19:00:00', '20:00:00', '', 1, '1234', NULL, '{\"url\": \"\", \"infos\": \"\"}', 5, 6, NULL),
(10, '2022-09-20', '18:30:00', '19:30:00', 'Aula 5', 1, '1234', NULL, '{\"url\": \"\", \"infos\": \"\"}', 2, NULL, NULL),
(13, '2021-09-16', '13:30:00', '14:30:00', 'Palestra', 1, '0611', NULL, NULL, 6, NULL, NULL),
(14, '2021-09-16', '19:00:00', '20:00:00', 'Encerramento', 0, '5609', NULL, NULL, 6, NULL, NULL),
(15, '2021-09-16', '14:30:00', '15:30:00', 'Palestra', 1, '1988', NULL, NULL, 6, NULL, NULL),
(17, '2021-10-02', '13:16:45', '14:16:47', '', 1, '1234', NULL, '{\"url\": \"\", \"infos\": \"\"}', 10, NULL, NULL),
(21, '2022-05-17', '21:00:00', '22:00:00', 'Live', 1, '5717', NULL, '{\"url\": \"http://aaaaccccccccbbbbbbbbbhhhhhhbbbbbbbbbb.com.br\", \"infos\": \"tststststs fghgff  ghfghgf fghjghj ghjhgj ghjghj tststststs fghgff  ghfghgf fghjghj ghjhgj ghjghj tststststs fghgff  ghfghgf fghjghj ghjhgj ghjghj tststststs fghgff  ghfghgf fghjghj ghjhgj ghjghj \"}', 14, 15, NULL),
(22, '2022-06-18', '16:00:00', '17:00:00', 'Teste 2', 1, '5484', NULL, '{\"url\": \"\", \"infos\": \"\"}', 14, NULL, NULL),
(23, '2022-06-04', '18:00:00', '19:00:00', '', 1, '7084', NULL, '{\"url\": \"\", \"infos\": \"\"}', 5, NULL, NULL),
(34, '2022-10-24', '10:00:00', '11:00:00', 'hjhgjhgj', NULL, '4130', NULL, '{\"url\": \"\", \"infos\": \"\"}', 18, NULL, NULL),
(35, '2022-10-25', '10:00:00', '11:00:00', 'gfghg11', 1, '1812', 4, '{\"url\": \"fgh\", \"infos\": \"ghgfhfh\"}', 19, 24, NULL),
(37, '2022-11-11', '11:11:11', '12:11:11', 'fgjhgjghjgh', 1, '3174', NULL, '{\"url\": \"\", \"infos\": \"\"}', 21, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `eventdatesprofessors`
--

CREATE TABLE `eventdatesprofessors` (
  `eventDateId` int UNSIGNED NOT NULL,
  `professorId` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='Lista de professores para cada data de evento';

--
-- Extraindo dados da tabela `eventdatesprofessors`
--

INSERT INTO `eventdatesprofessors` (`eventDateId`, `professorId`) VALUES
(3, 2),
(4, 1),
(6, 1),
(7, 2),
(9, 2),
(10, 2),
(21, 1),
(21, 2),
(23, 1),
(17, 1),
(35, 2),
(35, 4),
(37, 4);

-- --------------------------------------------------------

--
-- Estrutura da tabela `eventdatestraits`
--

CREATE TABLE `eventdatestraits` (
  `eventDateId` int UNSIGNED NOT NULL,
  `traitId` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Extraindo dados da tabela `eventdatestraits`
--

INSERT INTO `eventdatestraits` (`eventDateId`, `traitId`) VALUES
(6, 3),
(37, 1),
(37, 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `eventlocations`
--

CREATE TABLE `eventlocations` (
  `id` int NOT NULL,
  `name` varchar(280) NOT NULL,
  `type` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `calendarInfoBoxStyleJson` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='Locais de ocorrência dos eventos';

--
-- Extraindo dados da tabela `eventlocations`
--

INSERT INTO `eventlocations` (`id`, `name`, `type`, `calendarInfoBoxStyleJson`) VALUES
(3, 'Plenário Bemvindo Moreira Nery - Câmara Municipal de Itapevi', 'physical', '{\"textColor\": \"#cc8100\", \"backgroundColor\": \"#ffd080\"}'),
(4, 'Plataforma Zoom', 'online', '{\"textColor\": \"#13a03d\", \"backgroundColor\": \"#aafdfb\"}');

-- --------------------------------------------------------

--
-- Estrutura da tabela `events`
--

CREATE TABLE `events` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(120) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'Evento sem nome',
  `typeId` int NOT NULL,
  `subscriptionListNeeded` tinyint(1) DEFAULT NULL,
  `subscriptionListOpeningDate` date DEFAULT NULL,
  `subscriptionListClosureDate` date DEFAULT NULL,
  `maxSubscriptionNumber` int DEFAULT NULL,
  `allowLateSubscriptions` tinyint(1) NOT NULL DEFAULT '0',
  `posterImageAttachmentFileName` varchar(120) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `responsibleForTheEvent` varchar(120) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `customInfosJson` json DEFAULT NULL,
  `moreInfos` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `certificateText` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `certificateBgFile` varchar(255) DEFAULT NULL,
  `checklistId` int DEFAULT NULL,
  `surveyTemplateId` int UNSIGNED DEFAULT NULL,
  `subscriptionTemplateId` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='Tabela de eventos';

--
-- Extraindo dados da tabela `events`
--

INSERT INTO `events` (`id`, `name`, `typeId`, `subscriptionListNeeded`, `subscriptionListOpeningDate`, `subscriptionListClosureDate`, `maxSubscriptionNumber`, `allowLateSubscriptions`, `posterImageAttachmentFileName`, `responsibleForTheEvent`, `customInfosJson`, `moreInfos`, `certificateText`, `certificateBgFile`, `checklistId`, `surveyTemplateId`, `subscriptionTemplateId`) VALUES
(2, 'Curso YYYY231', 1, 1, NULL, '2022-09-21', 102, 1, 'EPI_O-CIDADAO-NA-COUNCIACAO-PUBLICA.jpeg', 'Qualquer pessoa', '[]', 'Teste Teste\r\n\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Sed volutpat nisi id sem interdum vulputate. Fusce ante ex, sagittis eget aliquam vitae, euismod eget tellus. Maecenas mattis augue quam, a posuere est sollicitudin et. Nulla placerat ornare eleifend. Phasellus tempor malesuada libero a auctor. Fusce tincidunt efficitur vulputate. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quis augue sed nisl ornare pretium. Curabitur eget dignissim nulla. Cras sed accumsan enim. Phasellus a dictum erat, eu ultricies nunc.\r\n\r\nMauris elementum luctus nulla, a commodo eros imperdiet ut. Proin in laoreet felis. Suspendisse sed ornare massa. Duis at urna in lectus aliquet blandit. Nulla facilisi. Cras commodo ante et risus laoreet malesuada. Etiam rutrum porttitor ipsum in dignissim. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Aliquam in risus lobortis, tempor mi eu, molestie massa.\r\n\r\nPhasellus vulputate mi at nisi tempus, eu lobortis elit luctus. Maecenas egestas scelerisque diam, at sagittis turpis. Suspendisse feugiat erat at condimentum vehicula. Curabitur consectetur, leo non bibendum hendrerit, risus mauris eleifend felis, eget tincidunt mi mi iaculis ex. Cras at massa convallis, consectetur lorem vitae, viverra mauris. Aliquam mollis at augue sit amet pretium. Maecenas consectetur diam a libero varius, vitae tincidunt diam sodales. Sed sed hendrerit erat. Nulla id massa enim.', 'Participou do Curso \"YYYY23\", on-line, tendo como docente a Professora Dr.ª Patrícia Guimarães Gil, promovido pela Câmara Municipal de Itapevi por meio da Escola do Parlamento \"Doutor Osmar de Souza\", no dia 13 de setembro de 2021, às 12h, com carga horária total de 6,5 horas.', 'certbg2021.jpg', NULL, 1, 2),
(5, 'Lorem ãpsum dolor sit amet123', 1, 1, NULL, '2022-06-13', 81, 1, NULL, '-----', '[]', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin ac dapibus libero. Quisque faucibus diam turpis, eu congue diam elementum id. Pellentesque suscipit malesuada ipsum, quis consectetur elit porttitor vel. Duis mi tellus, egestas ac gravida ut, bibendum ac augue. Sed tortor magna, faucibus a velit ac, feugiat malesuada lacus. Integer condimentum diam nec commodo maximus. In feugiat metus fermentum massa dignissim vestibulum. Quisque viverra dapibus nunc, eget gravida mi imperdiet ut. Integer mollis ex non lorem tempor gravida.1111\r\n\r\nhjghjghjhg', NULL, 'tytgfy', 5, NULL, 1),
(6, 'Palestra Teste11', 2, 0, NULL, '2021-09-10', 80, 1, NULL, '-----', NULL, '', 'Participou da Palestra \"O cidadão na comunicação pública\", on-line, tendo como palestrante a Professora Dr.ª Patrícia Guimarães Gil, promovida pela Câmara Municipal de Itapevi por meio da Escola do Parlamento \"Doutor Osmar de Souza\", no dia 16 de setembro de 2021, às 13h30, com carga horária total de 3 horas.\r\n', 'certbg2021.jpg', NULL, NULL, 1),
(7, 'Curso teste teste', 1, 0, NULL, NULL, 80, 1, NULL, '', NULL, '', 'Certificado teste', NULL, NULL, NULL, 1),
(8, 'Curso teste teste', 1, 0, NULL, NULL, 80, 1, NULL, '', NULL, '', 'Certificado teste', NULL, NULL, NULL, 1),
(10, 'Curso teste teste', 1, 0, NULL, NULL, 80, 1, NULL, NULL, '[]', NULL, 'Certificado teste', 'certbg2021.jpg', NULL, NULL, 1),
(14, 'Live teste teste', 3, 0, NULL, NULL, 80, 1, NULL, NULL, '[{\"label\": \"Objetivo\", \"value\": \"ffff\"}, {\"label\": \"Público-alvo\", \"value\": \"fffdddd\"}]', NULL, NULL, 'certbg2021.jpg', 14, 1, 1),
(21, 'DataEntity teste', 1, 0, NULL, '2022-11-11', 80, 0, NULL, NULL, '[]', NULL, NULL, 'certbg2021.jpg', 28, NULL, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `eventsurveys`
--

CREATE TABLE `eventsurveys` (
  `id` int UNSIGNED NOT NULL,
  `eventId` int UNSIGNED NOT NULL,
  `subscriptionId` int DEFAULT NULL,
  `studentEmail` varbinary(140) DEFAULT NULL,
  `surveyJson` json NOT NULL,
  `registrationDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='Tabela para as avaliações de evento preenchidas.';

--
-- Extraindo dados da tabela `eventsurveys`
--

INSERT INTO `eventsurveys` (`id`, `eventId`, `subscriptionId`, `studentEmail`, `surveyJson`, `registrationDate`) VALUES
(10, 2, 1, NULL, '{\"body\": [{\"type\": \"fiveStarRating\", \"title\": \"Nível do evento\", \"value\": \"5\", \"optional\": false}, {\"type\": \"fiveStarRating\", \"title\": \"Palestrantes/docentes\", \"value\": \"5\", \"optional\": false}, {\"type\": \"fiveStarRating\", \"title\": \"Duração do evento\", \"value\": \"4\", \"optional\": false}, {\"type\": \"fiveStarRating\", \"title\": \"Atendimento e orientação antes, depois do evento e durante\", \"value\": \"4\", \"optional\": false}, {\"type\": \"fiveStarRating\", \"title\": \"Material de Apoio (se houver)\", \"value\": \"\", \"optional\": true}, {\"type\": \"fiveStarRating\", \"title\": \"O evento atingiu o seu objetivo?\", \"value\": \"5\", \"optional\": false}, {\"type\": \"checkList\", \"title\": \"Como ficou sabendo do evento\", \"optional\": false, \"checkList\": [{\"name\": \"Site da Escola\", \"value\": \"1\"}, {\"name\": \"Site da Câmara\"}, {\"name\": \"Facebook\", \"value\": \"1\"}, {\"name\": \"Google\"}, {\"name\": \"E-mail\", \"value\": \"1\"}, {\"name\": \"Jornal\"}, {\"name\": \"Site de Notícias\"}, {\"name\": \"Amigos\"}, {\"name\": \"Cartaz\"}]}, {\"type\": \"shortText\", \"title\": \"Texto ao professor:\", \"value\": \"\", \"optional\": true}], \"foot\": [{\"type\": \"textArea\", \"title\": \"Sugestões\", \"value\": \"tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst tstst \", \"optional\": true}], \"head\": [{\"type\": \"yesNo\", \"title\": \"É a primeira vez que participa?\", \"value\": \"0\", \"optional\": false}, {\"type\": \"yesNo\", \"title\": \"Indicará os eventos da Escola?\", \"value\": \"1\", \"optional\": false}]}', '2022-06-19 17:06:23'),
(11, 2, 4, NULL, '{\"body\": [{\"type\": \"fiveStarRating\", \"title\": \"Nível do evento\", \"value\": \"4\", \"optional\": false}, {\"type\": \"fiveStarRating\", \"title\": \"Palestrantes/docentes\", \"value\": \"4\", \"optional\": false}, {\"type\": \"fiveStarRating\", \"title\": \"Duração do evento\", \"value\": \"3\", \"optional\": false}, {\"type\": \"fiveStarRating\", \"title\": \"Atendimento e orientação antes, depois do evento e durante\", \"value\": \"5\", \"optional\": false}, {\"type\": \"fiveStarRating\", \"title\": \"Material de Apoio (se houver)\", \"value\": \"5\", \"optional\": true}, {\"type\": \"fiveStarRating\", \"title\": \"O evento atingiu o seu objetivo?\", \"value\": \"4\", \"optional\": false}, {\"type\": \"checkList\", \"title\": \"Como ficou sabendo do evento\", \"optional\": false, \"checkList\": [{\"name\": \"Site da Escola\"}, {\"name\": \"Site da Câmara\"}, {\"name\": \"Facebook\"}, {\"name\": \"Google\"}, {\"name\": \"E-mail\"}, {\"name\": \"Jornal\", \"value\": \"1\"}, {\"name\": \"Site de Notícias\", \"value\": \"1\"}, {\"name\": \"Amigos\", \"value\": \"1\"}, {\"name\": \"Cartaz\"}]}], \"foot\": [{\"type\": \"textArea\", \"title\": \"Sugestões\", \"value\": \"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam vehicula eros sit amet mattis gravida. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Quisque congue hendrerit nisi, ac malesuada magna sagittis malesuada. Nullam volutpat, dui eget aliquet suscipit, tellus massa molestie sem, id finibus arcu augue vitae ante. Curabitur auctor eros eu orci sollicitudin fringilla. Curabitur sit amet arcu mollis augue hendrerit mollis. Donec pharetra diam suscipit, interdum purus et, dictum lacus. Maecenas non pharetra quam. Morbi vestibulum sapien commodo libero rutrum vehicula. Phasellus id venenatis ante. Interdum et malesuada fames ac ante ipsum primis in faucibus.\\r\\n\\r\\nInterdum et malesuada fames ac ante ipsum primis in faucibus. Suspendisse vel malesuada erat, sit amet commodo diam. Ut eu arcu eget lectus porttitor fermentum. Vivamus commodo nunc est. Duis a tincidunt mauris. Donec ut dui in neque egestas pretium a quis arcu. Aliquam dapibus felis eu augue iaculis, et cursus lectus dictum. Curabitur diam eros, porta quis interdum non, euismod at quam. Curabitur vel blandit elit, quis vestibulum metus.\", \"optional\": true}], \"head\": [{\"type\": \"yesNo\", \"title\": \"É a primeira vez que participa?\", \"value\": \"1\", \"optional\": false}, {\"type\": \"yesNo\", \"title\": \"Indicará os eventos da Escola?\", \"value\": \"1\", \"optional\": false}]}', '2022-06-19 18:48:09'),
(12, 2, 10, NULL, '{\"body\": [{\"type\": \"fiveStarRating\", \"title\": \"Nível do evento\", \"value\": \"4\", \"optional\": false}, {\"type\": \"fiveStarRating\", \"title\": \"Palestrantes/docentes\", \"value\": \"5\", \"optional\": false}, {\"type\": \"fiveStarRating\", \"title\": \"Duração do evento\", \"value\": \"4\", \"optional\": false}, {\"type\": \"fiveStarRating\", \"title\": \"Atendimento e orientação antes, depois do evento e durante\", \"value\": \"4\", \"optional\": false}, {\"type\": \"fiveStarRating\", \"title\": \"Material de Apoio (se houver)\", \"value\": \"\", \"optional\": true}, {\"type\": \"fiveStarRating\", \"title\": \"O evento atingiu o seu objetivo?\", \"value\": \"5\", \"optional\": false}, {\"type\": \"checkList\", \"title\": \"Como ficou sabendo do evento\", \"optional\": false, \"checkList\": [{\"name\": \"Site da Escola\", \"value\": \"1\"}, {\"name\": \"Site da Câmara\"}, {\"name\": \"Facebook\"}, {\"name\": \"Google\"}, {\"name\": \"E-mail\", \"value\": \"1\"}, {\"name\": \"Jornal\"}, {\"name\": \"Site de Notícias\"}, {\"name\": \"Amigos\"}, {\"name\": \"Cartaz\"}]}], \"foot\": [{\"type\": \"textArea\", \"title\": \"Sugestões\", \"value\": \"teste teste teste\", \"optional\": true}], \"head\": [{\"type\": \"yesNo\", \"title\": \"É a primeira vez que participa?\", \"value\": \"1\", \"optional\": false}, {\"type\": \"yesNo\", \"title\": \"Indicará os eventos da Escola?\", \"value\": \"1\", \"optional\": false}]}', '2022-09-20 19:35:15');

-- --------------------------------------------------------

--
-- Estrutura da tabela `eventworkplanattachments`
--

CREATE TABLE `eventworkplanattachments` (
  `id` int UNSIGNED NOT NULL,
  `workPlanId` int UNSIGNED NOT NULL,
  `fileName` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Registro de anexos privados de eventos.';

--
-- Extraindo dados da tabela `eventworkplanattachments`
--

INSERT INTO `eventworkplanattachments` (`id`, `workPlanId`, `fileName`) VALUES
(3, 1, 'Seminário de Eleições 2022.pdf');

-- --------------------------------------------------------

--
-- Estrutura da tabela `eventworkplans`
--

CREATE TABLE `eventworkplans` (
  `id` int UNSIGNED NOT NULL,
  `eventId` int UNSIGNED NOT NULL,
  `programName` varchar(255) DEFAULT NULL,
  `targetAudience` varchar(255) DEFAULT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `resources` varchar(255) DEFAULT NULL,
  `coordinators` varchar(255) DEFAULT NULL,
  `team` varchar(255) DEFAULT NULL,
  `assocTeam` varchar(255) DEFAULT NULL,
  `legalSubstantiation` varchar(255) DEFAULT NULL,
  `eventObjective` varchar(255) DEFAULT NULL,
  `specificObjective` varchar(255) DEFAULT NULL,
  `manualCertificatesInfos` varchar(280) DEFAULT NULL,
  `observations` varchar(300) DEFAULT NULL,
  `eventDescription` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='Tabela para os planos de trabalho dos eventos';

--
-- Extraindo dados da tabela `eventworkplans`
--

INSERT INTO `eventworkplans` (`id`, `eventId`, `programName`, `targetAudience`, `duration`, `resources`, `coordinators`, `team`, `assocTeam`, `legalSubstantiation`, `eventObjective`, `specificObjective`, `manualCertificatesInfos`, `observations`, `eventDescription`) VALUES
(1, 14, 'aaa111222', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 21, 'ghjgh', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `jsontemplates`
--

CREATE TABLE `jsontemplates` (
  `type` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `id` int NOT NULL,
  `name` varchar(140) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `templateJson` json NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

--
-- Extraindo dados da tabela `jsontemplates`
--

INSERT INTO `jsontemplates` (`type`, `id`, `name`, `templateJson`) VALUES
('eventchecklist', 1, 'Checklist Geral', '{\"preevent\": [{\"type\": \"check\", \"title\": \"O corpo docente já está confirmado?\", \"optional\": false, \"responsibleUser\": 1}, {\"type\": \"check\", \"title\": \"Já existe um cronograma?\", \"optional\": false, \"responsibleUser\": 1}, {\"type\": \"check\", \"title\": \"O local já está reservado?\", \"optional\": false, \"responsibleUser\": 1}, {\"type\": \"text\", \"title\": \"Local onde ocorrerá\", \"optional\": false, \"responsibleUser\": 0}, {\"type\": \"checkListWithResponsibleUser\", \"title\": \"Material Gráfico e Divulgação - Mídia impressa\", \"optional\": false, \"checkList\": [{\"name\": \"Cartaz\", \"optional\": true, \"responsibleUser\": 1}, {\"name\": \"Ofício\", \"optional\": true, \"responsibleUser\": 1}]}, {\"type\": \"checkListWithResponsibleUser\", \"title\": \"Material Gráfico e Divulgação - Mídia digital\", \"optional\": false, \"checkList\": [{\"name\": \"Cartaz\", \"optional\": true, \"responsibleUser\": 1}, {\"name\": \"E-mail\", \"optional\": true, \"responsibleUser\": 1}, {\"name\": \"Site\", \"optional\": true, \"responsibleUser\": 1}, {\"name\": \"Redes Sociais\", \"optional\": true, \"responsibleUser\": 1}]}, {\"type\": \"checkList\", \"title\": \"Foi feita a reserva dos equipamentos?\", \"optional\": false, \"checkList\": [{\"name\": \"Notebook\", \"optional\": true}, {\"name\": \"Data Show\", \"optional\": true}, {\"name\": \"Telão\", \"optional\": true}, {\"name\": \"Som\", \"optional\": true}, {\"name\": \"Microfone\", \"optional\": true}, {\"name\": \"Filmagem\", \"optional\": true}, {\"name\": \"Lousa\", \"optional\": true}, {\"name\": \"Canetas para lousa\", \"optional\": true}, {\"name\": \"Apagador\", \"optional\": true}], \"responsibleUser\": 1}, {\"type\": \"check\", \"title\": \"Memorando comunicando os Vereadores e Servidores?\", \"optional\": false, \"responsibleUser\": 1}, {\"type\": \"check\", \"title\": \"Os e-mails de confirmação das inscrições deferidas já foram encaminhados?\", \"optional\": false, \"responsibleUser\": 1}, {\"type\": \"check\", \"title\": \"O serviço de limpeza e copa já foi solicitado?\", \"optional\": false, \"responsibleUser\": 1}, {\"type\": \"checkList\", \"title\": \"Será utilizado material de apoio?\", \"optional\": false, \"checkList\": [{\"name\": \"Pasta\", \"optional\": true}, {\"name\": \"Caneta\", \"optional\": true}, {\"name\": \"Bloco de papel\", \"optional\": true}, {\"name\": \"Programação do evento\", \"optional\": true}, {\"name\": \"Folder da Escola\", \"optional\": true}, {\"name\": \"QR Code para lista de presença\", \"optional\": true}], \"responsibleUser\": 1}, {\"type\": \"check\", \"title\": \"Necessitará de transporte ou translado?\", \"optional\": false, \"responsibleUser\": 1}], \"eventdate\": [{\"type\": \"checkList\", \"title\": \"Verificar serviços de limpeza e copa:\", \"optional\": false, \"checkList\": [{\"name\": \"Plenário ou sala estão limpos\", \"optional\": true}, {\"name\": \"Banheiros higienizados\", \"optional\": true}, {\"name\": \"Filtro de água está cheio\", \"optional\": true}, {\"name\": \"Copos plásticos\", \"optional\": true}, {\"name\": \"Copo de água para os professores e palestrantes\", \"optional\": true}, {\"name\": \"Chá e café\", \"optional\": true}], \"responsibleUser\": 1}, {\"type\": \"checkList\", \"title\": \"Conferir materiais e equipamentos:\", \"optional\": false, \"checkList\": [{\"name\": \"Som\", \"optional\": true}, {\"name\": \"Microfone\", \"optional\": true}, {\"name\": \"Notebook\", \"optional\": true}, {\"name\": \"Data Show\", \"optional\": true}, {\"name\": \"Lousa\", \"optional\": true}, {\"name\": \"Canetas para quadro branco\", \"optional\": true}, {\"name\": \"Apagador\", \"optional\": true}, {\"name\": \"Telão\", \"optional\": true}, {\"name\": \"Filmagem\", \"optional\": true}], \"responsibleUser\": 1}, {\"type\": \"checkList\", \"title\": \"Acolhimento:\", \"optional\": false, \"checkList\": [{\"name\": \"Recepcionar os participantes\", \"optional\": true}, {\"name\": \"Observar se há algum participante que ainda não tenha efetuado a inscrição e orientar para o preenchimento da inscrição tardia\", \"optional\": true}, {\"name\": \"Passar a lista de presença\", \"optional\": true}, {\"name\": \"Entregar material de apoio\", \"optional\": true}], \"responsibleUser\": 1}, {\"type\": \"checkListWithResponsibleUser\", \"title\": \"Mídia:\", \"optional\": false, \"checkList\": [{\"name\": \"Fotografar\", \"optional\": true, \"responsibleUser\": 1}, {\"name\": \"Filmar\", \"optional\": true, \"responsibleUser\": 1}, {\"name\": \"Redes Sociais\", \"optional\": true, \"responsibleUser\": 1}]}, {\"type\": \"checkList\", \"title\": \"Cerimonial:\", \"optional\": false, \"checkList\": [{\"name\": \"Cuidar do translado das autoridades, professores e palestrantes\", \"optional\": true}, {\"name\": \"Recepcionar os convidados\", \"optional\": true}, {\"name\": \"Organizar o mini currículo dos convidados para apresentação\", \"optional\": true}, {\"name\": \"Zelar pela manutenção dos horários fixados\", \"optional\": true}, {\"name\": \"Abertura e encerramento dos trabalhos\", \"optional\": true}], \"responsibleUser\": 1}], \"postevent\": [{\"type\": \"check\", \"title\": \"O memorando para a realização do empenho já foi elaborado e enviado?\", \"optional\": false, \"responsibleUser\": 1}]}'),
('eventsurvey', 6, 'Nova pesquisa de satisfação', '{\"body\": [{\"type\": \"yesNo\", \"title\": \"Mesmo?\", \"optional\": false}], \"foot\": [{\"type\": \"textArea\", \"title\": \"Sugestões?\", \"optional\": true}], \"head\": [{\"type\": \"yesNo\", \"title\": \"Gostou do evento?\", \"optional\": false}]}'),
('eventsurvey', 1, 'Pesquisa de satisfação padrão', '{\"body\": [{\"type\": \"fiveStarRating\", \"title\": \"Nível do evento\", \"optional\": false}, {\"type\": \"fiveStarRating\", \"title\": \"Palestrantes/docentes\", \"optional\": false}, {\"type\": \"fiveStarRating\", \"title\": \"Duração do evento\", \"optional\": false}, {\"type\": \"fiveStarRating\", \"title\": \"Atendimento e orientação antes, depois do evento e durante\", \"optional\": false}, {\"type\": \"fiveStarRating\", \"title\": \"Material de Apoio (se houver)\", \"optional\": true}, {\"type\": \"fiveStarRating\", \"title\": \"O evento atingiu o seu objetivo?\", \"optional\": false}, {\"type\": \"checkList\", \"title\": \"Como ficou sabendo do evento\", \"optional\": false, \"checkList\": [{\"name\": \"Site da Escola\"}, {\"name\": \"Site da Câmara\"}, {\"name\": \"Facebook\"}, {\"name\": \"Google\"}, {\"name\": \"E-mail\"}, {\"name\": \"Jornal\"}, {\"name\": \"Site de Notícias\"}, {\"name\": \"Amigos\"}, {\"name\": \"Cartaz\"}]}], \"foot\": [{\"type\": \"textArea\", \"title\": \"Sugestões\", \"optional\": true}], \"head\": [{\"type\": \"yesNo\", \"title\": \"É a primeira vez que participa?\", \"optional\": false}, {\"type\": \"yesNo\", \"title\": \"Indicará os eventos da Escola?\", \"optional\": false}]}'),
('professorworkdoc', 1, 'Documentação de empenho', '{\"pages\": [{\"elements\": [{\"type\": \"title\", \"align\": \"center\", \"content\": \"Proposta de Trabalho\"}, {\"type\": \"paragraph\", \"content\": \"Conforme Lei nº 2369/2015 e Atos da Mesa 6 e 7/2015, segue abaixo identificação, informações e descrições gerais para atuar como docente na Escola do Parlamento \\\"Doutor Osmar de Souza\\\" da Câmara Municipal de Itapevi:\"}, {\"type\": \"sectionTitle\", \"content\": \"I - IDENTIFICAÇÃO\"}, {\"type\": \"generatedContent\", \"identifier\": \"professorPersonalData1\"}, {\"type\": \"sectionTitle\", \"content\": \"II - INFORMAÇÕES COMPLEMENTARES\"}, {\"type\": \"generatedContent\", \"identifier\": \"professorPersonalData2\"}]}, {\"elements\": [{\"type\": \"sectionTitle\", \"content\": \"III - PROPOSTA DE TRABALHO\"}, {\"type\": \"generatedContent\", \"identifier\": \"workProposalType\"}, {\"type\": \"sectionTitle\", \"content\": \"IV - INFORMAÇÕES SOBRE A ATIVIDADE\"}, {\"type\": \"generatedContent\", \"identifier\": \"professorActivityInformation\"}, {\"type\": \"sectionTitle\", \"content\": \"V - USO DE IMAGEM\"}, {\"type\": \"paragraph\", \"content\": \"Declaro estar ciente de que o conteúdo audiovisual gerado no evento, incluindo minha imagem e/ou voz e/ou depoimento, poderá ser utilizado com finalidade, exclusiva, institucional educacional, a título gratuito, pela Câmara Municipal de Itapevi, por meio da Escola do Parlamento “Doutor Osmar de Souza”.\"}, {\"type\": \"sectionTitle\", \"content\": \"VI - IMPOSSIBILIDADE DA PRESTAÇÃO DO SERVIÇO\"}, {\"type\": \"paragraph\", \"content\": \"Em caso de inexecução total ou parcial do serviço contratado, poderão ser aplicadas as sanções previstas no documento anexo à nota de empenho, do qual o prestador de serviço tomará ciência no ato da contratação, que será o não pagamento total ou parcial.\"}]}, {\"elements\": [{\"type\": \"sectionTitle\", \"content\": \"VII - PRAZO PARA PAGAMENTO\"}, {\"type\": \"paragraph\", \"content\": \"Após a prestação de serviço, o ateste será encaminhado ao Departamento de Finanças e Orçamento da CMI, Escola do Parlamento. O pagamento será efetuado até décimo dia útil do mês, devidamente instruído com o recibo assinado pelo docente na data da prestação do serviço, o comprovante de residência, o documento pessoal apresentado, e a declaração de veracidade, o diploma e o currículo.\"}, {\"type\": \"sectionTitle\", \"content\": \"VIII – OBSERVAÇÕES\"}, {\"type\": \"paragraph\", \"content\": \"É necessário o cadastro do professor junto à Escola do Parlamento no site www.camaraitapevi.sp.gov.br/escola bem como o envio da documentação exigida para a devida instrução do processo de contratação através do e-mail escoladoparlamento@camaraitapevi.sp.gov.br\"}, {\"type\": \"sectionTitle\", \"content\": \"IX - DOCUMENTOS EXIGIDOS\"}, {\"type\": \"orderedList\", \"items\": [\"“Proposta de Trabalho” do docente;\", \"Cópia do Registro de Identidade ou CNH;\", \"Cópia do comprovante de endereço;\", \"Currículo Vitae;\", \"Cópia do diploma, certificados, e, ou documento que comprova a situação acadêmica.\"]}, {\"type\": \"generatedContent\", \"align\": \"right\", \"identifier\": \"cityAndDate\"}, {\"type\": \"generatedContent\", \"align\": \"right\", \"identifier\": \"professorSignatureField\", \"docSignatureId\": 1, \"signatureLabel\": \"Proposta de trabalho\"}]}, {\"elements\": [{\"type\": \"paragraph\", \"align\": \"left\", \"content\": \"LEI Nº 2.843, de 16 de abril de 2021, \\\"Altera a redação e acrescenta dispositivo à Lei nº 2.369, de 27 de novembro de 2015.\\\"\"}, {\"type\": \"generatedContent\", \"tableId\": 0, \"identifier\": \"paymentValuesTable\"}, {\"type\": \"generatedContent\", \"tableId\": 1, \"identifier\": \"paymentValuesTable\"}, {\"type\": \"paragraph\", \"content\": \"OBS.: Ao Coordenador, paga-se 70% da tabela, conforme Ato da Mesa nº 006/2015, Anexo I.\"}]}, {\"elements\": [{\"type\": \"title\", \"align\": \"center\", \"content\": \"DECLARAÇÃO\"}, {\"type\": \"paragraph\", \"content\": \"Eu, ${professorName}, inscrito no RG nº ${professorRG}, e CPF/MF sob nº ${professorCPF}, residente e domiciliado na cidade de ${professorAddressCity}, declaro, para os devidos fins de direito, sob as penas do art. 299 do Código Penal Brasileiro, que as informações e os documentos apresentados para contratação pela Escola do Parlamento são verdadeiros e autênticos\", \"useGeneratedContentTags\": true}, {\"type\": \"paragraph\", \"content\": \"E por ser esta a expressão da verdade, firmo a presente.\"}, {\"type\": \"generatedContent\", \"identifier\": \"cityAndDate\"}, {\"type\": \"generatedContent\", \"identifier\": \"professorSignatureField\", \"docSignatureId\": 2, \"signatureLabel\": \"Declaração de autenticidade de documentos\"}]}, {\"elements\": [{\"type\": \"title\", \"align\": \"center\", \"content\": \"DECLARAÇÃO\"}, {\"type\": \"paragraph\", \"content\": \"Eu, ${professorName}, inscrito no RG nº ${professorRG}, e CPF/MF sob nº ${professorCPF}, à ${professorAddressStreet}, ${professorAddressNumber}, ${professorAddressNeighborhood}, vem por meio deste DECLARAR sob as penas do art. 299 do Código Penal Brasileiro, que nada devo à Fazenda Pública do Município de Itapevi a título de tributos mobiliários.\", \"useGeneratedContentTags\": true}, {\"type\": \"paragraph\", \"content\": \"E por ser esta a expressão da verdade, firmo a presente.\"}, {\"type\": \"generatedContent\", \"identifier\": \"cityAndDate\"}, {\"type\": \"generatedContent\", \"identifier\": \"professorSignatureField\", \"docSignatureId\": 3, \"signatureLabel\": \"Declaração de não devedor à Fazenda Pública de Itapevi\"}]}, {\"elements\": [{\"type\": \"title\", \"align\": \"center\", \"content\": \"RECIBO\"}, {\"type\": \"paragraph\", \"content\": \"Eu, ${professorName}, inscrito no RG nº ${professorRG}, e CPF/MF sob nº ${professorCPF}, declaro ter recebido o valor de ${professorPaymentValueAndFullText} da Câmara Municipal de Itapevi, por meio da Escola do Parlamento “Doutor Osmar de Souza”, correspondente ao pagamento de ${professorClassHoursAndMode} como docente ${professorQualification} durante o evento ${professorEventName} no(s) dia(s) ${professorClassDates}. \", \"conditions\": [\"~paySubsistenceAllowance\"], \"useGeneratedContentTags\": true}, {\"type\": \"paragraph\", \"content\": \"Eu, ${professorName}, inscrito no RG nº ${professorRG}, e CPF/MF sob nº ${professorCPF}, declaro ter recebido o valor de ${professorPaymentValueAndFullText} da Câmara Municipal de Itapevi, por meio da Escola do Parlamento “Doutor Osmar de Souza”, correspondente ao pagamento de ${professorClassHoursAndMode} como docente ${professorQualification} e ajuda de custo conforme prevê o Artigo 14, I do Ato da Mesa 06/2015 de ${professorSubsAllowanceClassHoursAndMode} como docente ${professorSubsAllowanceQualification} durante o evento ${professorEventName} no(s) dia(s) ${professorClassDates}. \", \"conditions\": [\"paySubsistenceAllowance\"], \"useGeneratedContentTags\": true}, {\"type\": \"paragraph\", \"content\": \"E por ser esta a expressão da verdade, firmo a presente.\"}, {\"type\": \"generatedContent\", \"identifier\": \"cityAndDate\"}, {\"type\": \"generatedContent\", \"identifier\": \"professorSignatureField\", \"docSignatureId\": 4, \"signatureLabel\": \"Recibo\"}]}, {\"margins\": [15, 10, 15], \"elements\": [{\"type\": \"title\", \"align\": \"center\", \"content\": \"DECLARAÇÃO INSS\"}, {\"type\": \"paragraph\", \"content\": \"Eu, ${professorName}, inscrito no CPF nº: ${professorCPF} e na Previdência Social, NIT, PIS, PASEP sob número: ${professorPIS_PASEP}, declaro sob as penas da lei, que o desconto da minha contribuição previdenciária como segurado da Previdência Social no período de: ${professorCollectInssFromDate} a  ${professorCollectInssToDate} será realizado pelas empresas  relacionadas  abaixo: \", \"lineHeight\": 6, \"pBreakHeight\": 4, \"useGeneratedContentTags\": true}, {\"type\": \"generatedContent\", \"identifier\": \"inssCompaniesProfessorWorksAt\", \"tableWidth\": 180}, {\"type\": \"paragraph\", \"content\": \"Declaro ainda estar ciente, caso o total das remunerações informadas acima não atinja o limite máximo mensal do Salário de Contribuição (Teto da Previdência Social) no período declarado, a Câmara Municipal de Itapevi será responsável pelo desconto complementar, observado o valor da minha remuneração, bem como o Teto da Previdência Social.\", \"lineHeight\": 6, \"pBreakHeight\": 4}, {\"type\": \"paragraph\", \"content\": \"Entretanto, fica sob minha responsabilidade a complementação mensal da contribuição até o limite da remuneração declarada acima, na hipótese de, por qualquer razão, deixar de receber remuneração ou receber remuneração inferior à indicada nesta declaração.\", \"lineHeight\": 6, \"pBreakHeight\": 4}, {\"type\": \"paragraph\", \"content\": \"Estou ciente que deverei manter uma cópia desta declaração em meu poder juntamente com o(s) comprovante(s) de pagamento(s) da(s) empresa(s) relacionada(s) acima, para apresentação à Previdência Social quando solicitado, bem como, deverei informar de imediato qualquer alteração relacionada à remuneração e empresa(s) relacionada(s).\", \"lineHeight\": 6, \"pBreakHeight\": 4}, {\"type\": \"paragraph\", \"content\": \"A presente declaração atende ao disposto na Lei 10.666/03 de 08/05/2003, Decreto 4.729, de 10/06/2003, art.64 e 67 Instrução Normativa nº, 971 de 13/11/2009 e Instrução Normativa n° 1997, de 07/12/2020.\", \"lineHeight\": 6, \"pBreakHeight\": 4}, {\"type\": \"paragraph\", \"content\": \"Por ser verdade, firmo a presente declaração, ficando sob minha responsabilidade qualquer sanção imposta pela Auditoria Fiscal da Receita Federal do Brasil decorrente de seus efeitos.\", \"lineHeight\": 6, \"pBreakHeight\": 4}, {\"type\": \"generatedContent\", \"align\": \"center\", \"identifier\": \"cityAndDate\"}, {\"type\": \"generatedContent\", \"align\": \"center\", \"identifier\": \"professorSignatureField\", \"docSignatureId\": 5, \"signatureLabel\": \"Declaração INSS\"}], \"conditions\": [\"collectInss\"]}]}'),
('eventsubscription', 1, 'Formulário Padrão de Inscrição', '{\"terms\": [{\"name\": \"Política de inscrição e certificação\", \"termId\": 1, \"required\": true, \"titleLabel\": \"Você concorda nossa política de inscrição e certificação?\", \"checkBoxLabel\": \"Concordo\"}, {\"name\": \"Termo de consentimento para o tratamento de dados pessoais\", \"termId\": 2, \"required\": true, \"titleLabel\": \"Você concorda com o termo de consentimento para o tratamento de seus dados pessoais?\", \"checkBoxLabel\": \"Concordo\"}], \"questions\": [{\"hide\": {\"infoText\": \"O nome social será exibido no certificado juntamente ao nome completo fornecido acima. Só preencha este campo se você tiver nome social e ele for diferente do nome completo. \", \"checkBoxLabel\": \"Usar nome social no certificado\"}, \"formInput\": {\"type\": \"text\", \"label\": \"Nome social: \", \"properties\": {\"size\": 60, \"required\": 1, \"maxlength\": 140}}, \"identifier\": \"socialName\"}, {\"formInput\": {\"type\": \"text\", \"label\": \"Celular (com prefixo): \", \"properties\": {\"size\": 30, \"required\": 1, \"maxlength\": 20}}, \"identifier\": \"phoneNumber\"}, {\"formInput\": {\"type\": \"date\", \"label\": \"Data de nascimento: \", \"properties\": {\"required\": 1}}, \"identifier\": \"birthDate\"}, {\"formInput\": {\"type\": \"radiobuttons\", \"label\": \"Gênero: \", \"options\": {\"useDbEnum\": \"GENDER\"}, \"properties\": {\"required\": 1}}, \"identifier\": \"gender\"}, {\"formInput\": {\"type\": \"radiobuttons\", \"label\": \"Nacionalidade: \", \"options\": {\"useDbEnum\": \"NATION\"}, \"properties\": {\"required\": 1}}, \"identifier\": \"nationality\"}, {\"formInput\": {\"type\": \"radiobuttons\", \"label\": \"Etnia: \", \"options\": {\"useDbEnum\": \"RACE\"}, \"properties\": {\"required\": 1}}, \"identifier\": \"race\"}, {\"formInput\": {\"type\": \"radiobuttons\", \"label\": \"Escolaridade: \", \"options\": {\"useDbEnum\": \"SCHOOLING\"}, \"properties\": {\"required\": 1}}, \"identifier\": \"schoolingLevel\"}, {\"formInput\": {\"type\": \"combobox\", \"label\": \"Estado (UF): \", \"options\": {\"useDbEnum\": \"UF\"}, \"properties\": {\"required\": 1}}, \"identifier\": \"stateUf\"}, {\"formInput\": {\"type\": \"radiobuttons\", \"label\": \"Área de atuação principal: \", \"options\": {\"useDbEnum\": \"OCCUPATION\"}, \"properties\": {\"required\": 1}}, \"identifier\": \"occupation\"}, {\"hide\": {\"infoText\": \"Se você precisa de algum recurso de acessibilidade para participar do evento, informe-o abaixo: \", \"checkBoxLabel\": \"Informar adaptação ou recurso de acessibilidade requerido\"}, \"formInput\": {\"type\": \"text\", \"label\": \"Recurso requerido: \", \"properties\": {\"maxlength\": 60, \"placeholder\": \"Qual?\"}}, \"identifier\": \"accessibilityRequired\"}]}'),
('eventsubscription', 2, 'Formulário de Inscrição com Redes Sociais', '{\"terms\": [{\"name\": \"Política de inscrição e certificação\", \"termId\": 1, \"required\": true, \"titleLabel\": \"Você concorda nossa política de inscrição e certificação?\", \"checkBoxLabel\": \"Concordo\"}, {\"name\": \"Termo de consentimento para o tratamento de dados pessoais\", \"termId\": 2, \"required\": true, \"titleLabel\": \"Você concorda com o termo de consentimento para o tratamento de seus dados pessoais?\", \"checkBoxLabel\": \"Concordo\"}], \"questions\": [{\"hide\": {\"infoText\": \"O nome social será exibido no certificado juntamente ao nome completo fornecido acima. Só preencha este campo se você tiver nome social e ele for diferente do nome completo. \", \"checkBoxLabel\": \"Usar nome social no certificado\"}, \"formInput\": {\"type\": \"text\", \"label\": \"Nome social: \", \"properties\": {\"size\": 60, \"required\": 1, \"maxlength\": 140}}, \"identifier\": \"socialName\"}, {\"formInput\": {\"type\": \"text\", \"label\": \"Celular (com prefixo): \", \"properties\": {\"size\": 30, \"required\": 1, \"maxlength\": 20}}, \"identifier\": \"phoneNumber\"}, {\"formInput\": {\"type\": \"date\", \"label\": \"Data de nascimento: \", \"properties\": {\"required\": 1}}, \"identifier\": \"birthDate\"}, {\"formInput\": {\"type\": \"radiobuttons\", \"label\": \"Gênero: \", \"options\": {\"useDbEnum\": \"GENDER\"}, \"properties\": {\"required\": 1}}, \"identifier\": \"gender\"}, {\"formInput\": {\"type\": \"radiobuttons\", \"label\": \"Nacionalidade: \", \"options\": {\"useDbEnum\": \"NATION\"}, \"properties\": {\"required\": 1}}, \"identifier\": \"nationality\"}, {\"formInput\": {\"type\": \"radiobuttons\", \"label\": \"Etnia: \", \"options\": {\"useDbEnum\": \"RACE\"}, \"properties\": {\"required\": 1}}, \"identifier\": \"race\"}, {\"formInput\": {\"type\": \"radiobuttons\", \"label\": \"Escolaridade: \", \"options\": {\"useDbEnum\": \"SCHOOLING\"}, \"properties\": {\"required\": 1}}, \"identifier\": \"schoolingLevel\"}, {\"formInput\": {\"type\": \"combobox\", \"label\": \"Estado (UF): \", \"options\": {\"useDbEnum\": \"UF\"}, \"properties\": {\"required\": 1}}, \"identifier\": \"stateUf\"}, {\"formInput\": {\"type\": \"radiobuttons\", \"label\": \"Área de atuação principal: \", \"options\": {\"useDbEnum\": \"OCCUPATION\"}, \"properties\": {\"required\": 1}}, \"identifier\": \"occupation\"}, {\"hide\": {\"infoText\": \"Se você precisa de algum recurso de acessibilidade para participar do evento, informe-o abaixo: \", \"checkBoxLabel\": \"Informar adaptação ou recurso de acessibilidade requerido\"}, \"formInput\": {\"type\": \"text\", \"label\": \"Recurso requerido: \", \"properties\": {\"size\": 50, \"maxlength\": 140, \"placeholder\": \"Qual?\"}}, \"identifier\": \"accessibilityRequired\"}, {\"formInput\": {\"type\": \"info\", \"label\": \"Se desejar, informe suas redes sociais abaixo.\", \"properties\": {}}, \"identifier\": \"info1\"}, {\"formInput\": {\"type\": \"text\", \"label\": \"Facebook: \", \"properties\": {\"size\": 40, \"placeholder\": \"Opcional\"}}, \"identifier\": \"socNet_facebook\"}, {\"formInput\": {\"type\": \"text\", \"label\": \"Instagram: \", \"properties\": {\"size\": 40, \"placeholder\": \"Opcional\"}}, \"identifier\": \"socNet_instagram\"}]}');

-- --------------------------------------------------------

--
-- Estrutura da tabela `libraryborrowedpublications`
--

CREATE TABLE `libraryborrowedpublications` (
  `id` int NOT NULL,
  `publicationId` int NOT NULL,
  `libUserId` int NOT NULL,
  `borrowDatetime` datetime NOT NULL,
  `expectedReturnDatetime` datetime NOT NULL,
  `returnDatetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='Empréstimos do acervo da biblioteca';

--
-- Extraindo dados da tabela `libraryborrowedpublications`
--

INSERT INTO `libraryborrowedpublications` (`id`, `publicationId`, `libUserId`, `borrowDatetime`, `expectedReturnDatetime`, `returnDatetime`) VALUES
(1, 2, 4, '2022-01-04 17:07:30', '2022-01-05 12:00:00', '2022-01-04 18:03:56'),
(2, 5, 5, '2022-02-06 16:40:03', '2022-02-07 12:00:00', '2022-02-06 16:41:03'),
(3, 3, 1, '2022-10-01 17:44:39', '2022-10-10 10:00:00', '2022-10-01 17:46:01'),
(4, 4, 1, '2022-10-01 17:46:27', '2022-10-03 12:00:00', '2022-10-01 17:58:13'),
(5, 4, 3, '2022-10-01 17:58:24', '2022-10-05 12:00:00', '2022-10-01 18:00:51');

-- --------------------------------------------------------

--
-- Estrutura da tabela `librarycollection`
--

CREATE TABLE `librarycollection` (
  `id` int NOT NULL,
  `registrationDate` date NOT NULL,
  `author` varchar(120) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `title` varchar(280) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `cdu` varchar(120) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `cdd` varchar(120) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `isbn` varchar(120) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `local` varchar(120) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `publisher_edition` varchar(120) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `number` varchar(120) DEFAULT NULL,
  `month` varchar(120) DEFAULT NULL,
  `year` varchar(120) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `edition` varchar(120) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `volume` varchar(120) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `copyNumber` varchar(120) DEFAULT NULL,
  `pageNumber` varchar(120) DEFAULT NULL,
  `typeAcquisitionId` int NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `prohibitedSale` tinyint(1) NOT NULL DEFAULT '0',
  `provider` varchar(120) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `exclusionInfoTerm` varchar(255) DEFAULT NULL,
  `registeredByUserId` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='Tabela para o acervo da biblioteca';

--
-- Extraindo dados da tabela `librarycollection`
--

INSERT INTO `librarycollection` (`id`, `registrationDate`, `author`, `title`, `cdu`, `cdd`, `isbn`, `local`, `publisher_edition`, `number`, `month`, `year`, `edition`, `volume`, `copyNumber`, `pageNumber`, `typeAcquisitionId`, `price`, `prohibitedSale`, `provider`, `exclusionInfoTerm`, `registeredByUserId`) VALUES
(1, '2022-01-01', 'COLLI, Márcia Constantino ', 'Manual de Prevenção das doenças sexuais', '', '616.951', '', 'Maringá', 'Caniatti', '', '', '2003', '1 ed.', '', 'ex.1', 'p.101', 1, '7.80', 0, '003.', '001', 1),
(2, '2022-01-01', 'ANDERY, Maria Amália ', 'Para Compreender a ciência', '88.0081', '199.81', '85-85114-42-8', 'São Paulo', 'Editora Espaço e tempo', NULL, NULL, '1988', '1 ed.', NULL, 'ex.1', 'p.446', 1, '30.00', 0, '003.', NULL, 1),
(3, '2022-01-01', 'RODRIGUES, Alberico ', 'Zé Batalha: O Herói da Minha Infância', '044.654', '398', '85-98581-01-1', 'São Paulo', 'Mentes Raras', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p. 81', 1, '12.00', 0, '003.', NULL, 1),
(4, '2022-01-01', 'GUARNIERI, Gianfrancisco ', 'Eles não usam black-tie', '869.0(81)-2', '869.92', '978-85-200-0182-0', 'Rio de Janeiro ', 'Civilização Brasileira', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p. 112', 1, '39.92', 0, '003.', NULL, 1),
(5, '2022-01-01', 'Promotoria de Justiça do Meio Ambiente de São Paulo', 'Dicionário Ambiental Básico', NULL, '008', NULL, 'São Paulo', 'RIMI - Gráfica e Editora', NULL, NULL, '2004', '1 ed.', 'v.1', 'ex.1', 'p. 92', 1, NULL, 1, '003.', NULL, 1),
(6, '2022-01-01', 'SARTINI, Humberto ', 'Socorro! O telefone está tocando...', NULL, '652', NULL, 'São Paulo', 'Editora Exclusiva', NULL, NULL, '1999', '1 ed.', NULL, 'ex.1', 'p. 94', 1, '30.00', 0, '003.', NULL, 1),
(7, '2022-01-01', 'SARTINI, Humberto ', 'O livro de bolsa da secretária', NULL, '652', NULL, 'São Paulo', 'Editora Exclusiva', NULL, NULL, '1999', '1 ed.', NULL, 'ex.1', 'p.137', 1, '30.00', 0, '003.', NULL, 1),
(8, '2022-01-01', 'CAMPOS, José Narino de Campos', 'Cartas aos Bispos do Brasil', NULL, '282', '85-85008-87-3', 'São Paulo', 'Bisordi Ltda', NULL, NULL, '1988', '1 ed.', NULL, 'ex.1', 'p. 96', 1, '6.00', 0, '003.', NULL, 1),
(9, '2022-01-01', '***', 'A Bíblia na Linguagem de Hoje - Novo Testamento', NULL, '225', NULL, 'Rio de Janeiro ', 'SBB - Sociedade Bíblica do Brasil ', NULL, NULL, '1975', '1 ed.', NULL, 'ex.1', 'p. 744', 1, '10.00', 0, '003.', NULL, 1),
(10, '2022-01-01', 'LUCCA, José Carlos de ', 'Pensamentos que ajudam', '150.7684', '133.9', '978-85-63808-67-7', 'São Paulo', 'Intelítera', NULL, NULL, '2016', '1 ed.', NULL, 'ex.1', 'p.  249', 1, '12.00', 0, '003.', NULL, 1),
(11, '2022-01-01', 'CHOVROT, Georges ', 'Jesus e a Samaritana', NULL, '282', '978-85-7465-163-7', 'São Paulo', 'Quadrante', NULL, NULL, '2013', '1 ed.', NULL, 'ex.1', 'p. 166', 1, '54.00', 0, '003.', NULL, 1),
(12, '2022-01-01', 'OLIVEIRA, Armado Fernandes de', 'Oração é luz', NULL, '133.9', '978-85-7353-104-6', 'Capivarí-SP', 'EME Editora', NULL, NULL, '2013', '1 ed.', NULL, 'ex.1', 'p. 214', 1, '10.00', 0, '003.', NULL, 1),
(13, '2022-01-01', 'XAVIER, Francisco Cândido ', 'À luz da Oração', NULL, '133.9', '978-85-7357-138-7', 'Marão-SP', 'Casa Editora O Clarim', NULL, NULL, '2015', '1 ed.', NULL, 'ex.1', 'p. 216', 1, '13.00', 0, '003.', NULL, 1),
(14, '2022-01-01', 'FERNANDES, Rubem César', 'Romarias da Paixão', '248.153', '248', '85-325-0436-1', 'Rio de Janeiro ', 'Rocco Ltda', NULL, NULL, '1994', '1 ed.', NULL, 'ex.1', 'p. 251', 1, '6.00', 0, '003.', NULL, 1),
(15, '2022-01-01', 'FABER, Frederick Willian ', 'A bondade: pensamentos, palavras e ações', NULL, '133.9', '978-85-7465-169-9', 'São Paulo', 'Quadrante', NULL, NULL, '2013', '1 ed.', NULL, 'ex.1', 'p. 77', 1, '42.00', 0, '003.', NULL, 1),
(16, '2022-01-01', 'ALMEIDA, Francisco José de Almeida', 'A alegria de crer: A vitória que vence o mundo', NULL, '248', '978-7465-162-0', 'São Paulo', 'Quadrante', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p. 77', 1, '27.20', 0, '003.', NULL, 1),
(17, '2022-01-01', 'DIAS, Romualdo ', 'Imagens da Ordem: a doutrina católica sobre autoridad no Brasil (1922-1933)', NULL, '282', '85-7139-119-x', 'São Paulo', 'UNESP - Universidade Estadual Paulista', NULL, NULL, '1996', '1 ed.', NULL, 'ex.1', 'p.  161', 1, '10.00', 0, '003.', NULL, 1),
(18, '2022-01-01', 'CAVALLARI, Marcelo Musa', 'Catolicismo', NULL, '282', NULL, 'São Paulo', 'Bella Editora', NULL, NULL, '2017', '1 ed.', NULL, 'ex.1', 'p. 189', 1, '24.00', 0, '003.', NULL, 1),
(19, '2022-01-01', 'RIBEIRO, João  Ubaldo ', 'Política', '32', '320', NULL, 'Rio de Janeiro ', 'Nova Fronteira S.A.', NULL, NULL, '1981', '1 ed.', 'v.1', 'ex.1', 'p. 181', 1, '31.99', 0, '003.', NULL, 1),
(20, '2022-01-01', 'SOARES, Ricardo Antonio Bueno ', 'Leitura Dinâmica: como multiplicar a velocidade e a retenção da leitura', NULL, '469-843', '85-87002-08-2', 'Rio de Janeiro ', 'Impetus Des. Educ. Ltda', NULL, NULL, '2000', '1 ed.', NULL, 'ex.1', 'p. 245', 1, '16.90', 0, '003.', NULL, 1),
(21, '2022-01-01', 'MARTINS, Antonio Carlos Godoy (Org.)', 'Manual de Redação Administrativa', NULL, '469.0469', NULL, 'São Paulo', 'Assembleia Legislativa de São Paulo', NULL, NULL, '2003', '1 ed.', NULL, 'ex.1', 'p. 245', 1, '10.00', 0, '003.', NULL, 1),
(22, '2022-01-01', 'MIDEI, Wanderlei ', 'Vendo a Vida', NULL, '869.1', '85-7372-612-1', 'São Paulo', 'Scorteccl Editora', NULL, NULL, '2001', '1 ed.', NULL, 'ex.1', 'p. 79', 1, '10.00', 0, '003.', NULL, 1),
(23, '2022-01-01', 'DEVIDÉ, Eni', 'O meu caminho - A história de Gil Lancaster vai inspirar você', NULL, '921', NULL, 'São Paulo', 'Elyon Indústria Gráfica', NULL, NULL, '2014', '1 ed.', NULL, 'ex.1', 'p.99', 1, '7.99', 0, '003.', NULL, 1),
(24, '2022-01-01', 'BULLÓN, Alejandro ', 'A única Esperança', NULL, '234', '9788-85-345-1953-3', 'Tatuí-SP', 'Casa Publicadora Brasileira', NULL, NULL, '2013', '1 ed.', NULL, 'ex.1', 'p. 110', 1, '11.90', 0, '003.', NULL, 1),
(25, '2022-01-01', 'ARRUDA, Edimundo Lima de Junior (Org.)', 'Globalização, Neoliberalismo e o mundo do trabalho', '338', '320', NULL, 'Curitiba-PR', 'Edibej Editora', NULL, NULL, '1998', '1 ed.', NULL, 'ex.1', 'p. 300', 1, '13.10', 0, '003.', NULL, 1),
(26, '2022-01-01', 'SALGADO, Luiz Gonzada e TOLEDO, Caio Alves e ', 'Do pricípio do mundo ao fim do câncer (A cura científica do câncer)', NULL, '362.1', NULL, 'São Paulo', 'Rolengraf Ltda', NULL, NULL, '1976', '1 ed.', NULL, 'ex.1', 'p. 302', 1, '17.25', 0, '003.', NULL, 1),
(27, '2022-01-01', 'MARAN, Ruth ', 'Aprenda a usar o computador e a internet através de imagens', '004', '004-1', '85-86115-20-3', 'Rio de Janeiro ', 'Reader\'s Digest', NULL, NULL, '1999', '1 ed.', NULL, 'ex.1', 'p. 288', 1, '7.00', 0, '003.', NULL, 1),
(28, '2022-01-01', 'CARONE, Modesto ', 'Resumo de Ana', NULL, '869-935', '85-7164-798-4', 'São Paulo', 'Companhia das Letras', NULL, NULL, '1998', '1 ed.', NULL, 'ex.1', 'p. 114', 1, '19.90', 0, '003.', NULL, 1),
(29, '2022-01-01', 'BADIM, Reinaldo Furquim ', 'Guego, o outro lado', NULL, '900', NULL, 'São Paulo', 'Gráfica dos D.A.S. da FEB', NULL, NULL, '1979', '1 ed.', NULL, 'ex.1', 'p.34', 1, '10.00', 0, '003.', NULL, 1),
(30, '2022-01-01', 'FERREIRA, Aurélio Buarque de Holanda ', 'Minidicionário da Língua Portuguesa', NULL, '008', NULL, 'Rio de Janeiro ', 'Nova Fronteira S.A.', NULL, NULL, '1985', '1 ed.', NULL, 'ex.1', 'p. 506', 1, '0.00', 0, '003.', NULL, 1),
(31, '2022-01-01', 'ÁVILA, Fernando Bastos de ', 'Pequena Ensiclopédia de Moral e Civismo', NULL, '008', NULL, 'Brasília-DF', 'MEC - Ministério de Educação e Cultura', NULL, NULL, '1967', '1 ed.', NULL, 'ex.1', 'p. 511', 1, NULL, 1, '003.', NULL, 1),
(32, '2022-01-01', 'Governo do Estado do Ceará', 'Anuário do Ceará 2010-2011', NULL, '905', '1677-2881', 'Ceará', 'Assembleia Legislativa do Ceará', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.  736', 1, NULL, 1, '003.', NULL, 1),
(33, '2022-01-01', 'TEMER, Michel ', 'Elementos de Direito Constitucional', NULL, '342', NULL, 'São Paulo', 'Graphbox', NULL, NULL, '1992', '1 ed.', 'v.1', 'ex.1', 'p. 206', 1, '12.00', 0, '003.', NULL, 1),
(34, '2022-01-01', 'VICENTE, Gil', 'Coleção Estadão: Ler e Aprende - Vol. 8 - Auto da Barca do Inferno', NULL, '869.03', NULL, 'São Paulo', 'Klick Editora', NULL, NULL, '1997', '1 ed.', 'v.8', 'ex.1', 'p. 112', 1, '6.00', 0, '003.', NULL, 1),
(35, '2022-01-01', 'GRAZIANO, Francisco (Xico)  Neto', 'Juventude consciente - conceitos e temas da política nacional', NULL, '320-03', '85.7113.163+5', 'Campinas-SP', 'Pontes', NULL, NULL, '2002', '1 ed.', 'v.1', 'ex.1', 'p. 72', 1, '14.00', 0, '003.', NULL, 1),
(36, '2022-01-01', 'XAVIER, Ronaldo Caldeira ', 'Português no direito: linguagem forense', '34.806.90', '348.06', NULL, 'Rio de Janeiro ', 'Companhia  Editora Forence', NULL, NULL, '1987', '1 ed.', 'v.1', 'ex.1', 'p. 341', 1, '15.00', 0, '003.', NULL, 1),
(37, '2022-01-01', 'CAVALHEIRO, Jader Branca', 'A organização do Sistema de Controle Interno Municipal do Rio Grande do Sul', NULL, '658', NULL, 'Porto Alegre-RS', 'Atricon - Associação dos Membros dos Tribunais de Contas do Brasil', NULL, NULL, '2007', '1 ed.', 'v.1', 'ex.1', 'p. 83', 1, '11.00', 0, '003.', NULL, 1),
(38, '2022-01-01', 'Assembleia Legislativa de São Paulo', 'Normas Conexas ao Regimento Interno', '342.52(815.6)', '342', NULL, 'São Paulo', 'Assembleia Legislativa de São Paulo', NULL, NULL, '2002', '1 ed.', 'v.1', 'ex.1', 'p. 293', 1, NULL, 1, '003.', NULL, 1),
(39, '2022-01-01', 'SÃO PAULO, Associação do Advogados de', '70 Anos : Gerações e serviço da advocacia', NULL, '921', NULL, 'São Paulo', 'DBA- Dórea Books and Art', NULL, NULL, '2013', '1 ed.', NULL, 'ex.1', 'p.143', 1, '11.99', 0, '003.', NULL, 1),
(40, '2022-01-01', 'MAGALHÃES, Marcos (Org.)', 'Salão de humor da anistia', '741.5-(091)', '740', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2009', '1 ed.', NULL, 'ex.1', 'p. 292', 1, '50.00', 0, '003.', NULL, 1),
(41, '2022-01-01', 'QUINTANA, Mario ', 'Canções', NULL, '869.03', '85.250.0161-9', 'Rio de Janeiro ', 'Globo', NULL, NULL, '1946', '1 ed.', NULL, 'ex.1', NULL, 1, '75.00', 0, '003.', NULL, 1),
(42, '2022-01-01', 'Câmara Municipal de SP', 'Conheça o novo plano diretor estratégido de São Paulo - Lei 16.050/14', NULL, '348', NULL, 'São Paulo', 'Câmara de São Paulo', NULL, NULL, '2014', '1 ed.', 'v.1', 'ex.1', 'p. 47', 1, NULL, 1, '003.', NULL, 1),
(43, '2022-01-01', 'SÃO PAULO, Assembleia Legislativa de (Coord. MAGALHÃES, Gilherme Wendel de)', 'Parlamento Paulista 2005/2007', '342.531.41(815.6)', '921', NULL, 'São Paulo', 'Assembleia Legislativa de São Paulo', NULL, NULL, '2006', '1 ed.', NULL, 'ex.1', 'p. 148', 1, NULL, 1, '003.', NULL, 1),
(44, '2022-01-01', 'SÃO PAULO, Assembleia Legislativa de (Coord. CALIMAN, Auro Augusto) ', 'Legislativo  Paulista - Parlamentares (1835-2011)', '342.52(816.1)/ 342.534(816.1) ', '921', NULL, 'São Paulo', 'Assembleia Legislativa de São Paulo', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p. 224', 1, NULL, 1, '003.', NULL, 1),
(45, '2022-01-01', 'NABOKOV, Vladimir ', 'Lolita', NULL, '813.5', NULL, 'São Paulo', 'O Globo', NULL, NULL, '2003', '1 ed.', NULL, 'ex.1', 'p. 319', 1, '20.00', 0, '003.', NULL, 1),
(46, '2022-01-01', 'MARKY, Thomas ', 'Curso Elementar de Direito Romano', '34(37)', '340.1', '85-02-00713-0', 'São Paulo', 'Saraiva', NULL, NULL, '1990', '1 ed.', NULL, 'ex.1', 'p. 209', 1, '120.00', 0, '003.', NULL, 1),
(47, '2022-01-01', 'ARNOLDI, Paulo Roberto Colombo', 'Ação Cambial', '347.746.6    347.746.6(81)', '346.07', '85.02.00925-7', 'São Paulo', 'Saraiva', NULL, NULL, '1991', '1 ed.', NULL, 'ex.1', 'p. 80', 1, '10.00', 0, '003.', NULL, 1),
(48, '2022-01-01', 'KRUGER, Domingos Afonso. Filho', 'A responsabilidade civil e penal no código de proteção e defesa do consumidor', '347.451.031(01)', '340', '85.7131.089-0', 'Porto Alegre-RS', 'Síntese Ltda', NULL, NULL, '2000', '1 ed.', NULL, 'ex.1', 'p. 124', 1, '20.00', 0, '003.', NULL, 1),
(49, '2022-01-01', 'SAFFI, Aurélio', 'O poder legislativo muncipal', '342.53(81)', '342.05', '85.86557-17-x', 'Campinas-SP', 'Jurídica Mizuno', NULL, NULL, '1999', '1 ed.', NULL, 'ex.1', 'p. 219', 1, '15.00', 0, '003.', NULL, 1),
(50, '2022-01-01', 'MACEDO, Joaquim Manuel de', 'A moreninha', NULL, '869.03', '85.08.00466-4', 'São Paulo', 'Ática', NULL, NULL, '1986', '1 ed.', NULL, 'ex.1', 'p. 118', 1, '15.00', 0, '003.', NULL, 1),
(51, '2022-01-01', 'GUIMARÃES, Bernardo ', 'A escrava Isaura', NULL, '869.93', NULL, 'São Paulo', 'Ática', NULL, NULL, '1985', '13 ed.', NULL, 'ex.1', 'p. 130', 1, '7.00', 0, '003.', NULL, 1),
(52, '2022-01-01', 'ALENCAR, José de ', 'Cinco Minutos e A viuvinha', NULL, '869.03', '85.08.00662.4', 'São Paulo', 'Ática', NULL, NULL, '1968', '1 ed.', NULL, 'ex.1', 'p. 91', 1, '10.00', 0, '003.', NULL, 1),
(53, '2022-01-01', 'ASSIS, Machado de', 'Contos Escolhidos', NULL, '869.03', '85.7263.076.7', 'São Paulo', 'Núcleo', NULL, NULL, '1994', '1 ed.', NULL, 'ex.1', 'p. 115', 1, '10.00', 0, '003.', NULL, 1),
(54, '2022-01-01', 'DUPRÉ, Maria José ', 'O Romance de Teresa Bernard', NULL, '869.03', NULL, 'São Paulo', 'Saraiva', NULL, NULL, '1967', '1 ed.', NULL, 'ex.1', 'p. 303', 1, '30.00', 0, '003.', NULL, 1),
(55, '2022-01-01', 'SANTOS, Jessy ', 'Filosofia e Humanismo', NULL, '144', NULL, 'São Paulo', 'Duas Cidades', NULL, NULL, '1981', '1 ed.', NULL, 'ex.1', 'p. 152', 1, '73.00', 0, '003.', NULL, 1),
(56, '2022-01-01', 'CARNEIRO, Athos Gusmão', 'Cumprimento da sentença civil e procedimentos executivos ', '347.95(81)', '347', '978-85-309-3192-6', 'Rio de Janeiro ', 'Forense Editora', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p. 254', 1, '56.00', 0, '003.', NULL, 1),
(57, '2022-01-01', 'MILHOMENS, Jônatas ', 'Manual prático dos contratos', '347.44', '347', NULL, 'Rio de Janeiro ', 'Forense Editora', NULL, NULL, '1996', '1 ed.', NULL, 'ex.1', 'p. 810', 1, '35.00', 0, '003.', NULL, 1),
(58, '2022-01-01', 'GIGLIO, Wagner D. ', 'Direito processual do trabalho', '347.9.331', '347', NULL, 'São Paulo', 'LTR Editora', NULL, NULL, '1993', '1 ed.', NULL, 'ex.1', 'p. 616', 1, '16.95', 0, '003.', NULL, 1),
(59, '2022-01-01', 'SANTOS, Ulderico Pires dos ', 'A responsabilidade civil na doutrina e na jurisprudência', '347.51', '347', NULL, 'Rio de Janeiro ', 'Forense Editora', NULL, NULL, '1987', '1 ed.', NULL, 'ex.1', 'p. 662', 1, '20.00', 0, '003.', NULL, 1),
(60, '2022-01-01', 'CARRION, Valentin ', 'Comentários à consolidação das Leis de Trabalho', '34.331.(81)(094.56)', '350', '85.203.1037.0', 'São Paulo', 'RT -Revista dos Tribunais', NULL, NULL, '1992', '1 ed.', NULL, 'ex.1', 'p. 996', 1, '20.00', 0, '003.', NULL, 1),
(61, '2022-01-01', 'NUNES, Castro', 'Do mandado de seurança e de outors meios de defesa contra atos do poder público', NULL, '342', NULL, 'Rio de Janeiro ', 'Forense Editora', NULL, NULL, '1956', '1 ed.', NULL, 'ex.1', 'p. 591', 1, '30.00', 0, '003.', NULL, 1),
(62, '2022-01-01', 'VIGNOLI, Francisco Humberto (Coord.)', 'Lei de responsabilidade fiscal comentada para muncipios', '336.1/5', '346', NULL, 'São Paulo', 'Fundação Getúlio Vargas', NULL, NULL, '2002', '1 ed.', NULL, 'ex.1', 'p. 316', 1, '20.00', 0, '003.', NULL, 1),
(63, '2022-01-01', 'CINTRA, Antônio Carlos de Araújo', 'Teoria geral do processo      (6ª Ed.)', '347.9/ 343.1/ 3.43.1(81)/ 347.9(81)', '347', '85.203.0514.8', 'São Paulo', 'RT -Revista dos Tribunais', NULL, NULL, '1986', '1 ed.', NULL, 'ex.1', 'p. 329', 1, '30.00', 0, '003.', NULL, 1),
(64, '2022-01-01', 'CINTRA, Antônio Carlos de Araújo', 'Teoria geral do processo     (13ª Ed.)', '347.9/ 343.1/ 3.43.1(81)/ 347.9(81)', '347', '85.203.0514.8', 'São Paulo', 'Malheiros', NULL, NULL, '1997', '1 ed.', NULL, 'ex.2', 'p. 364', 1, '30.00', 0, '003.', NULL, 1),
(65, '2022-01-01', 'MAZZILLI, Hugo Nigro ', 'A defesa dos interesses difusos em juízo: meio ambiente, consumidor e outros interesses difusos e coletivos', '347.922.33(81)', '347', '85.02.03356.5', 'São Paulo', 'Saraiva', NULL, NULL, '2001', '1 ed.', NULL, 'ex.1', 'p. 575', 1, '20.00', 0, '003.', NULL, 1),
(66, '2022-01-01', 'TEMER, Michel ', 'Elementos de Direito Constitucional (17ª Ed)', NULL, '342', NULL, 'São Paulo', 'Malheiros', NULL, NULL, '1992', '1 ed.', NULL, 'ex.2', 'p. 206', 1, '50.00', 0, '003.', NULL, 1),
(67, '2022-01-01', 'PINTO, Antonio Luiz de Toledo(ORG.)', 'Código Penal / Processo Penal e Constituição Federal', '34(81)(094.4)', '348', '978.85.02.07345-6', 'São Paulo', 'Saraiva', NULL, NULL, '2009', '1 ed.', NULL, 'ex.1', 'p. 982', 1, '22.00', 0, '003.', NULL, 1),
(68, '2022-01-01', 'CUSTÓDIO, Antonio Joaquim Ferreira  (Org.)', 'Constituição Federal de 1988 (Atualizada Até a EC22/99)', '342.81023', '348', '85.7453.069.7', 'São Paulo', 'Juarez de Oliveira Ltda', NULL, NULL, '1999', '1 ed.', NULL, 'ex.1', 'p. 363', 1, '10.00', 0, '003.', NULL, 1),
(69, '2022-01-01', 'ALVES, Benedito Antônio ', 'Lei de responsabilidade fiscal comentada e anotada', '343.81034', '348', '85.7453.171.5', 'São Paulo', 'Juarez de Oliveira Ltda', NULL, NULL, '2000', '1 ed.', 'v.1', 'ex.1', 'p. 200', 1, '15.00', 0, '003.', NULL, 1),
(70, '2022-01-01', 'Diretódio do PPB', 'Regimento do PPB - Partido Progressista Brasileiro', NULL, '320', NULL, 'Brasília-DF', 'Gráfica Editora', NULL, NULL, '1999', '1 ed.', 'v.1', 'ex.2', 'p. 83', 1, NULL, 1, '003.', NULL, 1),
(71, '2022-01-01', 'Câmara dos Deputados ', 'Estatudo do Desarmamento Lei 10.826 de 2003 (5ªEd.)', '343.344(81)(094)', '348', '978.85.402.0080.7', 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '2013', '1 ed.', 'v.1', 'ex.1', 'p. 59', 1, NULL, 1, '003.', NULL, 1),
(72, '2022-01-01', 'Brasil', 'Código Civil/Código Comercial/ Precesso Civil e Constituição Federal', '34(81)(094.4)', '348', '85.02.05542.9', 'São Paulo', 'Saraiva', NULL, NULL, '2006', '1 ed.', 'v.1', 'ex.1', 'p. 1539', 1, '60.00', 0, '003.', NULL, 1),
(73, '2022-01-01', '***', 'Constituição do Estado de São Paulo de 5 de outubro de 1989 (5ªEd./2001)', NULL, '348', '85.224.2896.4', 'São Paulo', 'Atlas S.A.', NULL, NULL, '2001', '1 ed.', 'v.1', 'ex.1', 'p. 140', 1, '60.00', 0, '003.', NULL, 1),
(74, '2022-01-01', 'ANDRADA,  Bonifácio de ', 'Direito Partidário Comenado', NULL, '320', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '1997', '1 ed.', 'v.1', 'ex.1', 'p. 209', 1, '50.00', 0, '003.', NULL, 1),
(75, '2022-01-01', 'BENETI, Sidnei Agostinho ', 'Modelos de despacho de sentenças', '340.142.(083.2)', '347', '85.02.043993.5', 'São Paulo', 'Saraiva', NULL, NULL, '2004', '1 ed.', 'v.1', 'ex.1', 'p. 325', 1, '45.00', 0, '003.', NULL, 1),
(76, '2022-01-01', 'GRAU, Eros Roberto ', 'O direito posto e o direito pressuposto', NULL, '340', '85.742.0186.3', 'São Paulo', 'Malheiros', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p. 209', 1, '199.00', 0, '003.', NULL, 1),
(77, '2022-01-01', 'NASCIMENTO, Edmundo Dantès ', 'Lógica aplicada a advocacia: técnica da persuasão', NULL, '160', NULL, 'São Paulo', 'Saraiva', NULL, NULL, '1987', '1 ed.', NULL, 'ex.1', 'p. 232', 1, '80.00', 0, '003.', NULL, 1),
(78, '2022-01-01', 'NIGRÃO, Ricardo ', 'Manual de direito comercial e de empresa - Volume 3', '347-7/ 34.338.93', '347', '85.02.04128.2', 'São Paulo', 'Saraiva', NULL, NULL, '2004', '1 ed.', 'v.3', 'ex.1', 'p. 753', 1, '16.00', 0, '003.', NULL, 1),
(79, '2022-01-01', '***', 'Constituição Federal do Estado de Santa Catarina', NULL, '342.8164', '85.85949.24.4', 'Florianópolis - SC', 'Insular', NULL, NULL, '1999', '1 ed.', 'v.1', 'ex.1', 'p. 256', 1, '0.00', 1, '003.', NULL, 1),
(80, '2022-01-01', 'MAZZEI, Rodrigo  (Coor.)', 'Questões Processuais do novo código civil', '347(094.4)/ 347.9(81)', '347', '85.98416.03.7', 'Barueri-SP', 'Manole', NULL, NULL, '2006', '1 ed.', 'v.1', 'ex.1', 'p. 543', 1, '80.00', 0, '003.', NULL, 1),
(81, '2022-01-01', 'MACHADO, Antonio Luís. Neto', 'Sociologia Jurídica', '34.301/ 34.301(81)', '340.1', NULL, 'São Paulo', 'Saraiva', NULL, NULL, '1979', '1 ed.', 'v.1', 'ex.1', 'p.420', 1, '30.00', 0, '003.', NULL, 1),
(82, '2022-01-01', 'GONÇALVES, Maria Gabriela Venturoti Perrota Rios', 'Direto Comercial: direito de empresa e sociedades empresariais ', '347.72.338.93', '346.07', '85.02.05441.4', 'São Paulo', 'Saraiva', NULL, NULL, '2005', '1 ed.', 'v.1', 'ex.1', 'p. 171', 1, '17.00', 0, '003.', NULL, 1),
(83, '2022-01-01', 'DINAMARCO, Cândido Rangel ', 'Fundamentos do Processo Civil -  Volume 1', NULL, '341481', '85.7420.255-x', 'São Paulo', 'Malheiros', NULL, NULL, '2000', '1 ed.', 'v.1', 'ex.1', 'p. 695', 1, '70.00', 0, '003.', NULL, 1),
(84, '2022-01-01', 'DINAMARCO, Cândido Rangel ', 'Fundamentos do Processo Civil -  Volume 2', NULL, '341481', '85.7420.255-x', 'São Paulo', 'Malheiros', NULL, NULL, '2000', '1 ed.', 'v.2', 'ex.1', 'p.695', 1, '70.00', 0, '003.', NULL, 1),
(85, '2022-01-01', 'BARROS, Alice Monteiro de Barros', 'Curso de Direito do Trabalho', '34.331(81)', '350', '85.361.0660.3', 'São Paulo', 'LTR Editora', NULL, NULL, '2005', '1 ed.', 'v.1', 'ex.1', 'p. 1317', 1, '77.00', 0, '003.', NULL, 1),
(86, '2022-01-01', 'BARRUFFINI, José Carlos Tosetti ', 'Direito Constitucional - Vol 1', '342.4', '342', '978.85.02.05943.6', 'São Paulo', 'Saraiva', NULL, NULL, '2008', '1 ed.', 'v.1', 'ex.1', 'p. 213', 1, '50.00', 0, '003.', NULL, 1),
(87, '2022-01-01', 'BARRUFFINI, José Carlos Tosetti ', 'Direito Constitucional - Vol 2', '342.4', '342', '978.85.02.05943.6', 'São Paulo', 'Saraiva', NULL, NULL, '2008', '1 ed.', 'v.2', 'ex.1', 'p. 169', 1, '50.00', 0, '003.', NULL, 1),
(88, '2022-01-01', 'BASILE, César Reinaldo Offa ', 'Direito do Trabalho - Duração do dieito de greve - Volume 28', '34.331.89(81)', '350', '978.85.02.07806.2', 'São Paulo', 'Saraiva', NULL, NULL, '2009', '1 ed.', 'v.28', 'ex.1', 'p. 159', 1, '13.00', 0, '003.', NULL, 1),
(89, '2022-01-01', 'BONACCHI, Gabriela  e GROPPI, Angela (Org)', 'O dilema da cidadania: direitos e deveres das mulhers', '323.34', '323', '85.7139.095.9', 'São Paulo', 'UNESP - Universidade Estadual Paulista', NULL, NULL, '1995', '1 ed.', 'v.1', 'ex.1', 'p. 312', 1, '59.00', 0, '003.', NULL, 1),
(90, '2022-01-01', 'LUIZ, Valdemar Pereira de', 'Ações de acidentes de trânsito: doutrina, prática  e jurisprudência', '343.346.656.08', '345', '85241.0035.4', 'Porto Alegre-RS', 'Sagra Editora', NULL, NULL, '1982', '1 ed.', 'v.1', 'ex.1', 'p. 88', 1, '20.00', 0, '003.', NULL, 1),
(91, '2022-01-01', 'DINIZ, Gustavo Saad ', 'Sociedades dependentes de autorização', '347.7/ 346.06', '346.07', '85.7647.037.3', 'São Paulo', 'Thomson', NULL, NULL, '2004', '1 ed.', 'v.1', 'ex.1', 'p. 191', 1, '28.90', 0, '003.', NULL, 1),
(92, '2022-01-01', 'GOTTSCHALK, Orlando Gomes e Elson ', 'Curso de Direito do trabalho', NULL, '350', NULL, 'Rio de Janeiro ', 'Forense Editora', NULL, NULL, '1994', '1 ed.', 'v.1', 'ex.1', 'p. 746', 1, '70.00', 0, '003.', NULL, 1),
(93, '2022-01-01', 'AMARAL, Sylvio do ', 'Falsidade Documental', NULL, '345', NULL, 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '1958', '1 ed.', 'v.1', 'ex.1', 'p. 193', 1, '20.00', 0, '003.', NULL, 1),
(94, '2022-01-01', 'CANÔAS, José Walter ', 'Assinalando alternativas  para a uiversidade e o movimento popular', NULL, '362', NULL, 'Franca-SP', 'UNIVERSIDADE FHDSS', NULL, NULL, '1991', '1 ed.', 'v.1', 'ex.1', 'p. 82', 1, '0.00', 1, '003.', NULL, 1),
(95, '2022-01-01', 'CANÔAS, José Walter ', 'Mundo do trabalho e políticas públicas', '362.85', '360', '978.85.7818.001-0', 'Franca-SP', 'UNIVERSIDADE FHDSS', NULL, NULL, '2007', '1 ed.', 'v.1', 'ex.1', 'p. 178', 1, '0.00', 1, '003.', NULL, 1),
(96, '2022-01-01', 'MEDEIROS, Alexandre Alliprandino ', 'A Efetividade da Hasta pública no processo do trabalho', '347.451.6.347.9.331(81)', '350', NULL, 'São Paulo', 'LTR Editora', NULL, NULL, '2003', '1 ed.', 'v.1', 'ex.1', 'p. 85', 1, '70.00', 0, '003.', NULL, 1),
(97, '2022-01-01', 'ENGLER, Helen Barbosa Raiz  (Org.)', 'Mentalidades e trabalho: do local ao globa, panorama do calçado francano', '362.85', '360', '978.85.7818.025.6', 'Franca-SP', 'UNIVERSIDADE FHDSS', NULL, NULL, '2009', '1 ed.', 'v.1', 'ex.1', 'p. 234', 1, '0.00', 1, '003.', NULL, 1),
(98, '2022-01-01', 'CANÔAS, José Walter ', 'A busca da canastra do mundo do trabalho: caminhos e descaminhos', '3014442', '360', '85.86420.72.7', 'Franca-SP', 'UNIVERSIDADE FHDSS', NULL, NULL, '2005', '1 ed.', 'v.1', 'ex.1', 'p. 262', 1, '0.00', 1, '003.', NULL, 1),
(99, '2022-01-01', 'CHIMENTI, Ricardo Cunha ', 'Teoria e prática dos juizados especiais cíveis', '347.994.(81)(094)', '360', '85.02.02778.6', 'São Paulo', 'Saraiva', NULL, NULL, '1999', '1 ed.', 'v.1', 'ex.1', 'p. 267', 1, '15.00', 0, '003.', NULL, 1),
(100, '2022-01-01', 'MONTEIRO, Washington de Barros ', 'Curso de Direito Civil - Vol 2', NULL, '341481', NULL, 'São Paulo', 'Saraiva', NULL, NULL, '1956', '1 ed.', 'v.2', 'ex.1', 'p.460', 1, '15.00', 0, '003.', NULL, 1),
(101, '2022-01-01', 'Brasil', 'Constituição Federal de 1988 (Atualizada Até 2000)', NULL, '342.0281', '85.309.0988.7', 'Rio de Janeiro ', 'Forense Editora', NULL, NULL, '2000', '1 ed.', 'v.1', 'ex.1', 'p.345', 1, '59.00', 0, '003.', NULL, 1),
(102, '2022-01-01', 'Governo do Estado de SP', 'Lei nº 4.320 de 17 de março de 1964', NULL, '348', NULL, 'São Paulo', 'Governo do Estado de SP', NULL, NULL, '1964', '1 ed.', 'v.1', 'ex.1', 'p.83', 1, '0.00', 1, '003.', NULL, 1),
(103, '2022-01-01', 'DINIZ, Paulo de Matos Ferreira', 'Lei nº 8.112/90 comentada (Regime jurídico dos servidores públicos civis da uinão e legislação  complementar', '350/350.87', '348', '85.7469.279.4', 'Brasília-DF', 'Brasília Jurídica ', NULL, NULL, '2006', '1 ed.', 'v.1', 'ex.1', 'p.927', 1, '89.27', 0, '003.', NULL, 1),
(104, '2022-01-01', 'LEITE, Celso Barroso ', 'O século da aposentadoria', NULL, '362.60981', NULL, 'São Paulo', 'LTR Editora', NULL, NULL, '1993', '1 ed.', 'v.1', 'ex.1', 'p.152', 1, '10.00', 0, '003.', NULL, 1),
(105, '2022-01-01', 'Brasil', 'Coleção de Leis e Estatutos brasileiros - Estatudo de desarmamento e Lei de Imprensa', '344.131.8(81)(094)', '348', '85.7060.318.5(1)/ 85.7060.310.x(2)', 'São Paulo', 'Impressão Oficial', NULL, NULL, '2004', '1 ed.', 'v.1', 'ex.1', 'p.46(1)/ 105(2)', 1, '0.00', 1, '003.', NULL, 1),
(106, '2022-01-01', 'Brasil', 'Coleção de Leis e Estatutos brasileiros -Lei de diretizes e bases da educação nacional e Estatito do Idoso', '344.131.8(81)(094)', '348', '85.7060.309.6(1)/ 85.7060.311.8(2)', 'São Paulo', 'Impressão Oficial', NULL, NULL, '2004', '1 ed.', 'v.1', 'ex.1', 'p.75(1)/ 55(2)', 1, '0.00', 1, '003.', NULL, 1),
(107, '2022-01-01', 'Brasil', 'Código de águas', '344.131.8(81)(094)', '348', '85.7060.317.7', 'São Paulo', 'Impressão Oficial', NULL, NULL, '2004', '1 ed.', 'v.1', 'ex.1', 'p.245', 1, '0.00', 1, '003.', NULL, 1),
(108, '2022-01-01', 'Brasil', 'Constituição do Estado de São Paulo de 5 de outubro de 1989 (atualizada em 2004)', '344.131.8(81)(094)', '348', '85.7060.333.9', 'São Paulo', 'Impressão Oficial', NULL, NULL, '2004', '1 ed.', 'v.1', 'ex.1', 'p.166', 1, '0.00', 1, '003.', NULL, 1),
(109, '2022-01-01', 'Secretaria Estadual de Defesa da Cidadania de SP', 'Direitos  Humanos: um novo caminho', NULL, '348', NULL, 'São Paulo', 'Impressão Oficial', NULL, NULL, '1994', '1 ed.', 'v.1', 'ex.1', 'p.122', 1, '0.00', 1, '003.', NULL, 1),
(110, '2022-01-01', 'ROSA, Luiz Emygdio Rosa ', 'Novo Manual de Direito Financeiro e direito tributário', '34336', '360', NULL, 'Rio de Janeiro ', 'Renovar', NULL, NULL, '1993', '1 ed.', 'v.1', 'ex.1', 'p.458', 1, '40.00', 0, '003.', NULL, 1),
(111, '2022-01-01', 'Brasil - (Org. Juarez de Oliveira )', 'CLT- Consolidação das Leis do Trabalho', '34.331.(81)(094)', '348', '85.02.00405.0', 'São Paulo', 'Saraiva', NULL, NULL, '1992', '1 ed.', 'v.1', 'ex.1', 'p.632', 1, '50.00', 0, '003.', NULL, 1),
(112, '2022-01-01', 'Câmara de Cotia-SP', 'Regimento Interno da Câmara de Cotia-SP', NULL, '348', NULL, 'Cotia-SP0', 'Câmara de Cotia-SP', NULL, NULL, NULL, '1 ed.', 'v.1', 'ex.1', 'p.106', 1, '0.00', 1, '003.', NULL, 1),
(113, '2022-01-01', 'ULTRAMARI, Clovis ', 'Desenvolvimento Local e Regional', NULL, '711', '978.85.8212.352.2', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.152', 1, '27.90', 0, '003.', NULL, 1),
(114, '2022-01-01', 'Carlos Domingos Nigro', 'Insustentabilidade Urbana', NULL, '711.4', '978.85.8212.135.1', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2013', '1 ed.', 'v.1', 'ex.1', 'p.132', 1, '25.00', 0, '003.', NULL, 1),
(115, '2022-01-01', 'Yumi Yamawami e Lucilene Teresa Salvi', 'Introdução a Gestão do Meio urbano', NULL, '711.904.81', '978.85.8212.959.3', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.439', 1, '10.00', 0, '003.', NULL, 1),
(116, '2022-01-01', 'Carlos Augusto Manhanelli', 'Estratégias Eleitorais: marketing político', NULL, '324.7', '85.323.0311-0', 'São Paulo', 'Summus', NULL, NULL, '1988', '1 ed.', 'v.1', 'ex.1', 'p.137', 1, '15.00', 0, '003.', NULL, 1),
(117, '2022-01-01', 'BARCELLOS, João ', 'Feijó e Cepellos: cidadãos brasileiros de Cotia: a hitória de Cotia contada através de seus mais ilustres cidadãos', NULL, '921', '978.85', 'São Paulo', 'Edicon', NULL, NULL, '2008', '1 ed.', NULL, 'ex.1', 'p.71', 1, '20.00', 0, '003.', NULL, 1),
(118, '2022-01-01', 'Darci Marques da Silva', 'Consolidação das Leis do Município de Piracicaba: Código Tributário - Vol. 6', NULL, '348', '978.85.62242.05.2', 'Piracicaba-SP', 'GR editora', NULL, NULL, '2008', '1 ed.', 'v.6', 'ex.1', 'p.284', 1, NULL, 1, '003.', NULL, 1),
(119, '2022-01-01', 'Ivis Gandra da Silva Martins', 'O plano brasil novo e a constituição: aspectos jurídicos e econômicos do plano brasil novo', '34.338.984(81)', '348', '85.218.0047.9', 'Rio de Janeiro ', 'Forense Editora', NULL, NULL, '1990', '1 ed.', 'v.1', 'ex.1', 'p.153', 1, '30.00', 0, '003.', NULL, 1),
(120, '2022-01-01', 'Floriceno Paixão', 'A previdência Social em Perguntas e Respostas e Legislação Correlata', '349.3(81)', '350', '85.713.002.5', 'Porto Alegre-RS', 'Síntese Ltda', NULL, NULL, '1996', '1 ed.', 'v.1', 'ex.1', 'p.796', 1, '20.00', 0, '003.', NULL, 1),
(121, '2022-01-01', 'Supremo Tribunal Federal', 'Ação Direta de insconstitucionalidade: jurisprudência - Vol. 6', NULL, '341205', '857469273.5', 'Brasília-DF', 'Supremo Tribunal Federal STF', NULL, NULL, '2004', '1 ed.', 'v.6', 'ex.1', 'p.447', 1, '10.00', 0, '003.', NULL, 1),
(122, '2022-01-01', 'Assembleia Legislativa de SP', 'Regimento Interno ', NULL, '348', NULL, 'São Paulo', 'Assembleia Legislativa de São Paulo', NULL, NULL, '2003', '1 ed.', 'v.1', 'ex.1', 'p.137', 1, NULL, 1, '003.', NULL, 1),
(123, '2022-01-01', 'Darci Marques da Silva', 'Consolidação das Leis do Município de Piracicaba: Saúde e Desenvolvimento Social - Vol. 5', NULL, '342', '978.85.62242.04.5', 'Piracicaba-SP', 'GR editora', NULL, NULL, '2008', '1 ed.', 'v.5', 'ex.1', 'p.239', 1, NULL, 1, '003.', NULL, 1),
(124, '2022-01-01', 'Rubens Limongi França', 'A irretroabilidade das leis e o direito adquirido', NULL, '340.132.3', '85.203.0204.1', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '1982', '1 ed.', 'v.1', 'ex.1', 'p.307', 1, '0.00', 0, '003.', NULL, 1),
(125, '2022-01-01', 'Delton Croce', 'Erro médico e o direito', '347.141.61', '347', '85.86442.01.1', 'São Paulo', 'Oliveira Mendes', NULL, NULL, '1997', '1 ed.', 'v.1', 'ex.1', 'p.323', 1, '20.00', 0, '003.', NULL, 1),
(126, '2022-01-01', 'Faculdade de Medicina de Botucatu FMB-UNESP', 'Livro comemorativo de 50 anos da Faculdade de Medicina de Botucatu FMB-UNESP', NULL, '378155', '978.85.62693.15.1', 'São Paulo', 'CDG Casa de Soluçoes e Editora', NULL, NULL, '2013', '1 ed.', 'v.1', 'ex.1', 'p.196', 1, '10.00', 0, '003.', NULL, 1),
(127, '2022-01-01', 'Escola do Parlamento de Itapevi', 'Pensando Itapevi - Sec XXI : A administração Pública Pensada para o cidadão do Século XXI', NULL, '352.16', NULL, 'São Paulo', 'Câmara Muncipal de Itapevi', NULL, NULL, '2017', '1 ed.', 'v.1', 'ex.1', 'p.165', 3, NULL, 1, '***', NULL, 1),
(128, '2022-01-01', 'Escola do Parlamento de Itapevi', 'Pensando Itapevi - Sec XXI : A administração Pública Pensada para o cidadão do Século XXI', NULL, '352.16', NULL, 'São Paulo', 'Câmara Muncipal de Itapevi', NULL, NULL, '2017', '1 ed.', 'v.1', 'ex.2', 'p.165', 3, NULL, 1, '***', NULL, 1),
(129, '2022-01-01', 'Escola do Parlamento de Itapevi', 'Pensando Itapevi - Sec XXI : A administração Pública Pensada para o cidadão do Século XXI', NULL, '352.16', NULL, 'São Paulo', 'Câmara Muncipal de Itapevi', NULL, NULL, '2017', '1 ed.', 'v.1', 'ex.3', 'p.165', 3, NULL, 1, '***', NULL, 1),
(130, '2022-01-01', 'Escola do Parlamento de Itapevi', 'Pensando Itapevi - Sec XXI : A administração Pública Pensada para o cidadão do Século XXI', NULL, '352.16', NULL, 'São Paulo', 'Câmara Muncipal de Itapevi', NULL, NULL, '2017', '1 ed.', 'v.1', 'ex.4', 'p.165', 3, NULL, 1, '***', NULL, 1),
(131, '2022-01-01', 'Escola do Parlamento de Itapevi', 'Pensando Itapevi - Sec XXI : A administração Pública Pensada para o cidadão do Século XXI', NULL, '352.16', NULL, 'São Paulo', 'Câmara Muncipal de Itapevi', NULL, NULL, '2017', '1 ed.', NULL, 'ex.5', 'p.165', 3, NULL, 1, '***', NULL, 1),
(132, '2022-01-01', 'Senado Federal', 'Constituição em Miúdos', NULL, '320', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2015', '1 ed.', 'v.1', 'ex.1', 'p.118', 1, NULL, 1, '004.', NULL, 1),
(133, '2022-01-01', 'Senado Federal', 'Constituição em Miúdos', NULL, '320', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2015', '1 ed.', 'v.1', 'ex.2', 'p.118', 1, NULL, 1, '004.', NULL, 1),
(134, '2022-01-01', 'Senado Federal', 'Constituição em Miúdos', NULL, '320', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2015', '1 ed.', 'v.1', 'ex.3', 'p.118', 1, NULL, 1, '004.', NULL, 1),
(135, '2022-01-01', 'Senado Federal', 'Constituição em Miúdos', NULL, '320', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2015', '1 ed.', 'v.1', 'ex.4', 'p.118', 1, NULL, 1, '004.', NULL, 1),
(136, '2022-01-01', 'Senado Federal', 'Constituição em Miúdos', NULL, '320', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2015', '1 ed.', 'v.1', 'ex.5', 'p.118', 1, NULL, 1, '004.', NULL, 1),
(137, '2022-01-01', 'Senado Federal', 'Constituição em Miúdos II', NULL, '320', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2017', '1 ed.', 'v.1', 'ex.1', 'p.118', 1, NULL, 1, '004.', NULL, 1),
(138, '2022-01-01', 'Senado Federal', 'Constituição em Miúdos II', NULL, '320', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2017', '1 ed.', 'v.1', 'ex.1', 'p.118', 1, NULL, 1, '004.', NULL, 1),
(139, '2022-01-01', 'Senado Federal', 'Constituição em Miúdos II', NULL, '320', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2017', '1 ed.', 'v.1', 'ex.1', 'p.118', 1, NULL, 1, '004.', NULL, 1),
(140, '2022-01-01', 'Senado Federal', 'Constituição em Miúdos II', NULL, '320', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2017', '1 ed.', 'v.1', 'ex.1', 'p.118', 1, NULL, 1, '004.', NULL, 1),
(141, '2022-01-01', 'Senado Federal', 'Constituição em Miúdos II', NULL, '320', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2017', '1 ed.', 'v.1', 'ex.1', 'p.118', 1, NULL, 1, '004.', NULL, 1),
(142, '2022-01-01', 'Alexsandro Broedel Lopes', 'Finanças internacionais: uma introdução', '332042', '332', '85.224.3585.5', 'São Paulo', 'Atlas S.A.', NULL, NULL, '2003', '1 ed.', 'v.1', 'ex.1', 'p.140', 1, '20.00', 0, '003.', NULL, 1),
(143, '2022-01-01', 'ZHEBIT, Alexander ', 'Quo Vadis: A ordem Mundial em Perspectiva', NULL, '914', '85.88831.03.1', 'Rio de Janeiro ', 'Bennett Editora do Instituto Metodista', NULL, NULL, NULL, '1 ed.', 'v.1', 'ex.1', 'p.156', 1, '10.00', 0, '003.', NULL, 1),
(144, '2022-01-01', 'Moysés Brejon (Org.)', 'Estrutura e Funcionamento do Ensino de 1º e 2º Graus', NULL, '372', NULL, 'São Paulo', 'Livraria Pioneira Editora', NULL, NULL, '1974', '1 ed.', 'v.1', 'ex.1', 'p.260', 1, '20.00', 0, '003.', NULL, 1),
(145, '2022-01-01', 'POZZONBON, Irineu ', 'A epopéia do café no Paraná', NULL, '981622', '85.906.880.03', 'Londrina - PR', 'Grafmarke', NULL, NULL, '2006', '1 ed.', NULL, 'ex.1', 'p.224', 1, '20.00', 0, '003.', NULL, 1),
(146, '2022-01-01', 'TELAROLLI, Rodolfo ', 'Coleção Brasiliana : Poder Local na República Velha - Vol -364', NULL, '329', NULL, 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '1977', '1 ed.', 'v.364', 'ex.1', 'p.222', 1, '20.00', 0, '003.', NULL, 1),
(147, '2022-01-01', 'HOLANDA, Sergio Buarque de/ CAMPOS, Pedro Moacyr', 'Tomo 2: O Brasil Monarquico Vol. 4 - Declinio e Queda do Império', NULL, '980', NULL, 'São Paulo', 'Difusão européia do Livro', NULL, NULL, '1971', '1 ed.', 'v.II.4', 'ex.1', 'p.390', 1, '20.00', 0, '003.', NULL, 1),
(148, '2022-01-01', 'FERRI, Mário Gulherme Ferri', 'História das ciências no Brasil (Azul)', NULL, '509.81', NULL, 'São Paulo', 'EDUSP EDITORA DA UNIV. DE SP', NULL, NULL, '1979', '1 ed.', 'v.1', 'ex.1', 'p.768', 1, '20.00', 0, '003.', NULL, 1),
(149, '2022-01-01', 'FERRI, Mário Gulherme Ferri', 'História das ciências no Brasil (Vermelho)', NULL, '509.81', NULL, 'São Paulo', 'EDUSP EDITORA DA UNIV. DE SP', NULL, NULL, '1979', '1 ed.', 'v.2', 'ex.1', 'p.390', 1, '20.00', 0, '003.', NULL, 1),
(150, '2022-01-01', 'Sônia Lopes e Sergio Rosso', 'Biologia', NULL, '373', '978.85.02.05375.5', 'São Paulo', 'Saraiva', NULL, NULL, '2005', '1 ed.', 'v.1', 'ex.1', 'p.606', 1, '120.00', 0, '003.', NULL, 1),
(151, '2022-01-01', 'Ary Lex', 'Coleção Atualidades Pedagógicas: Vol. 45 - Biologia Educacional', NULL, '371', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1978', '1 ed.', 'v.45', 'ex.1', 'p.275', 1, '10.00', 0, '003.', NULL, 1),
(152, '2022-01-01', 'Arthur T. Jersild', 'Coleção Atualidades Pedagógicas: Vol. 78 - Psicologia da adolescência', NULL, '371', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1976', '1 ed.', 'v.75', 'ex.1', 'p.590', 1, '10.00', 0, '003.', NULL, 1),
(153, '2022-01-01', 'Rafael Grisi', 'Coleção Atualidades Pedagógicas: Vol. 84 - Didática mínima', NULL, '371', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1978', '1 ed.', 'v.84', 'ex.1', 'p.120', 1, '10.00', 0, '003.', NULL, 1),
(154, '2022-01-01', 'Albert Collette', 'Coleção Atualidades Pedagógicas: Vol. 98 - Introdução à psicologia dinâmica', NULL, '371', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1978', '1 ed.', '98v.1', 'ex.1', 'p.298', 1, '10.00', 0, '003.', NULL, 1),
(155, '2022-01-01', 'Osvaldo Frota-Pessoa', 'Coleção Atualidades Pedagógicas: Vol. 104', NULL, '371', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1985', '1 ed.', 'v.104', 'ex.1', 'p.218', 1, '10.00', 0, '003.', NULL, 1),
(156, '2022-01-01', 'Daniel E. Griffiths', 'Coleção Atualidades Pedagógicas: Teoria da adminsitação escolar Vol. 105', NULL, '371.2', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1978', '1 ed.', 'v.105', 'ex.1', 'p.123', 1, '10.00', 0, '003.', NULL, 1),
(157, '2022-01-01', 'Dante Moreira Leite', 'Coleção Atualidades Pedagógicas: O desenvolvimento da criança - Vol. 109', NULL, '371', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1978', '1 ed.', 'v.109', 'ex.1', 'p.330', 1, '10.00', 0, '003.', NULL, 1),
(158, '2022-01-01', 'Maurice Debesse Gaston Mialaret', 'Coleção Atualidades Pedagógicas:Tratado das ciências pedagógicas- Vol. 114', NULL, '371', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1974', '1 ed.', 'v.114', 'ex.1', 'p.559', 1, '10.00', 0, '003.', NULL, 1),
(159, '2022-01-01', 'Anísio Teixeira', 'Coleção Atualidades Pedagógicas: Vol. 128 - Pequena introdução à filosofia da educação', NULL, '371', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1978', '1 ed.', 'v.128', 'ex.1', 'p.150', 1, '10.00', 0, '003.', NULL, 1),
(160, '2022-01-01', 'WHITE, Emil Henry ', 'Fundamentos de química para as ciências biológicas', NULL, '540', NULL, 'São Paulo', 'UNIVERSIDADE DE SÃO PAULO', NULL, NULL, '1926', '1 ed.', NULL, 'ex.1', 'p.187', 1, '10.00', 0, '003.', NULL, 1),
(161, '2022-01-01', 'Emil Henry White', 'Fundamentos de química para as ciências biológicas', NULL, '540', NULL, 'São Paulo', 'UNIVERSIDADE DE SÃO PAULO', NULL, NULL, '1926', '1 ed.', 'v.1', 'ex.3', 'p.187', 1, '10.00', 0, '003.', NULL, 1),
(162, '2022-01-01', 'Emil Henry White', 'Fundamentos de química para as ciências biológicas', NULL, '540', NULL, 'São Paulo', 'UNIVERSIDADE DE SÃO PAULO', NULL, NULL, '1926', '1 ed.', 'v.1', 'ex.4', 'p.187', 1, '10.00', 0, '003.', NULL, 1),
(163, '2022-01-01', 'STAHL, Franklin W. ', 'Os mecanismos da Herança', NULL, '576', NULL, 'São Paulo', 'UNIVERSIDADE DE SÃO PAULO', NULL, NULL, '1970', '1 ed.', NULL, 'ex.1', 'p.242', 1, '10.00', 0, '003.', NULL, 1),
(164, '2022-01-01', 'Valdemir Antonio Rodrigues', 'A educação Ambiental na trilha', '(21)372.3570981', '363', NULL, 'Botucatu-SP', 'Tipomic LTDA', NULL, NULL, '2000', '1 ed.', 'v.1', 'ex.1', 'p.106', 1, '20.00', 0, '003.', NULL, 1),
(165, '2022-01-01', 'Ali Chahin', 'e-gov.br: a próxima revolução brasileira: eficiência, qualidade e democracia: o governo eletrônico no Brasil e no mundo', NULL, '004', '85.87918.93.1', 'São Paulo', 'Pearson ', NULL, NULL, '2004', '1 ed.', 'v.1', 'ex.1', 'p.380', 1, '20.00', 0, '003.', NULL, 1),
(166, '2022-01-01', 'BIANCHINI, Ingrid de Souza Santos  (Org.)', 'Seu progresso é assustador - Coletânia de memórias e desejos', NULL, '921', '97.85.8096.009.9', 'Itatiba-SP', 'Bento', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.49', 1, '20.00', 0, '003.', NULL, 1),
(167, '2022-01-01', 'BIANCHINI, Ingrid de Souza Santos  (Org.)', 'Seu progresso é assustador - Coletânia de memórias e desejos', NULL, '921', '97.85.8096.009.9', 'Itatiba-SP', 'Bento', NULL, NULL, '2012', '1 ed.', NULL, 'ex.2', 'p.49', 1, '20.00', 0, '003.', NULL, 1),
(168, '2022-01-01', 'HEFLINGER JÚNIOR, José Eduardo', 'Um pouco da história de Limeira - Volume II', NULL, '981612', '978.85.98942.29.2', 'Limeira- SP', 'Unigráfica', NULL, NULL, '2017', '1 ed.', 'v.2', 'ex.1', 'p.199', 1, '20.00', 0, '003.', NULL, 1),
(169, '2022-01-01', 'Jeffrey M. Wooldridge', 'Introdução à econometria: uma  abordagem moderna', NULL, '330015195', '978.85.221.0446.8', 'São Paulo', 'Cengage Learning', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.701', 1, '20.00', 0, '003.', NULL, 1),
(170, '2022-01-01', 'MARTINEZ, Helio ', 'Fragmentos da Sensibilidade', NULL, '158.1', NULL, 'São Paulo', 'Cipola Inteligência gráfica', NULL, NULL, '2008', '1 ed.', NULL, 'ex.1', 'p.141', 1, '20.00', 0, '003.', NULL, 1),
(171, '2022-01-01', 'Joseph S. Nye', 'O futuro do poder', '316.33', '323', '987.85.64065.29.1', 'São Paulo', 'Benvirá', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.333', 1, '50.00', 0, '003.', NULL, 1),
(172, '2022-01-01', 'Jacques Brasseul', 'História Econômica do mundo - das origens aos subprimes', NULL, '332', '987.989.8285.41.6', 'Lisboa-Portugal', 'Texto e Gráfica Edições', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.351', 1, '30.00', 0, '003.', NULL, 1),
(173, '2022-01-01', 'Niall Ferguson', 'A ascensão do dinheiro: a história financeira do mundo', NULL, '332.49', '987.85.7665.445.2', 'São Paulo', 'Planeta Brasil', NULL, NULL, '2009', '1 ed.', 'v.1', 'ex.1', 'p.418', 1, '30.00', 0, '003.', NULL, 1),
(174, '2022-01-01', 'COLEMAN, Grahan ', 'O livro Tibetano dos mortos: a grande libertação pela auscultação nos estdos intermediários', NULL, '181', '978.85.7827.282.1', 'São Paulo', 'WMF Martins Fontes', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p.483', 1, '28.00', 0, '003.', NULL, 1),
(175, '2022-01-01', 'Benjamin Graham', 'O investigador inteligente', '336.76', '332678', '987.85.209.2000.8', 'Rio de Janeiro ', 'Nova Fronteira S.A.', NULL, NULL, '2007', '4 ed.', 'v.1', 'ex.1', 'p.671', 1, '25.00', 0, '003.', NULL, 1),
(176, '2022-01-01', 'MACFARLENE, Robert Macfarlane', 'Montanha da mente: história de um fascínio', NULL, '823', '85.7302.665.0', 'Rio de Janeiro ', 'Objetiva Ltda', NULL, NULL, '2005', '1 ed.', NULL, 'ex.1', 'p.282', 1, '26.00', 0, '003.', NULL, 1),
(177, '2022-01-01', 'Adam Smith', 'Riqueza as nações (Coleção folha livros que mudaram o mundo) Volume 4', NULL, '330153', '978.85.63270.25.2', 'São Paulo', 'Folha de São Paulo', NULL, NULL, '2010', '1 ed.', 'v.4', 'ex.1', 'p.432', 1, '30.00', 0, '003.', NULL, 1),
(178, '2022-01-01', 'Eduardo Porter', 'O preço das Coisas', '338.5', '338521', '978.85.390.0246.7', 'Rio de Janeiro ', 'Objetiva Ltda', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.287', 1, '15.00', 0, '003.', NULL, 1),
(179, '2022-01-01', 'Daniel Yoshio Shinohara', 'Parcerias público-privadas no Brasil', NULL, '338730981', '978.85.204.2729.3', 'Barueri-SP', 'Manole', NULL, NULL, '2008', '1 ed.', 'v.1', 'ex.1', 'p.108', 1, '20.00', 0, '003.', NULL, 1),
(180, '2022-01-01', 'ANDRADE, Aurélio L. ', 'Pensamento Sistêmico: caderno de campo: o desafio da mudança sustentada nas organizações e na sociedade', '65.016.7', '658', '85.363.0700.5', 'Porto Alegre-RS', 'Bookman', NULL, NULL, '2006', '1 ed.', NULL, 'ex.1', 'p.488', 1, '20.00', 0, '003.', NULL, 1),
(181, '2022-01-01', 'FOLLET, Ken', 'Queda de Gigantes', '821.111.3', '823', '978.85.99296.85.1', 'Rio de Janeiro ', 'Sextante', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p.910', 1, '50.00', 0, '003.', NULL, 1),
(182, '2022-01-01', 'MARCUM, David ', 'O fator ego: como o ego pode ser seu maior aliado ou seu maior inimigo', '65.011.4', '658409019', '978.85.7542.487.2', 'Rio de Janeiro ', 'Sextante', NULL, NULL, '2009', '1 ed.', NULL, 'ex.1', 'p.238', 1, '15.00', 0, '003.', NULL, 1),
(183, '2022-01-01', 'José Carlo Libâneo', 'Didática', NULL, '371.3', '987.85.249.0298.7', 'São Paulo', 'Cortez', NULL, NULL, '1994', '1 ed.', 'v.1', 'ex.1', 'p.263', 1, '20.00', 0, '003.', NULL, 1),
(184, '2022-01-01', 'José Vicente de Sá Pimentel', 'O Brasil, os Brics e a Agenda Internacional', '339.92', '339', '978.85.7631.373.1', 'Brasília-DF', 'Fundação Alexandre de Gusmão ', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.344', 1, '15.00', 0, '003.', NULL, 1),
(185, '2022-01-01', 'Jeremy Rifkin', 'A terceira revolução industrial', NULL, '333', '978.85.7680.181.8', 'São Paulo', 'M.Books do Brasil', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.320', 1, '20.00', 0, '003.', NULL, 1),
(186, '2022-01-01', 'LOURES, Rodrigo C. da Rocha ', 'Ideias inspiradoras da minha gestão 2004-2011', '92', '658', '978.85.61268.05.3', 'Curitiba-PR', 'Fiep', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p.326', 1, '30.00', 0, '003.', NULL, 1),
(187, '2022-01-01', 'Muhammad Yunus', 'O banqueiro dos pobres', NULL, '330.01', '978.85.08.07503.4', 'São Paulo', 'Ática', NULL, NULL, '2008', '1 ed.', 'v.1', 'ex.1', 'p.343', 1, '20.00', 0, '003.', NULL, 1),
(188, '2022-01-01', 'KARDEC, Allan', 'O evangelho segundo o espiritísmo', NULL, '133.9', '85.7253.036.3', 'São Paulo', 'Petit', NULL, NULL, '1997', '1 ed.', NULL, 'ex.1', 'p.317', 1, '20.00', 0, '003.', NULL, 1),
(189, '2022-01-01', 'ROBBINS, Anthony ', 'Poder sem limites: o caminho do sucesso pessoal pela programação neurolinguistica ', '159947', '158.2', '978.85.7123.953.1', 'Rio de Janeiro ', 'Best Seller Ltda', NULL, NULL, '2009', '1 ed.', NULL, 'ex.1', 'p.384', 1, '20.00', 0, '003.', NULL, 1),
(190, '2022-01-01', 'Mike Davis', 'Cidades Mortas', '316.334.56(73)', '307760973', '978.85.01.07282.5', 'Rio de Janeiro ', 'Record', NULL, NULL, '2007', '1 ed.', 'v.1', 'ex.1', 'p.491', 1, '20.00', 0, '003.', NULL, 1),
(191, '2022-01-01', 'ANDRADE, Rui Otávio Bernarde de', 'Princípios da Negociação: ferramentas de gestão', NULL, '658.405', '85.224.3703.3', 'São Paulo', 'Atlas S.A.', NULL, NULL, '2004', '1 ed.', NULL, 'ex.1', 'p.274', 1, '20.00', 0, '003.', NULL, 1),
(192, '2022-01-01', 'SENA, Cristina ', 'Encontro Marcado', NULL, '133.93', '857253082.7', 'São Paulo', 'Petit', NULL, NULL, '2002', '1 ed.', NULL, 'ex.1', 'p.187', 1, '20.00', 0, '003.', NULL, 1),
(193, '2022-01-01', 'Senado Federal', 'Mercosul: legislação e textos básicos', '339.923(8)', '348', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p.656', 1, '10.00', 0, '003.', NULL, 1),
(194, '2022-01-01', 'Senado Federal', 'Código Civil Brasileiro e Legislações Correlatas (Atualizado em Junho/2012', NULL, '348', '978.85.7018.457.3', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2012', '1 ed.', 'v.2', 'ex.1', 'p.543', 1, '10.00', 0, '003.', NULL, 1),
(195, '2022-01-01', 'MASSARANI, Emanuel von Lauenstein ', 'Italia - Brasil: Arte 2011-2012', NULL, '709.4', NULL, 'São Paulo', 'HR Gráfica e Editora', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.339', 1, '15.00', 0, '006.', NULL, 1),
(196, '2022-01-01', 'Emanuel von Lauenstein Massarani', 'Italia - Brasil: Arte 2011-2012', NULL, '709.4', NULL, 'São Paulo', 'HR Gráfica e Editora', NULL, NULL, '2012', '1 ed.', 'v.1', NULL, 'p.339', 1, '15.00', 0, '006.', NULL, 1),
(197, '2022-01-01', 'FERREIRA, Josué dos Santos Dr.', 'A história do poder legislativo no Brasil -Através dos tempos 1826-2009', NULL, '921', '978.85.6375.101.0', 'Brasília-DF', 'IDELB', NULL, NULL, '2016', '1 ed.', NULL, 'ex.1', 'p.323', 1, '20.00', 0, '003.', NULL, 1),
(198, '2022-01-01', 'FERREIRA, Josué dos Santos Dr.', 'A história do poder legislativo no Brasil -Através dos tempos 1826-2009', NULL, '921 (EST.)', '978.85.6375.101.0', 'Brasília-DF', 'IDELB', NULL, NULL, '2016', '1 ed.', NULL, 'ex.2', 'p.323', 1, '10.00', 0, '003.', NULL, 1),
(199, '2022-01-01', 'Maria Barr (Org.)', 'Comissão da Primeira Infância - 11 anos de Audiências Públicas sobre a primeira infancia', NULL, '305.23', '978.85.7018.883.0', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2018', '1 ed.', 'v.1', 'ex.1', 'p.415', 1, '30.00', 0, '003.', NULL, 1),
(200, '2022-01-01', 'Victor Eduardo Rios Gonçalves', 'Curso de direito penal: parte especial (art. 121-183)', '343.81', '345', '978.85.02.63569.2', 'São Paulo', 'Saraiva', NULL, NULL, '2016', '1 ed.', 'v.1', 'ex.1', 'p.476', 1, '15.00', 0, '003.', NULL, 1),
(201, '2022-01-01', 'Carlos Alberto Alvaro de Oliveira (Coord.)', 'A nova execução de títulos extajudiciais', '347.952(81)(094)', '347', '978.85.309.2574.1', 'Rio de Janeiro ', 'Forense Editora', NULL, NULL, '2007', '1 ed.', 'v.1', 'ex.1', 'p.276', 1, '15.00', 0, '003.', NULL, 1),
(202, '2022-01-01', 'EHRBAR, Al ', 'EVA: valor econômico agregado: a verdeira chave para a criação de riqueza', '658.15', '658.15', '85.7303.225.1', 'Rio de Janeiro ', 'Qualitymark', NULL, NULL, '1999', '1 ed.', NULL, 'ex.1', 'p.183', 1, '15.00', 0, '003.', NULL, 1),
(203, '2022-01-01', 'Andrea Fernandes Andrezo', 'Mercado Financeiro: asperctos históricos e conceituais', NULL, '332.60981', '85.221.0303.8', 'São Paulo', 'Thomson', NULL, NULL, '2002', '1 ed.', 'v.1', 'ex.1', 'p.373', 1, '15.00', 0, '003.', NULL, 1);
INSERT INTO `librarycollection` (`id`, `registrationDate`, `author`, `title`, `cdu`, `cdd`, `isbn`, `local`, `publisher_edition`, `number`, `month`, `year`, `edition`, `volume`, `copyNumber`, `pageNumber`, `typeAcquisitionId`, `price`, `prohibitedSale`, `provider`, `exclusionInfoTerm`, `registeredByUserId`) VALUES
(204, '2022-01-01', 'Tom Copeland e Tim Koller', 'Avaliação de empresas - Valuation - Calculando e gerenciando o valor das empresas', NULL, '332', '85.346.1361.3', 'São Paulo', 'Makron Books Ltda', NULL, NULL, '2002', '1 ed.', 'v.1', 'ex.1', 'p.499', 1, '15.00', 0, '003.', NULL, 1),
(205, '2022-01-01', 'Robertônio Santos Pessoa', 'Curso de direito administrativo moderno', '342.9', '342', '85.309.1638.7', 'Rio de Janeiro ', 'Forense Editora', NULL, NULL, '2003', '1 ed.', 'v.1', 'ex.1', 'p.688', 1, '15.00', 0, '003.', NULL, 1),
(206, '2022-01-01', 'RODRIGUES, Ricardo Vélez ', 'Coleção Brasil 500 anos - Castilho: uma filosofia da República', NULL, '980', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2000', '1 ed.', 'v.1', 'ex.1', 'p.296', 1, '0.00', 1, '003.', NULL, 1),
(207, '2022-01-01', 'MONTENEGRO, João Alfredo de Souza ', 'Coleção Brasil 500 anos - Castilho: O discurso autoritário de Cairu', NULL, '980', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2000', '1 ed.', 'v.1', 'ex.1', 'p.338', 1, '0.00', 1, '003.', NULL, 1),
(208, '2022-01-01', 'SARNEY, José  e COSTA, Pedro ', 'Coleção Brasil 500 anos - Amapá: a terra onde o Brasil começa', NULL, '980', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2000', '1 ed.', 'v.1', 'ex.1', 'p.269', 1, '0.00', 1, '003.', NULL, 1),
(209, '2022-01-01', 'Seleção de artigos fac-similar', 'Coleção Brasil 500 anos - Revista Americana: uma iniciativa pioneira de cooperação intelectual', NULL, '980', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2001', '1 ed.', 'v.1', 'ex.1', 'p.622', 1, '0.00', 1, '003.', NULL, 1),
(210, '2022-01-01', 'Antonio Vieira', 'Coleção Brasil 500 anos - De profecia e Inquisição', NULL, '980', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2001', '1 ed.', 'v.1', 'ex.1', 'p.276', 1, '0.00', 1, '003.', NULL, 1),
(211, '2022-01-01', 'SILVA, Rafael Silveira e (Org.)', 'Coleção 30 anos da Constituição: evolução, desafios e perspectivas para o futuro (Volume I)', NULL, '980', '978.85.7018.956.1', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2018', '1 ed.', 'v.1', 'ex.1', 'p.403', 1, '0.00', 1, '003.', NULL, 1),
(212, '2022-01-01', 'SILVA, Rafael Silveira e (Org.)', 'Coleção 30 anos da Constituição: evolução, desafios e perspectivas para o futuro (Volume II)', NULL, '980', '978.85.7018.957-8', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2018', '1 ed.', 'v.2', 'ex.1', 'p.430', 1, '0.00', 1, '003.', NULL, 1),
(213, '2022-01-01', 'SILVA, Rafael Silveira e (Org.)', 'Coleção 30 anos da Constituição: evolução, desafios e perspectivas para o futuro (Volume III)', NULL, '980', '978.85.7018.958.5', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2018', '1 ed.', 'v.3', 'ex.1', 'p.473', 1, '15.00', 0, '003.', NULL, 1),
(214, '2022-01-01', 'SILVA, Rafael Silveira e (Org.)', 'Coleção 30 anos da Constituição: evolução, desafios e perspectivas para o futuro (Volume IV)', NULL, '980', '978.85.7018.959.2', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2018', '1 ed.', 'v.4', 'ex.1', 'p.329', 1, '15.00', 0, '003.', NULL, 1),
(215, '2022-01-01', 'NABUCO, Joaquim ', 'Coleção Biblioteca Básica Brasileira - Minha Formação: Joaquim Nabuco', NULL, '923', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2001', '1 ed.', 'v.1', 'ex.1', 'p.243', 1, '0.00', 1, '003.', NULL, 1),
(216, '2022-01-01', 'FRANCO, Afonso Arinos de Melo ', 'Coleção Biblioteca Básica Brasileira - Rodrigo Alves: O apogeu e declínio do presidencialismo (Volume I)', NULL, '923', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2001', '1 ed.', 'v.1', 'ex.1', 'p.552', 1, '0.00', 1, '003/2018', NULL, 1),
(217, '2022-01-01', 'FRANCO, Afonso Arinos de Melo ', 'Coleção Biblioteca Básica Brasileira - Rodrigo Alves: O apogeu e declínio do presidencialismo (Volume II)', NULL, '923', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2001', '1 ed.', 'v.1', 'ex.1', 'p.530', 1, '0.00', 1, '003/2018', NULL, 1),
(218, '2022-01-01', 'CAMPOS, Francisco ', 'O Estado Nacional', NULL, '320.981', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2001', '1 ed.', 'v.1', 'ex.1', 'p.226', 1, '0.00', 1, '003/2018', NULL, 1),
(219, '2022-01-01', 'GUANABARA, Alcindo ', 'A presidêcia Campos Sales', NULL, '320.891', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2002', '1 ed.', 'v.1', 'ex.1', 'p.346', 1, '0.00', 1, '003.', NULL, 1),
(220, '2022-01-01', 'BARROSO, Gustavo ', 'Coleção Edições do Senado: História Militar do Brasil      (Volume 192)', NULL, '355.48', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2019', '1 ed.', 'v.1', 'ex.1', 'p.268', 1, '0.00', 1, '003.', NULL, 1),
(221, '2022-01-01', 'TALLES, Gilberto Mendonça ', 'Coleção Edições do Senado: Defesa da Poesia          (Volume 241)', NULL, '808.81009', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2017', '1 ed.', 'v.1', 'ex.1', 'p.384', 1, '0.00', 1, '003.', NULL, 1),
(222, '2022-01-01', 'ARAUJO, Antonio Martins de ', 'Coleção Edições do Senado: A língua portuguesa no tempo e no espaçõ      (Volume 242)', NULL, '469.09', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2017', '1 ed.', 'v.1', 'ex.1', 'p.480', 1, '0.00', 1, '003.', NULL, 1),
(223, '2022-01-01', 'Johann von R. Spix', 'Coleção Edições do Senado: Viagem pelo Brasil 1817-1820    (Volume 244-A) Vol. 1', NULL, '918.1', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2017', '1 ed.', 'v.1', 'ex.1', 'p.348', 1, '0.00', 1, '003.', NULL, 1),
(224, '2022-01-01', 'SPIX, Johann von R. ', 'Coleção Edições do Senado: Viagem pelo Brasil 1817-1820    (Volume 244-B) Vol. 2', NULL, '918.1', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2017', '1 ed.', 'v.1', 'ex.1', 'p.428', 1, '0.00', 1, '003.', NULL, 1),
(225, '2022-01-01', 'SPIX, Johann von R. ', 'Coleção Edições do Senado: Viagem pelo Brasil 1817-1820    (Volume 244-C) Vol. 3', NULL, '918.1', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2017', '1 ed.', 'v.1', 'ex.1', 'p.486', 1, '0.00', 1, '003.', NULL, 1),
(226, '2022-01-01', 'PAIM, Antonio ', 'Coleção Edições do Senado:  A questão indígena      (Volume 247)', NULL, '980.41', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2017', '1 ed.', 'v.1', 'ex.1', 'p.200', 1, '0.00', 1, '003.', NULL, 1),
(227, '2022-01-01', 'CÍCERO, Marco Tulio ', 'Coleção Edições do Senado: Da República (Volume 250)', NULL, '320.01', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2019', '1 ed.', 'v.1', 'ex.1', 'p.104', 1, '0.00', 1, '003.', NULL, 1),
(228, '2022-01-01', 'PORTO, Walter Costa ', 'Coleção Edições do Senado: Eleições presidenciais no Brasil: primeira república (Volume 256)', NULL, '341.28', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2019', '1 ed.', 'v.1', 'ex.1', 'p.256', 1, '0.00', 1, '003.', NULL, 1),
(229, '2022-01-01', 'Carlos Takahashi', 'Os 3 B\'s do Cerimonial: Introdução às Normas do Cerimonial Público Brasileiro', NULL, '340', NULL, '***', '***', NULL, NULL, NULL, '1 ed.', 'v.1', 'ex.1', 'p.256', 1, '20.00', 0, '003.', NULL, 1),
(230, '2022-01-01', 'Juarez Oliveira (Org.)', 'Código Tributário Nacional', '34.336.2.81.094.9', '343', '85.02.00448.4', 'Brasília-DF', 'Saraiva', NULL, NULL, '1994', '1 ed.', 'v.1', 'ex.1', 'p.564', 1, '20.00', 0, '003.', NULL, 1),
(231, '2022-01-01', 'Amauri Mascaro Nascimento', 'Curso de direito processual do trabalho', '347.9.331/ 347.9.331.81', '347', '85.02.00454.9', 'São Paulo', 'Saraiva', NULL, NULL, '1990', '1 ed.', 'v.1', 'ex.1', 'p.371', 1, '20.00', 0, '003.', NULL, 1),
(232, '2022-01-01', 'Amauri Mascaro Nascimento', 'Curso de direito processual do trabalho', '347.9.331', '347', '85.02.02342.x', 'São Paulo', 'Saraiva', NULL, NULL, '1998', '1 ed.', 'v.1', 'ex.1', 'p.668', 1, '20.00', 0, '003.', NULL, 1),
(233, '2022-01-01', 'Amauri Mascaro Nascimento', 'Curso de Direito do Trabalho', '34.331/ 34.331.81', '343', '85.02.00455.7', 'São Paulo', 'Saraiva', NULL, NULL, '1992', '1 ed.', 'v.1', 'ex.1', 'p.700', 1, '20.00', 0, '003.', NULL, 1),
(234, '2022-01-01', 'Arlindo Philippi Jr. e Alaôr Caffé Alves', 'Curso Interdisciplinar de Direito Ambiental', '349.6', '348', '85.204.2187.3', 'Barueri-SP', 'Manole', NULL, NULL, '2005', '1 ed.', 'v.1', 'ex.1', 'p.953', 1, '20.00', 0, '003.', NULL, 1),
(235, '2022-01-01', 'Theodoro Negrão, José Roberto F. Gouveia, Luis Gulherme A. Bondioli e João Francisco N. da Fonseca', 'Código de Processo Civil e legislação processual em vigor (Revição de 1996)', '347.9(81)(094.4)', '348', '85.02.01816.7', 'São Paulo', 'Saraiva', NULL, NULL, '1996', '1 ed.', 'v.1', 'ex.1', 'p.1419', 1, '20.00', 0, '003.', NULL, 1),
(236, '2022-01-01', 'Daniel Amorim Assunção Neves', 'Manual de direito processual civil (Volume Único)', '347.91.95.81', '341.46', '978.85.442.0990.5', 'Salvador - BA', 'Jus Podivm', NULL, NULL, '2017', '1 ed.', 'v.1', 'ex.1', 'p.1808', 1, '20.00', 0, '003.', NULL, 1),
(237, '2022-01-01', 'Ricardo Fiuza', 'Novo Código Civil Comentado', '347.81.094.4', '341.481', '85.03780.3', 'São Paulo', 'Saraiva', NULL, NULL, '2002', '1 ed.', 'v.1', 'ex.1', 'p.1843', 1, '20.00', 0, '003.', NULL, 1),
(238, '2022-01-01', 'STF', ' O Supremo Tribunal Federal e as comissões parlamentares de inquérito', NULL, '341.4191', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2006', '1 ed.', 'v.1', 'ex.1', 'p.927', 1, '20.00', 0, '003.', NULL, 1),
(239, '2022-01-01', 'CUNHA, Celso  / CINTRA, Luis F. Lindley ', 'Nova Gramática de Portugues contemporâneo', NULL, '469.5', NULL, 'Rio de Janeiro ', 'Nova Fronteira S.A.', NULL, NULL, '1985', '1 ed.', NULL, 'ex.1', 'p.714', 1, '20.00', 0, '003.', NULL, 1),
(240, '2022-01-01', 'Miguel Reale', 'Filosofia do Direito - Volume 1', NULL, '340.1', NULL, 'São Paulo', 'Saraiva', NULL, NULL, '1957', '1 ed.', 'v.1', 'ex.1', 'p.273', 1, '20.00', 0, '003.', NULL, 1),
(241, '2022-01-01', 'Miguel Reale', 'Filosofia do Direito - Volume 2', NULL, '340.1', NULL, 'São Paulo', 'Saraiva', NULL, NULL, '1957', '1 ed.', 'v.1', 'ex.1', 'p.653', 1, '20.00', 0, '003.', NULL, 1),
(242, '2022-01-01', 'Antônio Luís da Câmara Leal', 'Da prescrição e da decadência: teoria geral do direito civil', NULL, '341481', NULL, 'Rio de Janeiro ', 'Forense Editora', NULL, NULL, '1959', '1 ed.', 'v.1', 'ex.1', 'p.428', 1, '20.00', 0, '003.', NULL, 1),
(243, '2022-01-01', 'Alexandre A. Rocha (Coord.)', 'Agenda Legislativa para o desenvolvimento nacional', NULL, '320', '978.85.7018.349.1', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2007', '1 ed.', 'v.1', 'ex.1', 'p.496', 1, '20.00', 0, '003.', NULL, 1),
(244, '2022-01-01', 'Câmara Municipal de Hortolândia', 'Regimento Inerno da Câmara Municipal de Hortolândia (2008-2009)', NULL, '348', NULL, 'Hortolândia-SP', '***', NULL, NULL, '2008', '1 ed.', 'v.1', 'ex.1', 'p.127', 1, '30.00', 0, '003.', NULL, 1),
(245, '2022-01-01', 'Maximilianus Cláudio Américo Führer', 'Manual de Direito Público e Privado', '34(035)', '341', '85.203.2334.0', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '2004', '1 ed.', 'v.1', 'ex.1', 'p.373', 1, '30.00', 0, '003.', NULL, 1),
(246, '2022-01-01', 'Ricardo Rodrigo Gama', 'Manual de Direito Constitucional', '342', '342', '85.7394.050.6', 'Curitiba-PR', 'Jurua', NULL, NULL, '1999', '1 ed.', 'v.1', 'ex.1', 'p.322', 1, '30.00', 0, '003.', NULL, 1),
(247, '2022-01-01', 'Gizelda Maria Scalon Seixas Santos', 'União Estável e Alimentos', NULL, '341.0281', NULL, 'São Paulo', 'Led - Editora de Direito', NULL, NULL, '1996', '1 ed.', 'v.1', 'ex.1', 'p.182', 1, '30.00', 0, '003.', NULL, 1),
(248, '2022-01-01', 'Amílcar de Araújo Falcão', 'Fato gerador da obrigação tributária', '34.336.126.3(81)', '343', '85309.0123-1', 'Rio de Janeiro ', 'Forense Editora', NULL, NULL, '1994', '1 ed.', 'v.1', 'ex.1', 'p.94', 1, '30.00', 0, '003.', NULL, 1),
(249, '2022-01-01', 'Sahid Maluf', 'Direito constitucional', '342/ 342(81)', '342.481', NULL, 'São Paulo', 'Saraiva', NULL, NULL, '1984', '1 ed.', 'v.1', 'ex.1', 'p.524', 1, '30.00', 0, '003.', NULL, 1),
(250, '2022-01-01', 'José Eduardo Sabo Paes', 'Fundações, associações e entidades de interesse social: aspectos jurídicos, adminstrativos, contábeis, trabalhistas e tributários', NULL, '341.481', '85.7469.281.6', 'Brasília-DF', 'Brasília Jurídica ', NULL, NULL, '2006', '1 ed.', 'v.1', 'ex.1', 'p.1012', 1, '30.00', 0, '003.', NULL, 1),
(251, '2022-01-01', 'Washington de Barros Monteiro', 'Curso de Direito Civil ', '347', '341.481', '85.02.00374.7', 'São Paulo', 'Saraiva', NULL, NULL, '1985', '1 ed.', 'v.1', 'ex.1', 'p.323', 1, '20.00', 0, '003.', NULL, 1),
(252, '2022-01-01', 'Washington de Barros Monteiro', 'Curso de Direito Civil  - Direito da Obrigações         (Parte 1)', '347', '341.481', NULL, 'São Paulo', 'Saraiva', NULL, NULL, '1984', '1 ed.', 'v.1', 'ex.1', 'p.350', 1, '30.00', 0, '003.', NULL, 1),
(253, '2022-01-01', 'Câmra Municipal de Parahybuna- SP', 'Código de Posturas da Câmara Muncipal de Parahybuna SP', NULL, '348', NULL, 'São Paulo', 'Jac - Gráfica e Editora', NULL, NULL, '1907', '1 ed.', 'v.1', 'ex.1', 'p.49', 1, '40.00', 0, '003.', NULL, 1),
(254, '2022-01-01', 'Leonardo Carneiro da Cunha', 'Direito Intertemporal e o novo código de processo civil', NULL, '347.91', '978.85.309.7155.7', 'Rio de Janeiro ', 'Forense Editora', NULL, NULL, '2016', '1 ed.', 'v.1', 'ex.1', 'p.183', 1, '30.00', 0, '003.', NULL, 1),
(255, '2022-01-01', 'Hely Lopes Meirelles', 'Licitações e contratos adminstrativos', '351.712(81) / 351.712.032.3(81)', '352.16', NULL, 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '1973', '1 ed.', 'v.1', 'ex.1', 'p.361', 1, '60.00', 0, '003.', NULL, 1),
(256, '2022-01-01', 'Marcus Vinícius Rios Gonçalves', 'Novo Curso de direito processual civil - Vol. 1  Teoria Geral e processo de conhecimento', '347.9(81)', '347', '978.85.02.18714.6', 'São Paulo', 'Saraiva', NULL, NULL, '2013', '1 ed.', 'v.1', 'ex.1', 'p.487', 1, '39.00', 0, '003.', NULL, 1),
(257, '2022-01-01', 'Marcus Vinícius Rios Gonçalves', 'Novo Curso de direito processual civil - Vol. 2   Processo de conhecimento (parte 2) e Procedimentos Especiais', '347.9(81)', '347', '978.85.02.19094.8', 'São Paulo', 'Saraiva', NULL, NULL, '2013', '1 ed.', 'v.2', 'ex.1', 'p.493', 1, '39.00', 0, '003.', NULL, 1),
(258, '2022-01-01', 'Marcus Vinicius Rios Gonçalves', 'Novo Curso de Direito Processual Civil: Vol. 3 Execução e processo cautelar ', '347.9(81)', '347', '978.85.02.9097.9', 'São Paulo', 'Saraiva', NULL, NULL, '2013', '1 ed.', 'v.3', 'ex.1', 'p.351', 1, '39.00', 0, '003.', NULL, 1),
(259, '2022-01-01', 'Câmara Municipal de Vinhedo- SP', 'Lei Orgânica do Município de Vinhedo -SP', NULL, '348', NULL, 'São Paulo', '***', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.95', 1, NULL, 1, '003.', NULL, 1),
(260, '2022-01-01', 'Amauri Mascaro Nascimento', 'Direito Sindical ', '34.331.88/ 34.331.88(81)', '343', '85.02.0541.3', 'São Paulo', 'Saraiva', NULL, NULL, '1991', '1 ed.', 'v.1', 'ex.1', 'p.472', 1, '25.00', 0, '003.', NULL, 1),
(261, '2022-01-01', 'Rodrigo Lopes Lourenço', 'Controle da constitucionalidade à luz da jurisprudência do STF', '342(81)', '342', '85.309.0660.8', 'Rio de Janeiro ', 'Forense Editora', NULL, NULL, '1999', '1 ed.', 'v.1', 'ex.1', 'p.170', 1, '15.00', 0, '003.', NULL, 1),
(262, '2022-01-01', 'Regis Fernandes de Oliveira  e Estevão Horvath', 'Manual de Direito Financeiro', '34.336(81)', '343', '85.203.1728.6', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '1999', '1 ed.', 'v.1', 'ex.1', 'p.150', 1, '10.00', 0, '003.', NULL, 1),
(263, '2022-01-01', 'Governo Federal', 'Coleção Manual de Legislação Atlas: Normas Gerais do Direito Financeiro (Lei n 4.320 de 17 de março de 1964)', NULL, '348', NULL, 'São Paulo', 'Atlas S.A.', NULL, NULL, NULL, '1 ed.', 'v.1', 'ex.1', 'p.110', 1, '6.00', 0, '003.', NULL, 1),
(264, '2022-01-01', 'MOTTA, Mary Silva da ', 'Coleção Grandes Vultos que honraram o Senado - Teotônio Vilela', '92', '923.281', '85.7018.151.5', 'Brasília-DF', 'Senado Federal', NULL, NULL, '1996', '1 ed.', NULL, 'ex.1', 'p.299', 1, '6.00', 0, '003.', NULL, 1),
(265, '2022-01-01', 'SOUZA, Hensique Arthur de', 'Coleção Grandes Vultos que honraram o Senado - Hentique de la Rocque', NULL, '923.281', '978.85.7018.503.7', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2013', '1 ed.', NULL, 'ex.1', 'p.196', 1, '6.00', 0, '003.', NULL, 1),
(266, '2022-01-01', 'LOPES, Júlio Antonio Lopes', 'Coleção Grandes Vultos que honraram o Senado - Fábio Lucena', NULL, '923.281', '978.85.7018.514.3', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2013', '1 ed.', NULL, 'ex.1', 'p.199', 1, '6.00', 0, '003.', NULL, 1),
(267, '2022-01-01', 'FONTANA, Riccardo ', 'Italianos no Brasil : O tenente-general Napione', '355(81)(09)', '923', '85.904310.4.5', 'Brasília-DF', '***', NULL, NULL, '2006', '1 ed.', 'v.1', NULL, 'p.104', 1, '150.00', 0, '003.', NULL, 1),
(268, '2022-01-01', 'José Renato Nalini', 'Ética da Magistratura', '340.12', '340.1', '978.85.203.3866.7', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.255', 1, '90.00', 0, '003.', NULL, 1),
(269, '2022-01-01', 'VERNANT, Jean-Pierre ', 'O universo, os deuses, os homens', NULL, '219.13', '85.7164.986.3', 'São Paulo', 'Companhia das Letras', NULL, NULL, '2000', '1 ed.', NULL, 'ex.1', 'p.209', 1, '40.00', 0, '003.', NULL, 1),
(270, '2022-01-01', 'Ricardo Westin', 'Coleção Arquivos -  O Senado na história do Brasil    (Volume 1)', NULL, '328.8109', '978.85.7018.650.8', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2015', '1 ed.', 'v.1', 'ex.1', 'p.121', 1, '20.00', 0, '003.', NULL, 1),
(271, '2022-01-01', 'Conselho de Estudos Políticos Senado Federal', 'O poder legislativo muncipal no Brasil: papel institucional, desafios e perspectivas', NULL, '341.253', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2016', '1 ed.', 'v.1', 'ex.1', 'p.67', 1, '20.00', 0, '003.', NULL, 1),
(272, '2022-01-01', 'Conselho de Estudos Políticos Senado Federal', 'A educação municipal e a atuação do vereador', NULL, '379.81', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2018', '1 ed.', 'v.1', 'ex.1', 'p.84', 1, '20.00', 0, '003.', NULL, 1),
(273, '2022-01-01', 'Conselho de Estudos Políticos Senado Federal', 'Guia de ação de gestores municipais para a construção de cidades sustentáveis', NULL, '352.14', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2017', '1 ed.', 'v.1', 'ex.1', 'p.67', 1, '10.00', 0, '003.', NULL, 1),
(274, '2022-01-01', 'VIANA FILHO, Luiz', 'Luiz Viana Filho, o jornalista', NULL, '070.44', '978.85.7018.244.9', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2008', '1 ed.', 'v.1', 'ex.1', 'p.412', 1, '24.00', 0, '003.', NULL, 1),
(275, '2022-01-01', 'MATOS, Miguel', 'Mugalhas de Monteiro Lobato', NULL, 'B869', '978.85.5328.010.0', 'São Paulo', 'Migalhas', NULL, NULL, '2019', '1 ed.', NULL, 'ex.1', 'p.368', 1, '18.00', 0, '003.', NULL, 1),
(276, '2022-01-01', 'Senado Federal', 'Coleção Dispositivos Constitucionais pertinentes: Patrimônio Cultural', NULL, '341.34', '978.85.708.559.4', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2014', '1 ed.', 'v.1', 'ex.1', 'p.144', 1, '50.00', 0, '003.', NULL, 1),
(277, '2022-01-01', 'Senado Federal', 'Coleção Dispositivos Constitucionais pertinentes: Eleições (Código Eleitoral )', NULL, '341.28', '978.85.7018.736.9', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2016', '1 ed.', 'v.1', 'ex.1', 'p.202', 1, '50.00', 0, '003.', NULL, 1),
(278, '2022-01-01', 'Senado Federal', 'Coleção Dispositivos Constitucionais pertinentes: Consumidor (Código de Defesa do Consumidor)', NULL, '342.5', '978.85.7018.563.1', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2015', '1 ed.', 'v.1', 'ex.1', 'p.114', 1, '50.00', 0, '003.', NULL, 1),
(279, '2022-01-01', 'Senado Federal', 'Coleção Dispositivos Constitucionais pertinentes: Drogas (Leis antidrogas)', NULL, '341.5555', '978.85.7018.568.6', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2015', '1 ed.', 'v.1', 'ex.1', 'p.169', 1, '50.00', 0, '003.', NULL, 1),
(280, '2022-01-01', 'Senado Federal', 'Coleção Dispositivos Constitucionais pertinentes: Governança Pública', NULL, '341.3', '978.85.7018.801.4', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2017', '1 ed.', 'v.1', 'ex.1', 'p.171', 1, '50.00', 0, '003.', NULL, 1),
(281, '2022-01-01', 'Senado Federal', 'Coleção Dispositivos Constitucionais pertinentes: Governança Pública', NULL, '341.3', '978.85.7018.801.4', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2017', '1 ed.', 'v.1', 'ex.2', 'p.171', 1, '50.00', 0, '003.', NULL, 1),
(282, '2022-01-01', 'AYRES, Ana Lúcia', 'Mário Covas: o legado de uma \"repórter\" involuntária', NULL, '923', '85.260.0770.x', 'São Paulo', 'Global Editora', NULL, NULL, '2002', '1 ed.', NULL, 'ex.1', 'p.230', 1, '50.00', 0, '003.', NULL, 1),
(283, '2022-01-01', 'MARTINS, Osvaldo ', 'Mario Covas - Série Perfis Parlamentares - nº 68', '328(81)(042)', '923', '978.85.402.0132.3', 'Brasília-DF', 'Câmara Editora ', 'n.68', NULL, '2014', '1 ed.', NULL, 'ex.1', 'p.512', 1, '100.00', 0, '003.', NULL, 1),
(284, '2022-01-01', 'SOUZA, José Maria Viana de ', 'A lenda da mangaba: a terra dos bravos, Araquarela, a bela', NULL, NULL, '85.8853339.1', 'Araraquara-SP', 'Compacta Editora', NULL, NULL, '2008', '1 ed.', NULL, 'ex.1', 'p.122', 1, '40.00', 0, '003.', NULL, 1),
(285, '2022-01-01', 'Renan Tavares', 'Teatro Oficina de São Paulo: seus primeiros dez anos', NULL, '792.0981611', '85.98859.18.4', 'São Caetano do Sul -SP', 'Yendis Editora', NULL, NULL, '2006', '1 ed.', 'v.1', 'ex.1', 'p.128', 1, '14.00', 0, '003.', NULL, 1),
(286, '2022-01-01', 'ASSAF NETO, Alexandre ', 'Matemática Financeira e suas aplicações', NULL, '650.01513', '85.224.3064.0', 'São Paulo', 'Atlas S.A.', NULL, NULL, '2002', '1 ed.', NULL, 'ex.1', 'p.436', 1, '20.00', 0, '003.', NULL, 1),
(287, '2022-01-01', 'HULL, John ', 'Introdução aos mercados futuros e de opções', NULL, '650', '85.293.0029.7', 'São Paulo', 'Cultura Editora', NULL, NULL, NULL, '1 ed.', NULL, 'ex.1', 'p.448', 1, '30.00', 0, '003.', NULL, 1),
(288, '2022-01-01', 'HULL, John ', 'Introdução aos mercados futuros e de opções: Livro de responstas', NULL, '650', '85.293.0029.7', 'São Paulo', 'Cultura Editora', NULL, NULL, NULL, '1 ed.', NULL, 'ex.1', 'p.100', 1, '30.00', 0, '003.', NULL, 1),
(289, '2022-01-01', 'JOSIN, Philippe ', 'Value at Risk: A nova fonte de referência para o controle de risco de mercado', NULL, '650', '85.7438.004.0', 'São Paulo', 'Cultura Editora', NULL, NULL, NULL, '1 ed.', NULL, 'ex.1', 'p.305', 1, '10.00', 0, '003.', NULL, 1),
(290, '2022-01-01', 'Câmara dos Deputados ', 'Regimento Interno da Câmara dos Deputados', NULL, '348', '978.85.736.5606.0', 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.478', 1, '10.00', 0, '003.', NULL, 1),
(291, '2022-01-01', 'Brasil', 'Código Eleitoral anotado e legislação complementar', '341.280981', '348', '978.85.86611865', 'Brasília-DF', '.', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.1081', 1, '10.00', 0, '003.', NULL, 1),
(292, '2022-01-01', 'Brasil', 'Regimento Interno da Câmara dos Deputados', '342.532(81)(094)', '348', '85.7365.445.7', 'Brasília-DF', '.', NULL, NULL, '2006', '1 ed.', 'v.1', 'ex.1', 'p.395', 1, '10.00', 0, '003.', NULL, 1),
(293, '2022-01-01', 'Brasil', 'Código de Proteção e Defesa do Consumidor', NULL, '348', '85.7018.168.1', 'Brasília-DF', '.', NULL, NULL, '2003', '1 ed.', 'v.1', 'ex.1', 'p.115', 1, '10.00', 0, '003.', NULL, 1),
(294, '2022-01-01', 'Cândido Rangel Dinamarco', 'Instituições de Direito Processual civil              (Volume 1)', NULL, '341.46', '85.7420.468.4', 'São Paulo', '.', NULL, NULL, '2003', '1 ed.', 'v.1', 'ex.1', 'p.708', 1, '100.00', 0, '003.', NULL, 1),
(295, '2022-01-01', 'Cândido Rangel Dinamarco', 'Instituições de Direito Processual civil              (Volume 2)', NULL, '341.46', '85.7420.469.2', 'São Paulo', '.', NULL, NULL, '2003', '1 ed.', 'v.1', 'ex.1', 'p.682', 1, '100.00', 0, '003.', NULL, 1),
(296, '2022-01-01', 'Celso Antônio Pacheco Fiorillo', 'Curso de direito ambiental brasileiro', '34.502.7(81)', '340', '85.02.03779.x', 'São Paulo', '.', NULL, NULL, '2002', '1 ed.', 'v.1', 'ex.1', 'p.337', 1, '70.00', 0, '003.', NULL, 1),
(297, '2022-01-01', 'Luís Eduardo Schoueri e Fernando Aurelio Zilveti (Org.)', 'Direito Tributário: estudos em homenagem a Brandão Machado', '34:336.2(81)', '343', '85.86208.60.4', 'São Paulo', '.', NULL, NULL, '1998', '1 ed.', 'v.1', 'ex.1', 'p.303', 1, '50.00', 0, '003.', NULL, 1),
(298, '2022-01-01', 'Marcus Orione Gonçalves Correia', 'Teoria Geral do processo', '347.9(81)', '341.46', '85.02.4316.1', 'São Paulo', '.', NULL, NULL, '2003', '1 ed.', 'v.1', 'ex.1', 'p.220', 1, '89.00', 0, '003.', NULL, 1),
(299, '2022-01-01', 'Guilherme Setoguti Julio Pereira', 'Coleção IDSA de Direito societário e mercado de capitais -  Impugnação de Deliberações de Assembleia da S/A (Volume 6)', NULL, '350', '85.7674.890.8', 'São Paulo', '.', NULL, NULL, '2013', '1 ed.', 'v.1', 'ex.1', 'p.360', 1, '70.00', 0, '003.', NULL, 1),
(300, '2022-01-01', 'J. Franklin Alves Felipe', 'Curso de Direito Previdenciário', '4:368.4(81)(094)', '350', '85.309.2224.7', 'Rio de Janeiro ', '.', NULL, NULL, '2006', '1 ed.', 'v.1', 'ex.1', 'p.218', 1, '40.00', 0, '003.', NULL, 1),
(301, '2022-01-01', 'Alexandre de Moraes', 'Direito constitucional Administrativo', '342.35', '342', '85.224.3148.5', 'São Paulo', '.', NULL, NULL, '2002', '1 ed.', 'v.1', 'ex.1', 'p.381', 1, '35.00', 0, '003.', NULL, 1),
(302, '2022-01-01', 'Walter Paulo Sabella, Antonio Araldo Ferraz Dal Pozzo e José Emmanuel Burle Filho (Org.)', 'Ministério Público: vinte e cinto anos do novo perfil constitucional', NULL, '342', '978.85.392.0200.3', 'São Paulo', '.', NULL, NULL, '2013', '1 ed.', 'v.1', 'ex.1', 'p.909', 1, '10.00', 0, '003.', NULL, 1),
(303, '2022-01-01', 'Angel Latorre (Org.)', 'Coleção Grandes Temas Biblioteca Salvat : Justiça e direito', NULL, '340.1', NULL, 'Rio de Janeiro ', '.', NULL, NULL, '1979', '1 ed.', 'v.1', 'ex.1', 'p.142', 1, '50.00', 0, '003.', NULL, 1),
(304, '2022-01-01', 'Sérgio Salomão Shecaira', 'Responsabilidade penal da pessoa jurídica', '343.347.19(81)', '345', '85.203.1641.7', 'São Paulo', '.', NULL, NULL, '1998', '1 ed.', 'v.1', 'ex.1', 'p.163', 1, '100.00', 0, '003.', NULL, 1),
(305, '2022-01-01', 'Ivete Salete Baschetti (Org.) Conselho Federal de Serviço Social', 'Direito se conquista: a luta dos/as assistentes sociais pelas 30 horas semanais', NULL, '360', NULL, 'Brasília-DF', '.', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.156', 1, '60.00', 0, '003.', NULL, 1),
(306, '2022-01-01', 'Enoque Ribeiro dos Santos (Coord.)', 'Direito Coletivo Moderno - Da LACP e do CDC ao direito de negociação coletiva no setor público', '331.116.3(81)', '341', '85361.0772.3', 'São Paulo', '.', NULL, NULL, '2006', '1 ed.', 'v.1', 'ex.1', 'p.175', 1, '35.00', 0, '003.', NULL, 1),
(307, '2022-01-01', 'Lelio Bentes Corrêa e Tárcio José Vidotti (Coord.)', 'Trabalho Infantil e direitos humanos: homenagem a Oris de Oliveira', '34.331.024.053.2', '341.481', '85.361.0767.7', 'São Paulo', '.', NULL, NULL, '2005', '1 ed.', 'v.1', 'ex.1', 'p.221', 1, '40.00', 0, '003.', NULL, 1),
(308, '2022-01-01', 'Athos Gusmão Carneiro', 'Intervenção de terceiros', '347.921.3(81)', '341.46', '85.02.03583.5', 'São Paulo', '.', NULL, NULL, '2001', '1 ed.', 'v.1', 'ex.1', 'p.304', 1, '100.00', 0, '003.', NULL, 1),
(309, '2022-01-01', 'Antonio Araldo Ferraz Dal Pozzo', 'Lei Anticorrupção: apontamentos sobre a Lei 12.846/2013', '342.9', '342', '978.85.69220.03.9', 'São Paulo', '.', NULL, NULL, '2015', '1 ed.', 'v.1', 'ex.1', 'p.240', 1, '100.00', 0, '003.', NULL, 1),
(310, '2022-01-01', 'José Antonio Vasconcelos', 'Fundamentos Filosoficos da educação', NULL, '370.1', '978.85.8212.227.3', 'Curitiba-PR', '.', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.189', 1, '70.00', 0, '003.', NULL, 1),
(311, '2022-01-01', 'RAMOS, Cristhiane da Silva', 'Visualização cartográfica e cartografia multimídia: conceitos e tecnologias', '528.9', '526', '85.7139.595.0', 'São Paulo', 'UNESP - Universidade Estadual Paulista', NULL, NULL, '2005', '1 ed.', NULL, 'ex.1', 'p.178', 1, '50.00', 0, '003.', NULL, 1),
(312, '2022-01-01', 'José Murari Bovo (Coord.)', 'Impactos econômicos e financeiros da UNESP para os municípios', NULL, '378.8161', '85.7139.480.6', 'São Paulo', '.', NULL, NULL, '2003', '1 ed.', 'v.1', 'ex.1', 'p.150', 1, '6.00', 0, '003.', NULL, 1),
(313, '2022-01-01', 'CASANOVA, Pablo González', 'Coleção Nossa América: A democracia no México', NULL, '972', NULL, 'Rio de Janeiro ', '.', NULL, NULL, '1967', '1 ed.', NULL, 'ex.1', 'p.299', 1, '6.00', 0, '003.', NULL, 1),
(314, '2022-01-01', 'Brasil- Secretaria de Direitos Humanos da República', 'Programa Nacional de Direitos Humanos - PNDH-3', '341.231.14', '341.481', NULL, 'Brasília-DF', '.', NULL, NULL, '2010', '1 ed.', 'v.1', NULL, 'p.228', 1, '15.00', 0, '003.', NULL, 1),
(315, '2022-01-01', 'Ruy Rosado de Aguiar Júnior (Cood.)', 'Jornadas de direito civil I, II, III, IV e V: enunciados aprovados', NULL, '341.481', '978.85.85572.93.8', 'Brasília-DF', '.', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.135', 1, '30.00', 0, '003.', NULL, 1),
(316, '2022-01-01', 'Padre Tomas Enriquez ', 'Em Três Cárceres Comunistas', NULL, '320.532', NULL, 'Belo Horizonte-MG', '.', NULL, NULL, '1960', '1 ed.', 'v.1', 'ex.1', 'p.190', 1, '6.00', 0, '003.', NULL, 1),
(317, '2022-01-01', 'BRAGA, Pedro ', 'Filosofia e Psicanálise - A busca pela felicidade', '101.159.9', '101', '978.85.64898.56.1', 'Brasília-DF', '.', NULL, NULL, '2014', '1 ed.', NULL, 'ex.1', 'p.112', 1, '19.90', 0, '003.', NULL, 1),
(318, '2022-01-01', 'FREIRE, Danilo de Oliveira ', 'Filosofando através de música, jogos e dinâmicas', NULL, '107', '978.85.8196.187.3', 'São paulo', '.', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.79', 1, '10.00', 0, '003.', NULL, 1),
(319, '2022-01-01', 'ARANHA, Maria Lúcia de Arruda ', 'Filosofando: introdução a filosofia', NULL, '107', NULL, 'São Paulo', '.', NULL, NULL, '1986', '1 ed.', NULL, 'ex.1', 'p.443', 1, '10.00', 0, '003.', NULL, 1),
(320, '2022-01-01', 'AGUIAR, Maria Aparecida Ferreira de ', 'Psicologia aplicada à adminstração: uma introdução à psicologia organizacional', NULL, '658.0019', '85.224.0331.7', 'São Paulo', '.', NULL, NULL, '1988', '1 ed.', NULL, 'ex.1', 'p.235', 1, '20.00', 0, '003.', NULL, 1),
(321, '2022-01-01', 'Roberto Luiz Silva58', 'Direito Internacional Público', '341.01', '341.1', '85.7308.553.3', 'Belo Horizonte-MG', '.', NULL, NULL, '2002', '1 ed.', NULL, 'ex.1', 'p.456', 1, '15.00', 0, '003.', NULL, 1),
(322, '2022-01-01', 'Francisco de B.B. de Magalhães Filho', 'História Econômica', NULL, '330.01', NULL, 'São Paulo', '.', NULL, NULL, '1970', '1 ed.', NULL, 'ex.1', 'p.472', 1, '40.00', 0, '003.', NULL, 1),
(323, '2022-01-01', 'Secretaria do Interior e Justiça - Estado de Mato Grosso', 'Diagnóstico do menor no Mato Grosso', NULL, '305.234', NULL, 'Cuiabá-MT', '.', NULL, NULL, '1974', '1 ed.', NULL, 'ex.1', 'p.198', 1, NULL, 1, '003.', NULL, 1),
(324, '2022-01-01', 'SOUSA, Gabriel Soares de', 'Coleção Brasiliana : Tratado Descritivo do Brasil em 1587 - Vol -117', NULL, '981.03', NULL, 'São Paulo', '.', NULL, NULL, '1971', '1 ed.', 'v.117', 'ex.1', 'p.389', 1, '35.00', 0, '003.', NULL, 1),
(325, '2022-01-01', 'UFG - Universidade Federal de Goiás', 'Fórum Nacional dos pró-reitores de assuntos estudantis e comunitários', NULL, '378', NULL, 'Goiânia -GO', '.', NULL, NULL, '1993', '1 ed.', 'v.1', 'ex.2', 'p.323', 1, '8.00', 0, '003.', NULL, 1),
(326, '2022-01-01', 'Luiz Roberto Curia, Lívia Céspedes e Fabiana Dias Rocha', 'VADE MECUM Compacto', '34(81)(02)', '348', '978.85.02.63114.4', 'São Paulo', '.', NULL, NULL, '2015', '1 ed.', 'v.1', 'ex.1', 'p.1803', 1, '50.00', 0, '003.', NULL, 1),
(327, '2022-01-01', 'Julio Fabbrini Mirabete', 'Processo penal (atualização 2012)', NULL, '343.1', '85.224.3474.3', 'São Paulo', '.', NULL, NULL, '2003', '1 ed.', 'v.1', 'ex.1', 'p.783', 1, '30.00', 0, '003.', NULL, 1),
(328, '2022-01-01', 'João Roberto Parizatto', 'Divórcio e separação alimentos e sua execução: doutrinas e jurisprudência', NULL, '345', NULL, 'São Paulo', '.', NULL, NULL, '1997', '1 ed.', 'v.1', 'ex.1', 'p.208', 1, '100.00', 0, '003.', NULL, 1),
(329, '2022-01-01', 'Jacob Dolinger', 'Direito internacional privado', '341.9', '341.481', NULL, 'Rio de Janeiro ', '.', NULL, NULL, '1993', '1 ed.', 'v.1', 'ex.1', 'p.457', 1, '40.00', 0, '003.', NULL, 1),
(330, '2022-01-01', 'Hely Lopes Meirelles', 'Estudos e pareceres de direito público (Volume 11)', '35(81)/ 342(81)/342(81)(094.98)', '346', '85.203.0925.9', 'São Paulo', '.', NULL, NULL, '1991', '1 ed.', 'v.1', 'ex.1', 'p.392', 1, '20.00', 0, '003.', NULL, 1),
(331, '2022-01-01', 'Mauricio Antonio Ribeiro', 'Direito penal, estado e constituição:  princípios constitucionais politicamente conformadores do direito penal', '343.342', '345', NULL, 'São Paulo', '.', NULL, NULL, '1997', '1 ed.', 'v.1', 'ex.1', 'p.240', 1, '30.00', 0, '003.', NULL, 1),
(332, '2022-01-01', 'Valdir Gonzaga', 'Vocabulário forense da investigação de paternidade', NULL, '347.632.12(038)', '85.7283.127.4', 'Bauru-SP', '.', NULL, NULL, '1996', '1 ed.', 'v.1', 'ex.1', 'p.263', 1, '58.00', 0, '003.', NULL, 1),
(333, '2022-01-01', 'Fávila Ribeiro', 'Abuso de poder no direito eleitoral', '342.84', '342.07', '85.309.0603.9', 'Rio de Janeiro ', '.', NULL, NULL, '1998', '1 ed.', 'v.1', 'ex.1', 'p.232', 1, '140.00', 0, '003.', NULL, 1),
(334, '2022-01-01', 'Paulo Dourado de Gusmão', 'Introdução ao estudo do direito', '340.12/340.14', '340.1', '85.309.0206.8', 'Rio de Janeiro ', '.', NULL, NULL, '1997', '1 ed.', 'v.1', 'ex.1', 'p.433', 1, '30.00', 0, '003.', NULL, 1),
(335, '2022-01-01', 'Bruno Yepes Pereira', 'Curso de Direito internacional público', '341', '341', '85.02.05467.8', 'São Paulo', '.', NULL, NULL, '2006', '1 ed.', 'v.1', 'ex.1', 'p.244', 1, '70.00', 0, '003.', NULL, 1),
(336, '2022-01-01', 'Adriano Campanhole e Hilton Lobo Campanhole (Org.)', 'Todas as constituições do Brasil (compilação atualizada em 1969)', NULL, '341', NULL, 'São Paulo', '.', NULL, NULL, '1971', '1 ed.', 'v.1', 'ex.1', 'p.639', 1, '70.00', 0, '003.', NULL, 1),
(337, '2022-01-01', 'Otavio Pinto e Silva', 'A contratação coletiva como fonte do direito do trabalho', '34:331.106.2', '350', '85.7322.453.3', 'São Paulo', '.', NULL, NULL, '1998', '1 ed.', 'v.1', 'ex.1', 'p.144', 1, '30.00', 0, '003.', NULL, 1),
(338, '2022-01-01', 'Natália Queiroz Cabral Rodrigues', 'Relações de trabalho sadia: função social da propriedade versos livre-iniciativa', '34:331', '350', '978.85.361.8595.8', 'São Paulo', '.', NULL, NULL, '2015', '1 ed.', 'v.1', 'ex.1', 'p.175', 1, '29.00', 0, '003.', NULL, 1),
(339, '2022-01-01', 'Juarez de Oliveira (Org.)', 'Código Penal', '343(81)(094.4)', '348', '85.02.00285.6', 'São Paulo', '.', NULL, NULL, '1992', '1 ed.', 'v.1', 'ex.1', 'p.520', 1, '30.00', 0, '003.', NULL, 1),
(340, '2022-01-01', 'Antonio José Miguel Feu Rosa', 'Direito Constitucional', '342.81', '342', '85.02.02568.6', 'São Paulo', '.', NULL, NULL, '1998', '1 ed.', 'v.1', 'ex.1', 'p.578', 1, '20.00', 0, '003.', NULL, 1),
(341, '2022-01-01', 'BUSSADA, Wilson ', 'Direito Criminal nos tribunais (Verbetes de Q a Z) - Ensiclopédia', NULL, '345', NULL, 'São Paulo', '.', NULL, NULL, '1991', '1 ed.', 'v.3', 'ex.1', 'p.1873 - 2317', 1, '50.00', 0, '003.', NULL, 1),
(342, '2022-01-01', 'HAILEY, Arthur', 'Hotel', NULL, '813', NULL, 'Rio de Janeiro ', 'Nova Fronteira S.A.', NULL, NULL, '1965', '1 ed.', NULL, 'ex.1', 'p.434', 1, '6.00', 0, '003.', NULL, 1),
(343, '2022-01-01', 'Cristina Maria Macedo Tomaz (Org.)', 'Linhas da vida - Bordando as histórias dos nossos corações', NULL, '398.20481', '85.98804-01-0', 'São Paulo', 'Sonora', NULL, NULL, '2004', '1 ed.', NULL, 'ex.1', 'p.96', 1, '13.00', 0, '003.', NULL, 1),
(344, '2022-01-01', 'COHEN, Robert ', 'Aqui e Agora', NULL, '823', '85.7123.738.7', 'São Paulo', 'Best Seller Ltda', NULL, NULL, '2001', '1 ed.', NULL, 'ex.1', 'p.416', 1, '8.00', 0, '003.', NULL, 1),
(345, '2022-01-01', 'JOSÉ, Ganymédes', 'Larissa', NULL, 'B869.93 ', '85.11.20314-1', 'São Paulo', 'Brasilienses', NULL, NULL, '1995', '1 ed.', NULL, 'ex.1', 'p.102', 1, '12.00', 0, '003.', NULL, 1),
(346, '2022-01-01', 'RANIERI, R.A. ', 'Materializções Luminosas', NULL, 'B869.93 ', NULL, 'São Paulo', 'Zahar Editora', NULL, NULL, '1955', '1 ed.', NULL, 'ex.1', 'p.244', 1, '20.00', 0, '003.', NULL, 1),
(347, '2022-01-01', 'MARIE, Dagmar ', 'Poesias em Mensagens que o amor gravou', NULL, 'B869.91', NULL, 'São Paulo', 'Martins', NULL, NULL, NULL, '1 ed.', NULL, 'ex.1', 'p.95', 1, '15.00', 0, '003.', NULL, 1),
(348, '2022-01-01', 'DIAFÉRIA, Lourenço ', 'Um gato na terra do tamborim', NULL, 'B869.93 ', NULL, 'São Paulo', 'Símbolo Edições', NULL, NULL, '1976', '1 ed.', NULL, 'ex.1', 'p.188', 1, '10.00', 0, '003.', NULL, 1),
(349, '2022-01-01', 'VICENTE, Gil ', 'Auto da barca do inferno', NULL, 'B869.92', NULL, 'São Paulo', 'Objetivo sitema de ensino', NULL, NULL, NULL, '1 ed.', NULL, 'ex.1', 'p.64', 1, '16.00', 0, '003.', NULL, 1),
(350, '2022-01-01', 'HEMINGWAY, Ernest ', 'Por quem os sinos dobram', NULL, '813.5', NULL, 'São Paulo', 'Companhia Editora Nacional', NULL, NULL, '1980', '1 ed.', NULL, 'ex.1', 'p.423', 1, '28.00', 0, '003.', NULL, 1),
(351, '2022-01-01', 'RAMOS, Graciliano ', 'São Bernardo', NULL, '869.935', NULL, 'Rio de Janeiro ', 'Record', NULL, NULL, '1985', '1 ed.', NULL, 'ex.1', 'p.216', 1, '10.00', 0, '003.', NULL, 1),
(352, '2022-01-01', 'ENDE, Michael ', 'Manu, a menina que sabia ouvir', '809.89282', '839', NULL, 'Belo Horizonte-MG', 'VEGA S.A', NULL, NULL, '1977', '1 ed.', NULL, 'ex.1', 'p.212', 1, '15.00', 0, '003.', NULL, 1),
(353, '2022-01-01', 'LOPES, Edward', 'Metamorfose: a poesia de Claudio Manuel da Costa', NULL, '869.910', '85.7139.152.1', 'São Paulo', 'UNESP - Universidade Estadual Paulista', NULL, NULL, '1997', '1 ed.', NULL, 'ex.1', 'p.209', 1, '30.00', 0, '003.', NULL, 1),
(354, '2022-01-01', 'ANDRADE, Sérgio Augusto de', 'Pinto Calçudo ou os últimos dias de Serafim Ponde Grande', NULL, '869.935', '85.250.3472.x', 'São Paulo', 'Globo', NULL, NULL, '2001', '1 ed.', NULL, 'ex.1', 'p.205', 1, '17.00', 0, '003.', NULL, 1),
(355, '2022-01-01', 'BARBOSA, Frederico Barbosa (Org.)', 'Clássicos da Poesia brasileira', NULL, '813', '85.87333.32.1', 'São Paulo', 'Klick Editora', NULL, NULL, NULL, '1 ed.', NULL, 'ex.1', 'p.193', 1, '10.00', 0, '003.', NULL, 1),
(356, '2022-01-01', 'URIS, Leon ', 'Trindade -  o romance da Irlanda', NULL, '813', NULL, 'Rio de Janeiro ', 'Record', NULL, NULL, '1976', '1 ed.', NULL, 'ex.1', 'p.718', 1, '10.00', 0, '003.', NULL, 1),
(357, '2022-01-01', 'ALCOTT, Louise May ', 'Mulherzinhas', NULL, '813', NULL, 'São Paulo', 'Companhia Editora Nacional', NULL, NULL, '1976', '1 ed.', NULL, 'ex.1', 'p.236', 1, '15.00', 0, '003.', NULL, 1),
(358, '2022-01-01', 'SHELDON, Sidney ', 'A outra face', NULL, '813', NULL, 'Rio de Janeiro ', 'Record', NULL, NULL, '1970', '1 ed.', NULL, 'ex.1', 'p.194', 1, '12.00', 0, '003.', NULL, 1),
(359, '2022-01-01', 'ALLEY, Robert', 'Último tando em Paris', NULL, '813', NULL, 'Rio de Janeiro ', 'Civilização Brasileira', NULL, NULL, '1973', '1 ed.', NULL, 'ex.1', 'p.183', 1, '15.00', 0, '003.', NULL, 1),
(360, '2022-01-01', 'KUSHNER, Rose ', 'Por que Eu?', NULL, '616.994', NULL, 'São Paulo', 'Summus', NULL, NULL, '1981', '1 ed.', NULL, 'ex.1', 'p.270', 1, '10.00', 0, '003.', NULL, 1),
(361, '2022-01-01', 'CUNHA, Celso Ferreira da ', 'Gramática da Língua portuguesa', NULL, '469.5', NULL, 'Rio de Janeiro ', 'Fename', NULL, NULL, '1977', '1 ed.', NULL, 'ex.1', 'p.656', 1, '30.00', 0, '003.', NULL, 1),
(362, '2022-01-01', 'PIRANDELLO, Luigi ', 'O falescido Mattia Pascal', NULL, '853', NULL, 'Rio de Janeiro ', 'Civilização Brasileira', NULL, NULL, '1971', '1 ed.', NULL, 'ex.1', 'p.247', 1, '10.00', 0, '003.', NULL, 1),
(363, '2022-01-01', 'MASCHMANN, Melita ', 'O décimo terceiro', NULL, '839', NULL, 'São Paulo', 'Nova Cultura', NULL, NULL, '1966', '1 ed.', NULL, 'ex.1', 'p.203', 1, '6.00', 0, '003.', NULL, 1),
(364, '2022-01-01', 'REVERTE, Arturo Perez ', 'O dia da cólera ou A mão de um Deus sobre um império', NULL, '860', '978.989.23031.85', '***', 'Exposição do Livro', NULL, NULL, NULL, '1 ed.', NULL, 'ex.1', 'p.219', 1, '6.00', 0, '003.', NULL, 1),
(365, '2022-01-01', 'Jout, Jout (Júlia Tolezano da Veiga Faria', 'Tá todo mundo mal: o livro das crises', NULL, 'B869.93', '978.85.359.2720.7', 'São Paulo', 'Companhia das Letras', NULL, NULL, '2019', '1 ed.', NULL, 'ex.1', 'p.196', 1, '26.30', 0, '003.', NULL, 1),
(366, '2022-01-01', 'MACLEAN, Alistair ', 'O desafio das águas', NULL, '839', NULL, 'Rio de Janeiro ', 'Record', NULL, NULL, '1967', '1 ed.', NULL, 'ex.1', 'p.187', 1, '10.00', 0, '003.', NULL, 1),
(367, '2022-01-01', 'NASSA, Marlene Caminhoto', 'Catança', NULL, 'B869.93', '85.274.0855.4', 'São Paulo', 'Ícone', NULL, NULL, '2006', '1 ed.', NULL, 'ex.1', 'p.118', 1, '15.00', 0, '003.', NULL, 1),
(368, '2022-01-01', 'SHELDON, Sidney ', 'Um Estranho no espelho', NULL, '813', NULL, 'Rio de Janeiro ', 'Record', NULL, NULL, '1976', '1 ed.', NULL, 'ex.1', 'p.292', 1, '15.00', 0, '003.', NULL, 1),
(369, '2022-01-01', 'BUCK, Pearl S.', 'Uma ponte para passar', NULL, '813', NULL, 'São Paulo', 'Melhoramentos Editora', NULL, NULL, '1963', '1 ed.', NULL, 'ex.1', 'p.208', 1, '6.00', 0, '003.', NULL, 1),
(370, '2022-01-01', 'Ellen G. White', 'A grande esperança', '11-04259', '236', '978-85-345-1408-8', 'Tatuí-SP', 'Casa Publicadora Brasileira', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p.106', 1, '10.00', 0, '003.', NULL, 1),
(371, '2022-01-01', 'BADINTER, Elisabeth Badinter', 'Um amor conquistado: o mito do amor materno', NULL, '194', NULL, 'Rio de Janeiro ', 'Nova Fronteira S.A.', NULL, NULL, '1985', '1 ed.', NULL, 'ex.1', 'p.370', 1, '100.00', 0, '003.', NULL, 1),
(372, '2022-01-01', 'JORGE, Salomão', 'Arabescos', NULL, 'B869.91', NULL, 'São Paulo', 'EDART', NULL, NULL, '1968', '1 ed.', NULL, 'ex.1', 'p.240', 1, '15.00', 0, '003.', NULL, 1),
(373, '2022-01-01', 'GOMES, Arqueimedes ', 'Coisas da Vida', NULL, 'B869.93 ', NULL, '***', '***', NULL, NULL, NULL, '1 ed.', NULL, 'ex.1', 'p.127', 1, '15.00', 0, '003.', NULL, 1),
(374, '2022-01-01', 'VILAÇA, Marcos Vinícius ', ' O tempo e o Sonho', '869.0(81)821', 'B869.93 ', '85.7010.004.3', 'Recife-PE', 'Pool Editorial', NULL, NULL, '1983', '1 ed.', NULL, 'ex.1', 'p.116', 1, '15.00', 0, '003.', NULL, 1),
(375, '2022-01-01', 'GREENE, Graham ', 'Trem de Istambul', NULL, '823', NULL, 'Rio de Janeiro ', 'Civilização Brasileira', NULL, NULL, '1960', '1 ed.', NULL, 'ex.1', 'p.250', 1, '15.00', 0, '003.', NULL, 1),
(376, '2022-01-01', 'BRADFORD, Barbara Taylor', 'Uma Mulher de fibra', NULL, '823', NULL, 'Rio de Janeiro ', 'Record', NULL, NULL, '1979', '1 ed.', NULL, 'ex.1', 'p.718', 1, '15.00', 0, '003.', NULL, 1),
(377, '2022-01-01', 'Caixa Economica Federal (Conjunto Cultural)', 'Coleção: O Folclore da Caixa', NULL, 'B869.9301', NULL, 'Brasília-DF', '***', NULL, NULL, NULL, '1 ed.', NULL, 'ex.1', 'p.319', 1, '15.00', 0, '003.', NULL, 1),
(378, '2022-01-01', 'DIAS, Elisa ', 'Cer Tesa', NULL, 'B869.93 ', NULL, 'São Paulo', 'Arte Pau Brasil', NULL, NULL, NULL, '1 ed.', NULL, 'ex.1', 'p.85', 1, '15.00', 0, '003.', NULL, 1),
(379, '2022-01-01', 'RAMOS, Graciliano ', 'Caetés10', '869.0(81).31', 'B869.935', NULL, 'São Paulo', 'INL - Instituto Nacional do Livro', NULL, NULL, '1973', '1 ed.', NULL, 'ex.1', 'p.240', 1, '10.00', 0, '003.', NULL, 1),
(380, '2022-01-01', 'ECO, Umberto ', 'Viagem na Irrealidade Cotidiana', '850.4', '850', NULL, 'Rio de Janeiro ', 'Nova Fronteira S.A.', NULL, NULL, '1984', '1 ed.', NULL, 'ex.1', 'p.353', 1, '10.00', 0, '003.', NULL, 1),
(381, '2022-01-01', 'BARROCO, Maria Alice ', 'Um simples afeto recíproco', NULL, 'B869.93 ', NULL, 'Rio de Janeiro ', 'Edições GRD', NULL, NULL, '1963', '1 ed.', NULL, 'ex.1', 'p.268', 1, '10.00', 0, '003.', NULL, 1),
(382, '2022-01-01', 'VERÍSSIMO, Luis Fernando', 'A velhinha de Taubaté', NULL, 'B869.93 ', NULL, 'Porto Alegre-RS', 'LPM Editora', NULL, NULL, '1984', '5 ed.', NULL, 'ex.1', 'p.142', 1, '10.00', 0, '003.', NULL, 1),
(383, '2022-01-01', 'BENCHLEY, Peter Benchley', 'Tubarão', NULL, 'B869.93 ', NULL, 'Rio de Janeiro ', 'Record', NULL, NULL, '1974', '1 ed.', NULL, 'ex.1', 'p.262', 1, '15.00', 0, '003.', NULL, 1),
(384, '2022-01-01', 'WALLACE, Irving ', 'Os sete Minutos', NULL, '813', NULL, 'Rio de Janeiro ', 'Nova Fronteira S.A.', NULL, NULL, NULL, '1 ed.', NULL, 'ex.1', 'p.556', 1, '13.00', 0, '003.', NULL, 1),
(385, '2022-01-01', 'VIEBIG, Reinhard ', 'Formulário Fotografico', NULL, '770', NULL, 'São Paulo', 'Isis Editora', NULL, NULL, '1978', '1 ed.', '.+', 'ex.1', 'p.203', 1, '10.00', 0, '003.', NULL, 1),
(386, '2022-01-01', 'GODINHO, Gualter ', 'Coletânea de discursos e conferências', NULL, '980', NULL, 'São Paulo', 'MSTM - MINST. SUPREMO TRIB. MILITAR', NULL, NULL, '1979', '1 ed.', NULL, 'ex.1', 'p.152', 1, '15.00', 0, '003.', NULL, 1),
(387, '2022-01-01', 'SILVA, Zélia Lopes da (Org.)', 'Arquivos, patrimônio e memórias: trajetórias e perspectivas', NULL, '025.5', '85.7139.268.4', 'São Paulo', 'UNESP - Universidade Estadual Paulista', NULL, NULL, '1999', '1 ed.', NULL, 'ex.1', 'p.154', 1, '20.00', 0, '003.', NULL, 1),
(388, '2022-01-01', 'MENDES,, Alexandre Marques', 'Coleção História Local: O conflito social de Guariba, 1984-1985 (Volume 12)', NULL, '981.552', '85.86420.14.x', 'Franca-SP', 'UNESP - Universidade Estadual Paulista', NULL, NULL, '1999', '1 ed.', 'v.12', 'ex.1', 'p.211', 1, '15.00', 0, '003.', NULL, 1),
(389, '2022-01-01', 'Comitê das Bacias Hidrográficas dos Rios Piracicaba, Capicari e Jundiaí', 'Água (1993-2003)', NULL, '918.161', NULL, 'São Paulo', '***', NULL, NULL, '2003', '1 ed.', NULL, 'ex.1', 'p.135', 1, '20.00', 0, '003.', NULL, 1),
(390, '2022-01-01', 'FELICIANO, Anthemo Roberto ', 'Histórias de Marias: Relatos de uma cidade', NULL, 'B869.9', '978.85.908664.1.1', 'Botucatu-SP', '***', NULL, NULL, '2014', '1 ed.', 'v.1', 'ex.1', 'p.154', 1, '10.00', 0, '003.', NULL, 1),
(391, '2022-01-01', 'BARROS, Francisco Blaudes Souza', 'Coleção Camponeses e o Regime Militar - Japuara, um relato das entranhas do conflito (Volume 2)', '304', '981.063', '´978.85.60548.97.2', 'Brasília-DF', 'Minstério Agrário', NULL, NULL, '2013', '1 ed.', 'v.2', 'ex.1', 'p.224', 1, '30.00', 0, '003.', NULL, 1),
(392, '2022-01-01', 'PIERI, José Carlos de ', 'Ecos da Terra', NULL, 'B869.935', '978.85.98187.49.5', 'Batucatu-SP', 'FEPAF', NULL, NULL, '2013', '1 ed.', 'v.1', 'ex.3', 'P.133', 1, '28.50', 0, '003.', NULL, 1),
(393, '2022-01-01', 'FERRI, René', 'A arte nas teclas', NULL, '780', NULL, 'São Paulo', 'Escala Editora', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', NULL, 1, '10.00', 0, '003.', NULL, 1),
(394, '2022-01-01', 'HUXLEY, Aldous ', 'Admirável Mundo Novo', NULL, '823', NULL, 'Rio de Janeiro ', 'Dinal', NULL, NULL, '1966', '1 ed.', NULL, 'ex.1', 'p.309', 1, '26.00', 0, '003.', NULL, 1),
(395, '2022-01-01', 'CAPUCCI, Egmont Bastos (Org.)', 'Poços tubulares e outras captações de águas subterrâneas: orientação aos usuários', NULL, '627', '85.87206.11.7', 'Rio de Janeiro ', 'SEMADS - Secretaria de Meio Ambiente e Desenvolvimento Sustentável', NULL, NULL, '2001', '1 ed.', NULL, 'ex.1', 'p.70', 1, '10.00', 0, '003.', NULL, 1),
(396, '2022-01-01', 'Associação Nacional das Empresas de Transporte Urbano ', 'NTU 30 anos', '656.1/5', '380', '978.85.69544.03.6', 'Brasília-DF', 'Isca Conteúdos e Projetos', NULL, NULL, '2017', '1 ed.', 'v.1', 'ex.1', 'p.204', 1, '20.00', 0, '003.', NULL, 1),
(397, '2022-01-01', 'PAZIANI, Rodrigo Ribeiro / SOUZA JÚNIOR, João Batista de', 'De Cardoso no Mundo ao Mundo de Cardoso: uma trajetória de uma cidade no Extremo Noroeste de São Paulo', NULL, '981.61', '978.85.8148.491.4', 'São Paulo', 'Paco Editorial', NULL, NULL, '2014', '1 ed.', NULL, 'ex.1', 'p.164', 1, '30.00', 0, '003.', NULL, 1),
(398, '2022-01-01', 'MAGALHÃES, Gildo ', 'Força e Luz: eletricidade e modernização na República Velha', NULL, '981.05', '85.7139.283.8', 'São Paulo', 'UNESP - Universidade Estadual Paulista', NULL, NULL, '2000', '1 ed.', NULL, 'ex.1', 'p.122', 1, '10.00', 0, '003.', NULL, 1),
(399, '2022-01-01', 'LACOSTE, Yves', 'Os Países subdesenvolvidos', NULL, '910.7', NULL, 'São Paulo', 'DIFEL', NULL, NULL, '1985', '1 ed.', NULL, 'ex.1', 'p.120', 1, '7.00', 0, '003.', NULL, 1),
(400, '2022-01-01', 'Paul Singer', 'Coleção Discutindo a História - A formação da classe operária', NULL, '305.56', NULL, 'São Paulo', 'Universidade de Campinas', NULL, NULL, '1987', '1 ed.', 'v.1', 'ex.1', 'p.80', 1, '20.00', 0, '003.', NULL, 1),
(401, '2022-01-01', 'SEVCENKO, Nicolau', 'Coleção Discutindo a História - O renascimento', NULL, '940.21', NULL, 'São Paulo', 'Universidade de Campinas', NULL, NULL, '1985', '3 ed.', NULL, 'ex.1', 'p.82', 1, '6.00', 0, '003.', NULL, 1),
(402, '2022-01-01', 'ANDRADA, Bonifácio de', 'ParlAmento Brasileiro e a sua crise no fim do século', NULL, '981', NULL, 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '1995', '1 ed.', NULL, 'ex.1', 'p.154', 1, '30.00', 0, '003.', NULL, 1),
(403, '2022-01-01', 'ANTAS, José Baptista de Castro Moraes', 'O Amazonas: breve  resposta à memória do tenente da Armada Americana-Inglesa F. Maury sobre as Vantagens da Livre Navegação do Amazonas', NULL, '918.11', '978.85.7631.449.31', 'Rio de Janeiro ', 'FUNAG/CHDD', NULL, NULL, '2013', '1 ed.', NULL, 'ex.1', 'p.89', 1, '50.00', 0, '003.', NULL, 1),
(404, '2022-01-01', 'Bernardo Mançano Fernandes, Leonilde Servolo de Medeiros e Maria Ignez Paulilo (Org.)', 'Lutas camponesas contemporâneas: condições, dilemas e conquistas      (Volume 1)', NULL, '305.5633', '978.85.7139.948.8', 'São Paulo', 'UNESP - Universidade Estadual Paulista', NULL, NULL, '2009', '1 ed.', 'v.1', 'ex.1', 'p.327', 1, '14.00', 0, '003.', NULL, 1),
(405, '2022-01-01', 'FERNANDES, Jacoby J. U. ', 'Vade-mécon de licitações e contratos: legislações selecionadas e organizadas com jurisprudência', '351.712(81)', '348', '978.85.89148.98.6', 'Belo Horizonte-MG', 'Fórum Editora', NULL, NULL, '2009', '1 ed.', NULL, 'ex.1', 'p.2657', 1, '80.00', 0, '003.', NULL, 1);
INSERT INTO `librarycollection` (`id`, `registrationDate`, `author`, `title`, `cdu`, `cdd`, `isbn`, `local`, `publisher_edition`, `number`, `month`, `year`, `edition`, `volume`, `copyNumber`, `pageNumber`, `typeAcquisitionId`, `price`, `prohibitedSale`, `provider`, `exclusionInfoTerm`, `registeredByUserId`) VALUES
(406, '2022-01-01', 'STOCO, Rui Stoco', 'Tratado de responsabilidade civil: responsabilidade civil e sua interpretação doutrinária e jurisprudencia ', '347.51(81)(094.9)', '347', '85.203.2039.2', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '2001', '1 ed.', NULL, 'ex.1', 'p.1853', 1, '80.00', 0, '003.', NULL, 1),
(407, '2022-01-01', 'CUSTÓDIO, Antonio Joaquim Ferreira  (Org.)(Constituição Federal )', 'Constituição Federal Interpretada pela STF', '342.4(81)', '348', '85.7453.516.8', 'São Paulo', 'Juarez de Oliveira Ltda', NULL, NULL, '2004', '1 ed.', NULL, 'ex.1', 'p.784', 1, '50.00', 0, '003.', NULL, 1),
(408, '2022-01-01', 'KUYVEN, Luiz Fernando Martins', 'Temas de direito empresarial: estudos em homenagem a Modesto Carvalho', '34.338.93(81)', '346.07', '978.85.02.15954.9', 'São Paulo', 'Saraiva', NULL, NULL, '2002', '1 ed.', NULL, 'ex.1', 'p.1095', 1, '30.00', 0, '003.', NULL, 1),
(409, '2022-01-01', 'GUIMARÃES, Carlos A. M.', 'Inventários, arrolamentos e partilhas', NULL, '347', NULL, 'São Paulo', 'Edições Jurídicas', NULL, NULL, '1998', '1 ed.', NULL, 'ex.1', 'p.504', 1, '100.00', 0, '003.', NULL, 1),
(410, '2022-01-01', 'CINTRA, Antonio Carlos de Araujo; GRINOVER, Ada Pellegrini  e DINAMARCO, Cândido Rangel', 'Teoria Geral do Processo', NULL, '341.46', '85.392.0000.7', 'São Paulo', 'Malheiros', NULL, NULL, '2010', '1 ed.', NULL, 'ex.3', 'p.389', 1, '100.00', 0, '003.', NULL, 1),
(411, '2022-01-01', 'CARDOSO, Elio ', 'Tribunal Penal Internacional: Conceitos, realidades e implicações para o Brasil', NULL, '345', '978.85.7631.398.4', 'Brasília-DF', 'Fundação Alexandre de Gusmão ', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.176', 1, '70.00', 0, '003.', NULL, 1),
(412, '2022-01-01', 'GUIMARÃES, Samuel Pinheiro  (Org.)', 'Brasil e China: Multipolaridade', '341.232(51.81)', '327', '85.7631.005.8', 'Rio de Janeiro ', 'IPRI', NULL, NULL, '2003', '1 ed.', NULL, 'ex.1', 'p.489', 1, '10.00', 0, '003.', NULL, 1),
(413, '2022-01-01', 'MLODINOW, Leonard ', 'O andar do bêbado: como o acaso determina nossa vida', '519.2', '519.2', '978.85.378.0155.0', 'Rio de Janeiro ', 'Zahar Editora', NULL, NULL, '2009', '1 ed.', NULL, 'ex.1', 'p.261', 1, '50.00', 0, '003.', NULL, 1),
(414, '2022-01-01', 'CEGALLA, Domingos Paschoal ', 'Novíssima Gramática da Lingua portuguesa', NULL, '415', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1995', '1 ed.', NULL, 'ex.1', 'p.587', 1, '15.00', 0, '003.', NULL, 1),
(415, '2022-01-01', 'FALCÃO, Marcelo Milano ', 'Geoestratégia Global: economia, poder e gestão de território', NULL, '320.11', '978.85.225.0624.5', 'Rio de Janeiro ', 'FGV Editora', NULL, NULL, '2007', '1 ed.', NULL, 'ex.1', 'p.160', 1, '19.00', 0, '003.', NULL, 1),
(416, '2022-01-01', 'FOUCAULT, Michal', 'Microfísica do Poder', '321.01', '320.101', '978.85.7038.074.6', 'Rio de Janeiro ', 'Graal Ltda', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p.295', 1, '29.70', 0, '003.', NULL, 1),
(417, '2022-01-01', 'ALENCAR, José de ', 'Iracema', NULL, 'B869.93', NULL, 'São Paulo', 'Objetivo sitema de ensino', NULL, NULL, NULL, '1 ed.', NULL, 'ex.1', 'p.103', 1, '20.00', 0, '003.', NULL, 1),
(418, '2022-01-01', 'ALENCAR, José de ', 'Senhora (Coleção Ler e aprender - 12)', NULL, 'B869.93', NULL, 'São Paulo', 'Klick Editora', NULL, NULL, NULL, '1 ed.', 'V.12', 'ex.1', 'p.191', 1, '20.00', 0, '003.', NULL, 1),
(419, '2022-01-01', 'ESTY, Daniel C.', 'O verde que vale outro: como empresas inteligentes usam a vantagem competitiva para inovar', '658', '658.408', '978.85.352.2946.2', 'Rio de Janeiro ', 'Elsevier', NULL, NULL, '2008', '1 ed.', NULL, 'ex.1', 'p.347', 1, '20.00', 0, '003.', NULL, 1),
(420, '2022-01-01', 'COHEN, Stephen F.', 'Bukharin: uma biografia política', NULL, '923.247', NULL, 'Rio de Janeiro ', 'Paz e Terra', NULL, NULL, '1990', '1 ed.', NULL, 'ex.1', 'p.555', 1, '15.00', 0, '003.', NULL, 1),
(421, '2022-01-01', 'CERBASI, Gustavo', 'Casais inteligentes enriquecem juntos', NULL, '332.024', '85.7312.439.3', 'São Paulo', 'Gente Editora', NULL, NULL, '2004', '1 ed.', NULL, 'ex.1', 'p.165', 1, '27.00', 0, '003.', NULL, 1),
(422, '2022-01-01', 'MOHANA, João ', 'Sofrer e Amar (Psicologia do Sofrimento)', NULL, '152.4', NULL, 'Rio de Janeiro ', 'Agir', NULL, NULL, '1962', '1 ed.', NULL, 'ex.1', 'p.250', 1, '8.00', 0, '003.', NULL, 1),
(423, '2022-01-01', 'GUIMARÃES, Samuel Pinheiro  (Org.)', 'Coréa: visões brasileiras', '327(73)', '327', NULL, 'Brasília-DF', 'Fundação Alexandre de Gusmão ', NULL, NULL, '2002', '1 ed.', NULL, 'ex.1', 'p.370', 1, '20.00', 0, '003.', NULL, 1),
(424, '2022-01-01', 'ANDRADE, Bonifácio de ', 'Vultos e fatos históricos', NULL, '342.05', NULL, 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '2013', '1 ed.', NULL, 'ex.1', 'p.90', 1, NULL, 1, '003.', NULL, 1),
(425, '2022-01-01', 'SUASSUNA, Ariano ', 'Romance d\'a pedra do reino e o príncipe do sangue do vai-e-volta (Cap. 3) Peça', NULL, '792', NULL, '***', '***', NULL, NULL, NULL, '1 ed.', NULL, 'ex.1', 'p.66', 1, '50.00', 0, '003.', NULL, 1),
(426, '2022-01-01', 'CASE, Thomas Amos ', 'Como conseguir emprego no Brasil do século XXI', NULL, '650.14', '85.88306.02.6', 'São Paulo', 'Catho', NULL, NULL, '2004', '1 ed.', NULL, 'ex.1', 'p.341', 1, '30.00', 0, '003.', NULL, 1),
(427, '2022-01-01', 'Salvat Editores', 'Diccionario de la lengua (Salvat Léxico)', NULL, '008', '84.345.0620.3', '***', 'Enterprise', NULL, NULL, NULL, '1 ed.', NULL, 'ex.1', 'p.1111', 1, '35.00', 0, '003.', NULL, 1),
(428, '2022-01-01', 'VICENTINO, Cláudio ', 'História Geral', NULL, '907', '85.262.1714.3', 'São Paulo', 'Scipione ', NULL, NULL, '1991', '1 ed.', NULL, 'ex.1', 'p.351', 1, '60.00', 0, '003.', NULL, 1),
(429, '2022-01-01', 'FERREIRA,  Maria José Clímaco ( Irmã Salesiana)', 'Juventude, canta para as escolas secundárias', NULL, '780', NULL, 'São Paulo', 'Irmãos Vitale', NULL, NULL, NULL, '1 ed.', NULL, 'ex.1', 'p.254', 1, '18.00', 0, '003.', NULL, 1),
(430, '2022-01-01', 'Paul Monroe', 'Coleção Atualidades Pedagógicas: Histórias da educação (Volume 34)', NULL, '080 ', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1978', '1 ed.', 'v.34', 'ex.1', 'p.387', 1, '20.00', 0, '003.', NULL, 1),
(431, '2022-01-01', 'LEX, Ari ', 'Coleção Atualidades Pedagógicas: Biologia educacional (Volume 45)', NULL, '080 ', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1978', '1 ed.', 'v.45', 'ex.1', 'p.275', 1, '20.00', 0, '003.', NULL, 1),
(432, '2022-01-01', 'CHAGAS, Valnir ', 'Coleção Atualidades Pedagógicas: Didática especial de línguas modernas (Volume 68)', NULL, '080 ', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1978', '1 ed.', 'v.68', 'ex.1', 'p.512', 1, '20.00', 0, '003.', NULL, 1),
(433, '2022-01-01', 'GRISI, Rafael ', 'Coleção Atualidades Pedagógicas: Didática mínima (Volume 84)', NULL, '080 ', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1978', '1 ed.', 'v.84', 'ex.1', 'p.120', 1, '20.00', 0, '003.', NULL, 1),
(434, '2022-01-01', 'CHALLEYE, Félincien ', 'Coleção Atualidades Pedagógicas: Pequena história da filosofia (Volume 86)', NULL, '080 ', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1978', '1 ed.', 'v.86', 'ex.1', 'p.292', 1, '20.00', 0, '003.', NULL, 1),
(435, '2022-01-01', 'William C. Morse G. Max Wingo', 'Coleção Atualidades Pedagógicas: Leituras de psicologia educacional (Volume 93)', NULL, '080 ', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1978', '1 ed.', 'v.93', 'ex.1', 'p.607', 1, '20.00', 0, '003.', NULL, 1),
(436, '2022-01-01', 'COLLETTE,, Alberto ', 'Coleção Atualidades Pedagógicas: Introdução à psicologia dinâmica (Volume 98)', NULL, '080 ', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1978', '1 ed.', 'v.98', 'ex.1', 'p.298', 1, '20.00', 0, '003.', NULL, 1),
(437, '2022-01-01', 'BRUNER, Jeronme Seymour', 'Coleção Atualidades Pedagógicas: O processo da educação (Volume 126)', NULL, '080 ', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1978', '1 ed.', 'v.126', 'ex.1', 'p.87', 1, '20.00', 0, '003.', NULL, 1),
(438, '2022-01-01', 'BRUNELLE, Lucien Brunelle', 'Coleção Atualidades Pedagógicas: A não-diretividade (Volume 134)', NULL, '080 ', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1978', '1 ed.', 'v.134', 'ex.1', 'p.184', 1, '20.00', 0, '003.', NULL, 1),
(439, '2022-01-01', 'CARDOSO, Fernando Henrique e  MARTINS, Carlos Estevam ', 'Política e Sociedade       (Volume 2)', NULL, '320', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1979', '1 ed.', 'v.2', 'ex.1', 'p.269', 1, '20.00', 0, '003.', NULL, 1),
(440, '2022-01-01', 'BELTRÃO, Odacir ', 'Correspondência: Linguagem e Comunicação', NULL, '651.75', NULL, 'São Paulo', 'Atlas S.A.', NULL, NULL, '1966', '1 ed.', NULL, 'ex.1', 'p.364', 1, '41.00', 0, '003.', NULL, 1),
(441, '2022-01-01', 'PUPIO, José Antonio', 'O impossível é o que não se tentou', NULL, '380', '978.85.86439.41.4', 'São Paulo', 'Germinal', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.128', 1, '24.29', 0, '003.', NULL, 1),
(442, '2022-01-01', 'CALDAS, Waldenyr ', 'Acorde na aurora: música sertaneja e a indústria cultura', NULL, '780', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1977', '1 ed.', NULL, 'ex.1', 'p.166', 1, '14.50', 0, '003.', NULL, 1),
(443, '2022-01-01', 'GUNTHER, Max ', 'Os axiomas de Zurique', '820.73.3', '813', '978.85.01.03350.5', 'Rio de Janeiro ', 'Record', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p.155', 1, '35.00', 0, '003.', NULL, 1),
(444, '2022-01-01', 'HARADA, Susumo /  CORREIA, Vera Lúcia Godoy', 'Haikai- Imagens para ver e sentir', NULL, 'B869.91', NULL, 'Itapevi-SP', 'Acei ', NULL, NULL, '1998', '1 ed.', NULL, 'ex.1', 'p.70', 1, '25.00', 0, '003.', NULL, 1),
(445, '2022-01-01', 'CANDIDO, Antonio / CASTELLO, José Aderaldo ', 'Presença da Literatura Brasileira (III. Modernismo)', NULL, 'B869', NULL, 'São Paulo', 'DIFEL', NULL, NULL, '1983', '9 ed.', NULL, 'ex.1', 'p.374', 1, '15.00', 0, '003.', NULL, 1),
(446, '2022-01-01', 'CONY, Carlos Heitor ', 'Quase memória: quase-romance', NULL, 'B869.93', '85.7164.487.X', 'São Paulo', 'Companhia das Letras', NULL, NULL, '1995', '1 ed.', 'v.1', 'ex.1', 'p.213', 1, '12.00', 0, '003.', NULL, 1),
(447, '2022-01-01', 'GUERRA, Alexandre ', 'Atlas da Exclusão social', '331', '361.1', NULL, 'Osaco-SP', 'Secretaria de Desenvolvimento', NULL, NULL, '2007', '1 ed.', 'v.1', 'ex.1', 'p.200', 1, '10.00', 0, '003.', NULL, 1),
(448, '2022-01-01', 'GABRIEL, H.W. ', 'Auto-eficácia', NULL, '158.1', NULL, 'São Paulo', 'Best Seller Ltda', NULL, NULL, '1970', '1 ed.', 'v.1', 'ex.1', ' ', 1, '10.00', 0, '003.', NULL, 1),
(449, '2022-01-01', 'WEKWERTH, Manfred ', 'Diálogo sobre a encenação: um manual de direção teatral', NULL, NULL, NULL, 'São Paulo', 'Hucitec Editora', NULL, NULL, '1984', '1 ed.', NULL, 'ex.1', 'p.187', 1, '25.00', 0, '003.', NULL, 1),
(450, '2022-01-01', 'CORRÊA, Anna Maria Martínez ', 'Para preparar a mocidade... Fragmentos de memórias na historia da Faculdade de Farmácia e Odontologia de Araraquara', NULL, '378.81612', '85.7139.205.6', 'São Paulo', 'UNESP - Universidade Estadual Paulista', NULL, NULL, '1998', '1 ed.', 'v.1', 'ex.1', 'p.242', 1, NULL, 1, '003.', NULL, 1),
(451, '2022-01-01', 'FERGUSON, Niall ', 'A lógica do dinheiro', '338.1(09)', '330.903', '978.85.01.06369.4', 'Rio de Janeiro ', 'Record', NULL, NULL, '2007', '1 ed.', 'v.1', 'ex.1', 'p.627', 1, '36.00', 0, '003.', NULL, 1),
(452, '2022-01-01', 'HOUTART, François ', 'A agroenergia: solução para o clima ou saída da crise para o capital?', NULL, '333.79', '978.85.326.3991.2', 'Petrópolis', 'Vozes Editora', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.324', 1, '64.00', 0, '003.', NULL, 1),
(453, '2022-01-01', 'FUKUYAMA, Francis ', 'Construções de Estado: governo e organização mundial', '342.1', '320.1', '85.325.1826.5', 'Rio de Janeio', 'Rocco Ltda', NULL, NULL, '2005', '1 ed.', 'v.1', 'ex.1', 'p.168', 1, '30.00', 0, '003.', NULL, 1),
(454, '2022-01-01', 'ALIGLERI, Lilian ', 'Gestão socioambiental: responsabilidade e sustentabilidade do negócio', NULL, '304.2', '978.85.224.5505.8', 'São Paulo', 'Atlas S.A.', NULL, NULL, '2009', '1 ed.', 'v.1', 'ex.1', 'p.245', 1, '80.00', 0, '003.', NULL, 1),
(455, '2022-01-01', 'COIMBRA, Fábio ', 'Riscos operacionais: estrutura para gestão em bancos', NULL, '332.1068', '978.85.98838.54.0', 'São Paulo', 'Saint Paul Editora', NULL, NULL, '2007', '1 ed.', 'v.1', 'ex.1', 'p.146', 1, '30.00', 0, '003.', NULL, 1),
(456, '2022-01-01', 'HOFFMAN, K. Douglas ', 'Princípios do marketing de serviços: conceitos, estratégias e casos', NULL, '658.8', '978.85.221.0683.7', 'São Paulo', 'Cengage Learning', NULL, NULL, '2009', '3 ed.', NULL, 'ex.1', 'p.600', 1, '20.00', 0, '003.', NULL, 1),
(457, '2022-01-01', 'Rogério Chociay, Tania Azevedo (Org.)', 'BlogUnesp', '378.091.212.2(816.1)', '378.1664098161', '978.85.7983.169.0', 'São Paulo', 'Cultura Acadêmica', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.130', 1, NULL, 1, '003.', NULL, 1),
(458, '2022-01-01', 'BUCHSBAUM, Paulo ', 'Negócios S/A:  Adminstração na prátiva', NULL, '658', '978.85.221.1067.4', 'São Paulo', 'Cengage Learning', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.546', 1, '20.00', 0, '003.', NULL, 1),
(459, '2022-01-01', 'BROGAN, Hugh', 'Alexis de Tocqueville: o profeta da democracia', '929.82.94', '920.990', '978.85.01.08111.7', 'Rio de Janeiro ', 'Record', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.712', 1, '21.00', 0, '003.', NULL, 1),
(460, '2022-01-01', 'DALLEK, Robert', 'Nixon e Kissinger: parceiros no poder', '929.32(73)', '923.173', '978.85.378.0130.7', 'Rio de Janeiro ', 'Zahar Editora', NULL, NULL, '2009', '1 ed.', NULL, 'ex.1', 'p.734', 1, '30.00', 0, '004.', NULL, 1),
(461, '2022-01-01', 'BEATTIE, Alan ', 'Falsa economia: uma surpreendente história econômica do mundo', '330(09)', '330.9', '978.85.378.0201.4', 'Rio de Janeiro ', 'Zahar Editora', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.293', 1, '26.90', 0, '004.', NULL, 1),
(462, '2022-01-01', 'KAPELIOUK, Amnon', 'Arafat: o irredutível', NULL, '956.953', '85.7665.029.0', 'São Paulo', 'Planeta Brasil', NULL, NULL, '2004', '1 ed.', 'v.1', 'ex.1', 'p.504', 1, '15.00', 0, '003.', NULL, 1),
(463, '2022-01-01', 'ROCHA, Armando Freitas da ', 'Neuroeconomia e processo decisório: de que maneira seu cérebro toma decisões', '159.947.2', '153.83', '978.85.216.0981.1', 'Rio de Janeiro ', 'LTC Editora', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p.193', 1, '15.00', 0, '003.', NULL, 1),
(464, '2022-01-01', 'Manuel Portugal Ferreira, Nuno Rosa Reis e Fernando Ribeiro Serra', 'Negócios Internacionais e Internacionalização para economias emergentes', NULL, NULL, '978.972.757.714.9', 'Lisboa-Porto', 'Líder Edições', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.462', 1, '15.00', 0, '003.', NULL, 1),
(465, '2022-01-01', 'Alberto do Amaral Junior, Michelle Ratton Sanchez', 'A regulamentação internacional dos investimentos: algumas lições para o Brasil', NULL, '332.6730981', '978.85.7129.495.0', 'São Paulo', 'Aduaneiras LTDA', NULL, NULL, '2007', '1 ed.', 'v.1', 'ex.1', 'p.586', 1, '15.00', 0, '003.', NULL, 1),
(466, '2022-01-01', 'LADURIE, Emmanuel Le Roy', 'Saint-Simon ou o sistema do conde', '94(44)', '944.033', '85.200.0581.0', 'Rio de Janeiro ', 'Civilização Brasileira', NULL, NULL, '2004', '1 ed.', NULL, 'ex.1', 'p.583', 1, '15.00', 0, '003.', NULL, 1),
(467, '2022-01-01', 'João Carlos Ferraz, Marco Crocco, Luiz Antonio Elias (Org.)', 'Liberalização econômica e desenvolvimento', NULL, '338.9', '85.7413.158.x', 'São Paulo', 'Futura Editora', NULL, NULL, '2003', '1 ed.', 'v.1', 'ex.1', 'p.349', 1, '15.00', 0, '003.', NULL, 1),
(468, '2022-01-01', 'Gladston Mamede', 'Direito emprasarial brasileiro: empresa e atuação empresarial (Volume 1)', '34.338.93(81)', '346', '978.85.224.5226.2', 'São Paulo', 'Atlas S.A.', NULL, NULL, '2009', '1 ed.', 'v.1', 'ex.1', 'p.436', 1, '15.00', 0, '003.', NULL, 1),
(469, '2022-01-01', 'CLASON, George S. ', 'O homem mais rico da Babilônia', NULL, '100', '978.8189.020.3', 'São Paulo', 'Isis Editora', NULL, NULL, '2013', '1 ed.', 'v.1', 'ex.1', 'p.168', 1, '15.00', 0, '003.', NULL, 1),
(470, '2022-01-01', 'GOODWIN, Doris Kearns ', 'Tempos muito estranhos: Franklin e Eleanor Roosevelt: o front da Casa Branca na Segunda Guerra Mundial', '973', '973.917', '85.209.1214.1', 'Rio de Janeiro ', 'Nova Fronteira S.A.', NULL, NULL, '2001', '1 ed.', NULL, 'ex.1', 'p.652', 1, '15.00', 0, '003.', NULL, 1),
(471, '2022-01-01', 'BLAKE, Mark ', 'Nos Bastidores do Pink Floyd', NULL, '782.421660922', '978.85.63993.34.2', 'São Paulo', 'Évora Editora', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.488', 1, '15.00', 0, '003.', NULL, 1),
(472, '2022-01-01', 'Senado Federal', 'Resolução nº 20 de 1993 - Atualizado até a Resolução nº 25 de 2008', NULL, NULL, NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2016', '1 ed.', 'v.1', 'ex.1', 'p.24', 1, NULL, 1, '003.', NULL, 1),
(473, '2022-01-01', 'Senado Federal', 'Lei de Drogas ', NULL, NULL, NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.122', 1, NULL, 1, '003.', NULL, 1),
(474, '2022-01-01', 'Senado Federal', 'Lei de Drogas', NULL, NULL, NULL, 'Brasília-DF', 'Interlegis Edições', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.116', 1, NULL, 1, '003.', NULL, 1),
(475, '2022-01-01', 'Senado Federal', 'Correios e Telégrafos - Normas Federais (Atualizada até março de 2010', NULL, NULL, NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.62', 1, NULL, 1, '003.', NULL, 1),
(476, '2022-01-01', 'Senado Federal', 'Coletânea Básica Penal - Atualizada em maio  de 2010', NULL, '341.5', '978.85.7018.323.1', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.321', 1, NULL, 1, '003.', NULL, 1),
(477, '2022-01-01', 'Senado Federal', 'LDB - Lei de diretrizes e bases da educação nacional - Atualizada em março de 2017', NULL, '379.81', '978.85.7018.786.4', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2017', '1 ed.', 'v.1', 'ex.1', 'p.58', 1, NULL, 1, '003.', NULL, 1),
(478, '2022-01-01', 'Senado Federal', 'Estatuto da Igualdade Racial', NULL, '305.8', '978.85.7018.350.7', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.33', 1, NULL, 1, '003.', NULL, 1),
(479, '2022-01-01', 'Senado Federal', 'Estatuto da Igualdade Racial', NULL, '305.8', '978.85.7018.350.7', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.33', 1, NULL, 1, '003.', NULL, 1),
(480, '2022-01-01', 'Senado Federal', 'Estatuto do Idoso - Atualizada em 2018', NULL, '305.26026', '978.85.7018.928.8', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2018', '1 ed.', 'v.1', 'ex.1', 'p.39', 1, NULL, 1, '003.', NULL, 1),
(481, '2022-01-01', 'Senado Federal', 'Legislação sobre direitos autorais - Atualizada em 2011', NULL, '342.28', '978.85.7018.384.2', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.213', 1, NULL, 1, '003.', NULL, 1),
(482, '2022-01-01', 'Senado Federal', 'Lei de Turismo e Legislação Correlata - Atualizada em Junho de 2012', NULL, '338.4791026', '978.85.7018.447.4', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.105', 1, NULL, 1, '003.', NULL, 1),
(483, '2022-01-01', 'Senado Federal', 'Código de Profeção e Defesa do Consumidor', NULL, NULL, NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.100', 1, NULL, 1, '003.', NULL, 1),
(484, '2022-01-01', 'Senado Federal', 'Código de Profeção e Defesa do Consumidor', NULL, NULL, NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.2', 'p.100', 1, NULL, 1, '003.', NULL, 1),
(485, '2022-01-01', 'Senado Federal', 'Legislação Consoligada do Servidor Público', NULL, NULL, NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.372', 1, NULL, 1, '003.', NULL, 1),
(486, '2022-01-01', 'Senado Federal', 'Legislação Consoligada do Servidor Público', NULL, NULL, NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.2', 'p.372', 1, NULL, 1, '003.', NULL, 1),
(487, '2022-01-01', 'Senado Federal', 'Legislação Pesqueira - Atualizada em setembro de 2012', NULL, '341.3476', '978.85.7018.510.5', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2013', '1 ed.', 'v.1', 'ex.1', 'p.71', 1, NULL, 1, '003.', NULL, 1),
(488, '2022-01-01', 'Moacir Marques da Silva', 'Lei de Responsabilidade Fiscal para Concurso Público - Teoria e Prática', '347.51.336.2(81)(079.1)', NULL, '978.85.8399.020.8', 'São Paulo', 'Verbatim Editora', NULL, NULL, '2015', '1 ed.', 'v.1', 'ex.1', 'p.128', 1, '10.00', 0, '003.', NULL, 1),
(489, '2022-01-01', 'Senado Federal', 'Legislação desportiva', NULL, '796.02681', '978.85.7018.501.3', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2013', '1 ed.', 'v.1', 'ex.1', 'p.90', 1, NULL, 1, '003.', NULL, 1),
(490, '2022-01-01', 'Senado Federal', 'Legislação desportiva', NULL, '796.02681', '978.85.7018.501.3', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2013', '1 ed.', 'v.1', NULL, 'p.90', 1, NULL, 1, '003.', NULL, 1),
(491, '2022-01-01', 'Maria Stela Santos Graciani', 'Crianças e adolescentes têm direitos: conheça o sistema de Garantia dos Direitos e saiba como participar', '342.7.053.2/6', '342.1157', '978.85.66717.00.6', 'Brasília-DF', 'CONDECA', NULL, NULL, '2013', '1 ed.', 'v.1', 'ex.1', 'p.72', 1, '10.00', 0, '003.', NULL, 1),
(492, '2022-01-01', 'Maria Stela Santos Graciani', 'Crianças e adolescentes têm direitos: conheça o sistema de Garantia dos Direitos e saiba como participar', '342.7.053.2/6', '342.1157', '978.85.66717.00.6', 'Brasília-DF', 'CONDECA', NULL, NULL, '2013', '1 ed.', 'v.1', 'ex.2', 'p.72', 1, '10.00', 0, '003.', NULL, 1),
(493, '2022-01-01', 'Luiz Flávio Gomes', 'Crimes de responsabilidade fiscal: Lei nº 10.028/00 ', '347.51.336.2(81)(094)', NULL, '85.203.2047.3', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '2001', '1 ed.', 'v.1', 'ex.1', 'p.158', 1, '10.00', 0, '003.', NULL, 1),
(494, '2022-01-01', 'João Trindade Cavalcante Filho', 'Processo Administrativo', NULL, NULL, NULL, 'Salvador - BA', 'Podivm Edições', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.125', 1, '10.00', 0, '003.', NULL, 1),
(495, '2022-01-01', '***', 'Superando Obstáculos', NULL, '100', '3.905332.20.5', 'São Paulo', 'Aurora Production Ltda', NULL, NULL, '1999', '1 ed.', 'v.1', 'ex.1', 'p.79', 1, '10.00', 0, '003.', NULL, 1),
(496, '2022-01-01', '***', 'Lei Orgânica de Santo André - 1990 - Atualizada em 2004', NULL, NULL, NULL, 'Santo André', '***', NULL, NULL, '2004', '1 ed.', 'v.1', 'ex.1', 'p.93', 1, '10.00', 0, '003.', NULL, 1),
(497, '2022-01-01', 'Tribunal de Contas de SP', 'Lei Complementar nº 709 de 14 de Janeiro de 1993', NULL, NULL, NULL, 'São Paulo', '***', NULL, NULL, NULL, '1 ed.', 'v.1', 'ex.1', 'p.23', 1, '10.00', 0, '003.', NULL, 1),
(498, '2022-01-01', 'Senado Federal', 'Nova Lei de Licitações- Substitutivo do senador Fernando Bezerra Coelho ao Projeto de Lei (PLS) 559/2013', NULL, NULL, NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2016', '1 ed.', 'v.1', 'ex.1', 'p.115', 1, '10.00', 0, '003.', NULL, 1),
(499, '2022-01-01', '***', 'Coleção Manuais de Legislação Grátis: Licitações e contratos da administração pública (Volume 44)', NULL, NULL, '85.224.2341.5', 'São Paulo', 'Atlas S.A.', NULL, NULL, '1999', '1 ed.', 'v.1', 'ex.1', 'p.297', 1, '10.00', 0, '003.', NULL, 1),
(500, '2022-01-01', 'Senado Federal', 'Resolução nº43 de 2001 atualizado até a Resolução nº 45 de 2010', NULL, NULL, NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.109', 1, '10.00', 0, '003.', NULL, 1),
(501, '2022-01-01', 'Brasil', 'Coleção NDJ: Atualizadas em 31/12/2014 - Estatudo Nacional da Microempresa e empresa de Pequeno porte', NULL, NULL, NULL, 'São Paulo', 'DNJ - Nova Dimensão Jurídica', NULL, NULL, '2015', '1 ed.', 'v.1', 'ex.1', 'p.148', 1, '10.00', 0, '003.', NULL, 1),
(502, '2022-01-01', 'Brasil', 'Coleção NDJ: Atualizadas em 31/12/2014 - Normas Gerais de Direito Financeiro', NULL, NULL, NULL, 'São Paulo', 'DNJ - Nova Dimensão Jurídica', NULL, NULL, '2015', '1 ed.', 'v.1', 'ex.1', 'p.36', 1, '10.00', 0, '003.', NULL, 1),
(503, '2022-01-01', 'Brasil', 'Coleção NDJ: Atualizadas em 31/12/2014 - Licitações e Contratos Adminsitrativos', NULL, NULL, NULL, 'São Paulo', 'DNJ - Nova Dimensão Jurídica', NULL, NULL, '2015', '1 ed.', 'v.1', 'ex.1', 'p.415', 1, '10.00', 0, '003.', NULL, 1),
(504, '2022-01-01', 'Brasil', 'Coleção NDJ: Atualizadas em 31/12/2014 - Contratação de serviços continuados ou não', NULL, NULL, NULL, 'São Paulo', 'DNJ - Nova Dimensão Jurídica', NULL, NULL, '2015', '1 ed.', 'v.1', 'ex.1', 'p.128', 1, '10.00', 0, '003.', NULL, 1),
(505, '2022-01-01', 'Brasil', 'Coleção NDJ: Atualizadas em 31/12/2014 - Constituição da República Federativa do Brasil', NULL, NULL, NULL, 'São Paulo', 'DNJ - Nova Dimensão Jurídica', NULL, NULL, '2015', '1 ed.', 'v.1', 'ex.1', 'p.482', 1, '10.00', 0, '003.', NULL, 1),
(506, '2022-01-01', 'Brasil', 'Coleção NDJ: Atualizadas em 31/12/2014 - Consórcios Públicos', NULL, NULL, NULL, 'São Paulo', 'DNJ - Nova Dimensão Jurídica', NULL, NULL, '2015', '1 ed.', 'v.1', 'ex.1', 'p.47', 1, '10.00', 0, '003.', NULL, 1),
(507, '2022-01-01', 'Brasil', 'Coleção NDJ: Atualizadas em 31/12/2014 - Convênios e contratos de repasse', NULL, NULL, NULL, 'São Paulo', 'DNJ - Nova Dimensão Jurídica', NULL, NULL, '2015', '1 ed.', 'v.1', 'ex.1', 'p.122', 1, '10.00', 0, '003.', NULL, 1),
(508, '2022-01-01', 'Brasil', 'Coleção NDJ: Atualizadas em 31/12/2014 - Improbidade Administrativa', NULL, NULL, NULL, 'São Paulo', 'DNJ - Nova Dimensão Jurídica', NULL, NULL, '2015', '1 ed.', 'v.1', 'ex.1', 'p.22', 1, '10.00', 0, '003.', NULL, 1),
(509, '2022-01-01', 'Brasil', 'Coleção NDJ: Atualizadas em 31/12/2014 - Processo Adminsitrativo', NULL, NULL, NULL, 'São Paulo', 'DNJ - Nova Dimensão Jurídica', NULL, NULL, '2014', '1 ed.', 'v.1', 'ex.1', 'p.22', 1, '10.00', 0, '003.', NULL, 1),
(510, '2022-01-01', 'Brasil', 'Coleção NDJ: Atualizadas em 31/12/2014 - RDC', NULL, NULL, NULL, 'São Paulo', 'DNJ - Nova Dimensão Jurídica', NULL, NULL, '2014', '1 ed.', 'v.1', 'ex.1', 'p.123', 1, '10.00', 0, '003.', NULL, 1),
(511, '2022-01-01', 'Brasil', 'Coleção NDJ: Atualizadas em 31/12/2014 - Lei de Responsabilidade Fiscal', NULL, NULL, NULL, 'São Paulo', 'DNJ - Nova Dimensão Jurídica', NULL, NULL, '2015', '1 ed.', 'v.1', 'ex.1', 'p.83', 1, '10.00', 0, '003.', NULL, 1),
(512, '2022-01-01', 'Odete Medauar (org.)', 'Coletânea de legislação de direito ambiental e Constituição Federal - Atualizada em 2003', '34.502.7 (81)(094)', NULL, '85.203.2355.3', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '2003', '1 ed.', 'v.1', 'ex.1', 'p.983', 1, '10.00', 0, '003.', NULL, 1),
(513, '2022-01-01', 'Assembleia Legislativa de SP', 'Normas do Cerimonial Público', NULL, NULL, NULL, 'São Paulo', '***', NULL, NULL, '2003', '1 ed.', 'v.1', 'ex.1', 'p.116', 1, '10.00', 0, '003.', NULL, 1),
(514, '2022-01-01', 'Ministério da Saúde', 'Doenças Infecciosas e parasitárias: guia de bolso', '616.9', NULL, '978.85.334.1657.4', 'Brasília-DF', '***', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p.448', 1, NULL, 1, '003.', NULL, 1),
(515, '2022-01-01', 'Paulo Maluf', 'Plano de Trabalho para São Paulo - Tudo o que precisa ser feito para você voltar a ter orgulho de morar nesta cidade', NULL, NULL, NULL, 'São Paulo', '***', NULL, NULL, NULL, '1 ed.', 'v.1', 'ex.1', 'p.95', 1, '15.00', 0, '003.', NULL, 1),
(516, '2022-01-01', 'FUJYAMA, Y', 'Noções de literatura brasileira', NULL, '801', NULL, 'São Paulo', 'Ática', NULL, NULL, '1969', '9 ed.', NULL, 'ex.1', 'p.149', 1, '15.00', 0, '003.', NULL, 1),
(517, '2022-01-01', 'TUFANO, Douglas ', 'Prosa do romantismo: textos de literatura brasileira para análise', NULL, '869.90914', NULL, 'São Paulo', 'Moderna Editora', NULL, NULL, '1979', '1 ed.', NULL, 'ex.1', 'p.120', 1, '15.00', 0, '003.', NULL, 1),
(518, '2022-01-01', 'ANDRADE, Maria Margarida de ', 'Língua portuguesa: noções básicas para cursos superiores', NULL, '469.07', '85.224.0405.4', 'São Paulo', 'Atlas S.A.', NULL, NULL, '1989', '1 ed.', NULL, 'ex.1', 'p.208', 1, '15.00', 0, '003.', NULL, 1),
(519, '2022-01-01', 'Jared Diamond', 'Colapso: como as sociedades escolhem o fracasso ou o sucesso', '504.06', '304.28', '978.85.01.06594.0', 'Rio de janeiro', 'Record', NULL, NULL, '2000', '1 ed.', 'v.1', 'ex.1', 'p.683', 1, '15.00', 0, '003.', NULL, 1),
(520, '2022-01-01', 'AGOSTINI, Angelo Agostini / CAMPOS, Americo de (Org.)  ', 'Cabrião: semanário humorístico editado por Angelo Agostini, Americo de Campos e Antônio Manuel dos Reisd', NULL, '070.44098155', NULL, 'São Paulo', 'Impressão Oficial', NULL, NULL, '1982', '1 ed.', NULL, 'ex.1', 'p.407', 1, '15.00', 0, '003.', NULL, 1),
(521, '2022-01-01', 'Leinad Ayer de Oliveira (Org.)', 'Quilombos: a hora e a vez dos sobreviventes', NULL, '307.08996081', NULL, 'São Paulo', 'Comissão pró-índio de SP', NULL, NULL, '2001', '1 ed.', 'v.1', 'ex.1', 'p.121', 1, '15.00', 0, '003.', NULL, 1),
(522, '2022-01-01', 'LINS, João de Abreu', 'Memórias do Realengo', NULL, '921', NULL, 'São Paulo', 'Impressão Oficial', NULL, NULL, '1981', '1 ed.', NULL, 'ex.1', NULL, 1, '15.00', 0, '003.', NULL, 1),
(523, '2022-01-01', 'VARMA, Ravindra', 'Gandhi: poder, parceria e resistência', NULL, '954.035', '85.7242.038.x', 'São Paulo', 'Palas Athenas', NULL, NULL, '2002', '1 ed.', NULL, 'ex.1', 'p.96', 1, '15.00', 0, '003.', NULL, 1),
(524, '2022-01-01', 'CONDINI, Paulo ', 'Os filhos do rio: romance', NULL, 'B869.93', '8585294', 'São Paulo', 'Carthago & Forte', NULL, NULL, '1994', '1 ed.', 'v.1', 'ex.1', 'p.240', 1, '15.00', 0, '003.', NULL, 1),
(525, '2022-01-01', 'SALMONI, Anita ', 'Você voltaria?...', NULL, 'B869.93', NULL, 'São Paulo', 'Shalom Editora', NULL, NULL, '1979', '1 ed.', NULL, 'ex.1', 'p.173', 1, '15.00', 0, '003.', NULL, 1),
(526, '2022-01-01', 'DERANI, Álvaro Zarzur ', 'Por uam janela sem limites...', NULL, 'B869.915', NULL, 'São Paulo', 'A.Z.Derani', NULL, NULL, '1981', '1 ed.', NULL, 'ex.1', 'p.184', 1, '15.00', 0, '003.', NULL, 1),
(527, '2022-01-01', 'Secretaria de Estado da Cultura da Bahia', 'Semana do Estoril/ Portugal na Bahia', NULL, '921', NULL, 'Salvador - BA', '***', NULL, NULL, '1981', '1 ed.', 'v.1', 'ex.1', NULL, 1, NULL, 1, '003.', NULL, 1),
(528, '2022-01-01', 'Secretaria de Estado da Cultura da Bahia', 'Semana do Estoril/ Portugal na Bahia - Catálogo da exposição', NULL, '921', NULL, 'Salvador - BA', '***', NULL, NULL, '1981', '1 ed.', 'v.2', 'ex.1', NULL, 1, NULL, 1, '003.', NULL, 1),
(529, '2022-01-01', 'Secretaria de Estado da Cultura da Bahia', 'Semana do Estoril/ Portugal na Bahia - Inventário de Códices e de Documentos Avulsos de Arquivo Histórico Ultramarino', NULL, '921', NULL, 'Salvador - BA', '***', NULL, NULL, '1981', '1 ed.', 'v.3', 'ex.1', NULL, 1, NULL, 1, '003.', NULL, 1),
(530, '2022-01-01', 'Brasil', 'Estatuto da criança e adolescente, Plano Nacional de Educação em Direitos Humanos e Carta da Terra', NULL, '348', NULL, 'São Paulo', 'Instituto Paulo Freire', NULL, NULL, '2007', '1 ed.', 'v.1', 'ex.1', 'p.132', 1, NULL, 1, '003.', NULL, 1),
(531, '2022-01-01', 'CODEPE-SP', 'Manual de Municipalização: Conselho Estadual de defesa dos direitos da pessoa humana', NULL, '348', NULL, 'São Paulo', 'Impressão Oficial', NULL, NULL, NULL, '1 ed.', 'v.1', 'ex.1', 'p.51', 1, NULL, 1, '003.', NULL, 1),
(532, '2022-01-01', 'TAVARES, Maria da Conceição T.G. ', 'Tire dúvidas de português', NULL, '415', NULL, 'São Paulo', 'Europa Editora', NULL, NULL, '1990', '1 ed.', NULL, 'ex.1', 'p.151', 1, '20.00', 0, '003.', NULL, 1),
(533, '2022-01-01', 'Conselho Municipal de Direitos da Criança e adolescentes', 'O exercício da Democracia participativa', NULL, '323 R', NULL, 'Osaco-SP', '***', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', NULL, 1, '20.00', 0, '003.', NULL, 1),
(534, '2022-01-01', 'LAGES, Solange Bernard ', 'Alagoas Roteiro cultural e turístico', NULL, '921', NULL, 'Maceió-AL', '***', NULL, NULL, '1979', '1 ed.', NULL, 'ex.1', 'p.205', 1, '20.00', 0, '003.', NULL, 1),
(535, '2022-01-01', 'KIM, Yoo Na', 'A jovem Coréia', NULL, '951.95', NULL, 'São Paulo', 'SSUA Editora', NULL, NULL, '2008', '1 ed.', NULL, 'ex.1', 'p.176', 1, '20.00', 0, '003.', NULL, 1),
(536, '2022-01-01', 'MASIERO, Palmyro ', 'São José dos Campos de Osmar Fonseca', NULL, '921', NULL, 'São José dos Campos - SP', 'Fundação Valeparaibana de Ensino', NULL, NULL, '1989', '1 ed.', NULL, 'ex.1', 'p.122', 1, '20.00', 0, '003.', NULL, 1),
(537, '2022-01-01', 'BRASIL, Olivetti Edições', 'Antártida: o Sexto continente', NULL, '919.89', NULL, '***', '***', NULL, NULL, '1982', '1 ed.', NULL, 'ex.1', 'p.77', 1, '20.00', 0, '003.', NULL, 1),
(538, '2022-01-01', 'SÃO PAULO, Secretaria de Cultura de', '80 anos da Academia Brasileira de Letas', NULL, '928', NULL, 'São Paulo', 'Impressão Oficial', NULL, NULL, '1987', '1 ed.', NULL, 'ex.1', NULL, 1, NULL, 1, '003.', NULL, 1),
(539, '2022-01-01', 'GRACIOTTI, Mário ', 'Europa Tranquila: Crônicas de viagens para adultos e crianças', NULL, 'B869.93', NULL, 'São Paulo', 'Clube do Livro Ltda', NULL, NULL, '1959', '1 ed.', NULL, 'ex.1', 'p.300', 1, '20.00', 0, '003.', NULL, 1),
(540, '2022-01-01', 'FRENETTE, Marco ', 'Os caiçaras contam', NULL, '080.92', NULL, 'São Paulo', 'Publisher Brasil', NULL, NULL, '2000', '1 ed.', NULL, 'ex.1', 'p.80', 1, '20.00', 0, '003.', NULL, 1),
(541, '2022-01-01', 'MALIN, Mauro', 'Memórias do Comércio', NULL, '923.809', NULL, 'São Paulo', 'SESC/SENAC/SEBRAE', NULL, NULL, '1995', '1 ed.', NULL, 'ex.1', 'p.157', 1, NULL, 1, '003.', NULL, 1),
(542, '2022-01-01', '***', '90 Anos do Genocídio Armênio', NULL, '301', NULL, 'São Paulo', '***', NULL, NULL, '2005', '1 ed.', NULL, 'ex.1', NULL, 1, NULL, 1, '003.', NULL, 1),
(543, '2022-01-01', 'Brasil', 'Estatutuo das Cidades', NULL, '348', '85.7420.372.6', 'Brasília-DF', 'Malheiros', NULL, NULL, '2001', '1 ed.', NULL, 'ex.1', 'p.440', 1, NULL, 1, '003.', NULL, 1),
(544, '2022-01-01', 'Fábio Ulhoa Coelho', 'Comentários à lei de falências', '347.736.(81)(094.56)', '348', '978.85.02.08441.4', 'São Paulo', 'Saraiva', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p.592', 1, '20.00', 0, '003.', NULL, 1),
(545, '2022-01-01', '***', 'Lei Orgânica do Município de São Paulo', NULL, '348', '85.224.1751.2', 'São Paulo', 'Atlas S.A.', NULL, NULL, '1997', '1 ed.', NULL, 'ex.1', 'p.84', 1, NULL, 1, '003.', NULL, 1),
(546, '2022-01-01', 'Marcelo Figueiredo', 'Probidade Administrativa', NULL, '342', '85.7420.191.X', 'São Paulo', 'Malheiros', NULL, NULL, '1998', '1 ed.', NULL, 'ex.1', 'p.381', 1, '20.00', 0, '003.', NULL, 1),
(547, '2022-01-01', 'Câmara Municipal de Araras', 'Lei dos Serviços Públicos Municipais de Araras-SP', NULL, '348', '978.85.5925.002.2', 'Araras-SP', '***', NULL, NULL, '2015', '1 ed.', NULL, 'ex.1', 'p.187', 1, NULL, 1, '003.', NULL, 1),
(548, '2022-01-01', 'Câmara Municipal de Araras', 'Leis dos Conselhos Municipais de Araras-SP', NULL, '348', '978.85.5925.003.9', 'Araras-SP', '***', NULL, NULL, '2015', '1 ed.', NULL, 'ex.1', 'p.118', 1, NULL, 1, '003.', NULL, 1),
(549, '2022-01-01', 'Tribunal de Justiça de São Paulo', 'Lei Maria da Penha e a atitude para a paz', NULL, '348', NULL, 'São Paulo', 'Tribunal de Justiça de SP', NULL, NULL, '2013', '1 ed.', NULL, 'ex.1', 'p.43', 1, NULL, 1, '003.', NULL, 1),
(550, '2022-01-01', 'Tribunal de Justiça de São Paulo', 'Lei Maria da Penha e a atitude para a paz', NULL, '348', NULL, 'São Paulo', 'Tribunal de Justiça de SP', NULL, NULL, '2013', '1 ed.', NULL, 'ex.2', 'p.43', 1, NULL, 1, '003.', NULL, 1),
(551, '2022-01-01', 'Amir Antonio Khair', 'Lei de Responsabilidade Fiscal - Guia de orientação para as prefeituras', '336.2.34', '341.392', NULL, 'Brasília-DF', 'BNDES', NULL, NULL, '2000', '1 ed.', NULL, 'ex.1', 'p.144', 1, NULL, 1, '003.', NULL, 1),
(552, '2022-01-01', 'Câmara de Barueri -SP', 'Regimento Interno da Câmara de Barueri - SP', NULL, '348', NULL, 'Barueri-SP', '***', NULL, NULL, '2013', '1 ed.', 'v.1', 'ex.1', 'p.107', 1, NULL, 1, '003.', NULL, 1),
(553, '2022-01-01', 'Brasil', 'Lei Geral de Proteção de dados - Atualizada em  2019', NULL, '348', NULL, 'Brasília-DF', 'Migalhas', NULL, NULL, '2018', '1 ed.', 'v.1', 'ex.1', 'p.76', 1, NULL, 1, '003.', NULL, 1),
(554, '2022-01-01', 'André L. Del Negri', 'Controle de Constitucionalidade no processo legislativo: teoria da legitimidade democrática', NULL, '342.05', '85.89148.18.1', 'Belo Horizonte', 'Fórum Editora', NULL, NULL, '2003', '1 ed.', 'v.1', 'ex.1', 'p.120', 1, '20.00', 0, '003.', NULL, 1),
(555, '2022-01-01', 'Senado Federal', 'Correios e Telégrafos - Normas Federais (Atualizada até março de 2010', NULL, '348', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.3', 'p.62', 1, NULL, 1, '003.', NULL, 1),
(556, '2022-01-01', 'Senado Federal', 'Lei Antidrogas', NULL, '348', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2006', '1 ed.', 'v.1', 'ex.1', 'p.122', 1, NULL, 1, '003.', NULL, 1),
(557, '2022-01-01', 'Senado Federal', 'Estatuto da Microempresa', NULL, '348', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2009', '1 ed.', 'v.1', 'ex.1', 'p.158', 1, NULL, 1, '003.', NULL, 1),
(558, '2022-01-01', 'Senado Federal', 'Legislação sobre direitos autorais - Atualizada em 2011', NULL, '342.28', '978.85.7018.384.2', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.3', 'p.213', 1, NULL, 1, '003.', NULL, 1),
(559, '2022-01-01', 'Jessé Torres Pereira Junior', 'Comentários à lei de licitações e contratações da administraçaõ pública (atualizada em 2001)', NULL, '342.8106', '85.7147.055.3', 'Rio de janeiro', 'Renovar', NULL, NULL, '2002', '1 ed.', 'v.1', 'ex.1', 'p.1248', 1, '15.00', 0, '003.', NULL, 1),
(560, '2022-01-01', 'SÃO PAULO, Universidade de ', 'O Museu Paulista da Universidade de São Paulo', NULL, '981.007', NULL, 'São Paulo', 'Universidada de São Paulo - Usp', NULL, NULL, '1984', '1 ed.', NULL, 'ex.1', 'p.319', 1, NULL, 1, '003.', NULL, 1),
(561, '2022-01-01', 'VOLPI, Alfredo ', 'As pequenas grandes obras - três décadas de pintura', NULL, '750', NULL, 'São Paulo', '***', NULL, NULL, '1980', '1 ed.', NULL, 'ex.1', NULL, 1, '15.00', 0, '003.', NULL, 1),
(562, '2022-01-01', 'HILL,  Napoleon/ STONE, W. Clement ', 'Sucesso através da atitude mental positiva', NULL, '158.1', NULL, 'São Paulo', 'Best Seller Ltda', NULL, NULL, '1965', '1 ed.', NULL, 'ex.1', 'p.294', 1, '15.00', 0, '003.', NULL, 1),
(563, '2022-01-01', 'CHESSMAN, Caryl ', 'A lei quer que eu morra', NULL, '810', NULL, 'São Paulo', '***', NULL, NULL, '1957', '1 ed.', NULL, 'ex.1', 'p.306', 1, '15.00', 0, '003.', NULL, 1),
(564, '2022-01-01', 'CHESSMAN, Caryl ', '2455, A cela da morte', NULL, '810', NULL, 'São Paulo', '***', NULL, NULL, '1957', '1 ed.', NULL, 'ex.1', 'p.350', 1, '15.00', 0, '003.', NULL, 1),
(565, '2022-01-01', 'PETER, Laurence J. ', 'Todo mundo é incompetente inclusive você: as leis da incompetência', '65(088.3)', '658', NULL, 'Rio de Janeiro', '***', NULL, NULL, '1976', '1 ed.', NULL, 'ex.1', 'p.195', 1, '15.00', 0, '003.', NULL, 1),
(566, '2022-01-01', 'BAUER, Eddy', '1942 - História Polêmica da Segunda guerra mundial', NULL, '940', NULL, '***', '***', NULL, NULL, '1966', '1 ed.', NULL, 'ex.1', 'p.465', 1, '15.00', 0, '003.', NULL, 1),
(567, '2022-01-01', 'CHOPRA, Deepak ', 'As sete leis espirituais do sucesso', NULL, '158.1', NULL, 'São Paulo', 'Best Seller Ltda', NULL, NULL, '1998', '1 ed.', NULL, 'ex.1', 'p.103', 1, '15.00', 0, '003.', NULL, 1),
(568, '2022-01-01', 'RAMPA, Lobsang ', 'A chama sagrada', NULL, '820', NULL, 'Rio de Janeiro', 'Record', NULL, NULL, NULL, '2 ed.', NULL, 'ex.1', 'p.172', 1, '15.00', 0, '003.', NULL, 1),
(569, '2022-01-01', 'ACQUARONE, F. ', 'História da navegação - A conquista do mar', NULL, ' 909', NULL, 'Rio de Janeiro', 'Irmãos Pongetti', NULL, NULL, '1960', '1 ed.', 'v.1', 'ex.1', 'p.269', 1, '15.00', 0, '003.', NULL, 1),
(570, '2022-01-01', 'John Reed', 'Dez dias que abalaram o mundo', NULL, '330.01', NULL, 'Rio de Janeiro', 'Record', NULL, NULL, '1967', '1 ed.', 'v.1', 'ex.1', 'p.386', 1, '15.00', 0, '003.', NULL, 1),
(571, '2022-01-01', 'UCHÔA JÚNIOR, João ', 'Só é gordo quem quer', '613.24', '641.563', '85.7030.072.7', 'Rio de Janeiro', 'Guanabara koogan', NULL, NULL, '1986', '1 ed.', NULL, 'ex.1', 'p.109', 1, '15.00', 0, '003.', NULL, 1),
(572, '2022-01-01', 'Platão', 'Diálogos', NULL, '100', NULL, 'São Paulo', 'Hemus Ltda', NULL, NULL, NULL, '1 ed.', 'v.1', 'ex.1', 'p.236', 1, '15.00', 0, '003.', NULL, 1),
(573, '2022-01-01', 'MUPHY, Dr. Joseph ', ' O  poder dos subconsciente', NULL, '158.1', NULL, 'Rio de Janeiro', 'Record', NULL, NULL, NULL, '1 ed.', NULL, 'ex.1', 'p.243', 1, '15.00', 0, '003.', NULL, 1),
(574, '2022-01-01', 'ROCHESTER, Conde J. W. ', 'O faraó Mernephthan', NULL, '133.9', NULL, 'São Paulo', '***', NULL, NULL, NULL, '1 ed.', NULL, 'ex.1', 'p.278', 1, '15.00', 0, '003.', NULL, 1),
(575, '2022-01-01', 'DÄNIKEN, Erich von ', 'Eramos deuses astronautas?', NULL, '813', NULL, 'São Paulo', 'Melhoramentos Editora', NULL, NULL, '1971', '1 ed.', NULL, 'ex.1', 'p.183', 1, '15.00', 0, '003.', NULL, 1),
(576, '2022-01-01', 'Maurice Druon', 'MAGNO, Alexandre', NULL, '813', NULL, 'São Paulo', 'Difusão européia do livro', NULL, NULL, '1963', '1 ed.', NULL, 'ex.1', 'p.311', 1, '15.00', 0, '003.', NULL, 1),
(577, '2022-01-01', 'Robert Norman Vivian Cajado Nicol', 'Microeconomia', NULL, '338.5', '852240060.1', 'São Paulo', 'Atlas S.A.', NULL, NULL, '1985', '1 ed.', 'v.1', 'ex.1', 'p.284', 1, '15.00', 0, '003.', NULL, 1),
(578, '2022-01-01', 'LIMA, José Geraldo de ', 'Custos - Cálculos, sistemas e análises', NULL, '657.3', NULL, 'São Paulo', 'Atlas S.A.', NULL, NULL, '1973', '1 ed.', NULL, 'ex.1', 'p.270', 1, '15.00', 0, '003.', NULL, 1),
(579, '2022-01-01', 'FURTADO, Celso ', 'A economia Latino-americana - Formação histórica e problemas contemporâneos', NULL, '657.3', NULL, 'São Paulo', 'Companhia Editora Nacional', NULL, NULL, '1978', '1 ed.', 'v.1', 'ex.1', 'p.339', 1, '15.00', 0, '003.', NULL, 1),
(580, '2022-01-01', 'NETTO, Cesar Dacorso ', 'Elementos de Cálculo Infinitesimal', NULL, '658.15', NULL, 'São Paulo', 'Companhia Editora Nacional', NULL, NULL, '1977', '1 ed.', NULL, 'ex.1', 'p.478', 1, '15.00', 0, '003.', NULL, 1),
(581, '2022-01-01', 'PIMENTEL, Rosalina Chedian ', 'Estado, economia, trabalho e sociedade: o mosaico de uma nação', '36:33:321', '657.3', '978.85.60114.26.9', 'Franca-SP', 'Unifran Editora', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p.274', 1, '15.00', 0, '003.', NULL, 1),
(582, '2022-01-01', 'Jacques Généreux', 'O horror político: o horror não é econômico', '33(091)', '330.0904', '85.286.0664.3', 'Rio de Janeiro', 'Bertrand Brasil', NULL, NULL, '2000', '1 ed.', 'v.1', 'ex.1', 'p.144', 1, '15.00', 0, '003.', NULL, 1),
(583, '2022-01-01', 'Osvaldo Sunkel e Pedro Vaz', 'A Teoria do Desenvolvimento Econômico', NULL, '330.01', NULL, 'São Paulo', 'Difel', NULL, NULL, '1976', '1 ed.', 'v.1', 'ex.1', 'p.243', 1, '15.00', 0, '003.', NULL, 1),
(584, '2022-01-01', 'J.F. Normano', 'Evolução Econômica do Brail', NULL, '330.01', NULL, 'São Paulo', 'Companhia Editora Nacional', NULL, NULL, '1939', '1 ed.', 'v.1', 'ex.1', 'p.313', 1, '15.00', 0, '003.', NULL, 1),
(585, '2022-01-01', 'FRANCO, Hilário ', 'Contabilidade Geral', NULL, '658.15', NULL, 'São Paulo', 'Atlas S.A.', NULL, NULL, '1972', '1 ed.', NULL, 'ex.1', 'p.334', 1, '15.00', 0, '003.', NULL, 1),
(586, '2022-01-01', 'HERRMANN JÚNIOR, Frederico', 'Análise de Balanços para a administração financeira', NULL, '657.3', NULL, 'São Paulo', 'Atlas S.A.', NULL, NULL, '1972', '1 ed.', NULL, 'ex.1', 'p.179', 1, '15.00', 0, '003.', NULL, 1),
(587, '2022-01-01', 'Guilherme Bomfim Dei Verni-Neri e Celso Ayres Issa', 'Prática das transações imobiliárias', NULL, '333', NULL, 'São Paulo', 'Companhia Editora Nacional', NULL, NULL, '1978', '1 ed.', 'v.1', 'ex.1', 'p.182', 1, '15.00', 0, '003.', NULL, 1),
(588, '2022-01-01', 'SAMUELSON, Paul A. ', 'Introdução à análise econômica                         Volulme 1', NULL, '657.3', NULL, 'Rio de Janeiro', 'Agir', NULL, NULL, '1971', '1 ed.', 'v.1', 'ex.1', 'p.559', 1, '15.00', 0, '003.', NULL, 1),
(589, '2022-01-01', 'SAMUELSON, Paul A. ', 'Introdução à análise econômica                         Volulme 2', NULL, '657.3', NULL, 'Rio de Janeiro', 'Agir', NULL, NULL, '1971', '1 ed.', 'v.2', 'ex.1', 'p.1215', 1, '15.00', 0, '003.', NULL, 1),
(590, '2022-01-01', 'HERMANN JUNIOR, Frederico', 'Contabilidade Superior - Teoria econômica da contabilidade', NULL, '657.3', NULL, 'São Paulo', 'Atlas S.A.', NULL, NULL, '1970', '1 ed.', NULL, 'ex.1', 'p.264', 1, '15.00', 0, '003.', NULL, 1),
(591, '2022-01-01', 'SANVICENTE, Antônio Zaratto ', 'Adminstração financeira', NULL, '658.15', '85.224.0221.3', 'São Paulo', 'Atlas S.A.', NULL, NULL, '1987', '1 ed.', 'v.1', 'ex.1', 'p.283', 1, '15.00', 0, '003.', NULL, 1),
(592, '2022-01-01', 'MONTO FILHO, André Franco ', 'Manual de economia', NULL, '657.3', '85.02.01800.0', 'São Paulo', 'Saraiva', NULL, NULL, '1997', '1 ed.', NULL, 'ex.1', 'p.507', 1, '15.00', 0, '003.', NULL, 1),
(593, '2022-01-01', 'David Torres', 'Revelando o sistema tributário brasileiro', '34.336.2(81)', '343', '85.98119.01.6', 'São Paulo', 'Sinafresp Edições', NULL, NULL, '2003', '1 ed.', 'v.1', 'ex.1', 'p.560', 1, '15.00', 0, '003.', NULL, 1),
(594, '2022-01-01', 'HAY, Louise L. ', 'Você pode curar sua vida', '159.947', '158.1', '978.85.7684.843.1', 'Rio de Janeiro', 'Best Seller Ltda', NULL, NULL, '2014', '1 ed.', NULL, 'ex.1', 'p.254', 1, '15.00', 0, '003.', NULL, 1),
(595, '2022-01-01', 'James Kynge ', 'A China sacode o mundo: a ascenção de uma nação com fome', '338.1(510)', '330.951', '978.85.250.4338.2', 'São Paulo', 'Globo', NULL, NULL, '2007', '1 ed.', 'v.1', 'ex.1', 'p.335', 1, '20.00', 0, '003.', NULL, 1),
(596, '2022-01-01', 'Luiz Alberto de Farias', 'Opiniões voláteis: opniaão pública e construção de sentido', NULL, '303.38', '978.85.7814.397.8', 'São Paulo', 'Educação Metodista', NULL, NULL, '2019', '1 ed.', 'v.1', 'ex.1', 'p.144', 1, '20.00', 0, '003.', NULL, 1),
(597, '2022-01-01', 'Aline Marinho e Lilian Bindandi', 'Políticas: a falta de representatividade feminina nos espaços de poder público', NULL, '323', NULL, 'São Paulo', '***', NULL, NULL, '2017', '1 ed.', 'v.1', 'ex.1', 'p.211', 1, '20.00', 0, '003.', NULL, 1),
(598, '2022-01-01', 'Lúcia Avelar', 'Mulheres na elite política brasileira', NULL, '323.340981', '85.7504.018.9', 'São Paulo', 'UNESP - Universidade Estadual Paulista', NULL, NULL, '2001', '1 ed.', 'v.1', 'ex.1', 'p.188', 1, '20.00', 0, '003.', NULL, 1),
(599, '2022-01-01', 'MARTINS, Osvaldo (Org.)', 'Mario Covas: democracia: defender, conquistar, praticar', '321.009.81', '923.2', '978.85.7060.990.8', 'São Paulo', 'Impressão Oficial', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p.352', 1, '20.00', 0, '003.', NULL, 1),
(600, '2022-01-01', 'Dalmo de Abreu Dallari', 'Elementos da Teoria geral do Estado', NULL, '320.101', '978.85.02.07329.6', 'São Paulo', 'Saraiva', NULL, NULL, '2009', '1 ed.', 'v.1', 'ex.1', 'p.314', 1, '20.00', 0, '003.', NULL, 1),
(601, '2022-01-01', 'GIDDENS, Anthony ', 'Continente turbulento e poderoso: qual o futuro da europa?', '94(4)', '940', '978.85.393.0579.7', 'São Paulo', 'UNESP - Universidade Estadual Paulista', NULL, NULL, '2014', '1 ed.', NULL, 'ex.1', 'p.278', 1, '20.00', 0, '003.', NULL, 1),
(602, '2022-01-01', 'João Caramez', 'Ética na Política', NULL, '320', NULL, 'São Paulo', 'LifePress', NULL, NULL, '2002', '1 ed.', 'v.1', 'ex.1', 'p.214', 1, '20.00', 0, '003.', NULL, 1),
(603, '2022-01-01', 'Nuno Coimbra Mesquita(Org.)', 'Brasil: 25 anos e democracia: participação, sociedade civil, cultura e política', NULL, '320.981', '978.85.7504.196.3', 'Rio de Janeiro', 'Fundação Konrad Adenauer', NULL, NULL, '2016', '1 ed.', 'v.1', 'ex.1', 'p.264', 1, '20.00', 0, '003.', NULL, 1),
(604, '2022-01-01', 'Luis Manuel Fonseca, Maurício Zockun e Renata Porto Adri (Coord.)', 'Corrupeção, ética e moralidade administrativa', '351.9', '341.55172', '978.85.7700.175.0', 'Belo Horizonte', 'Fórum Editora', NULL, NULL, '2008', '1 ed.', 'v.1', 'ex.1', 'p.380', 1, '20.00', 0, '003.', NULL, 1),
(605, '2022-01-01', '***', 'Coletânea da Democracia: Guia', NULL, '321.8', NULL, 'Curitiba-PR', 'Instituto Atuação', NULL, NULL, '2017', '1 ed.', 'v.1', 'ex.1', 'p.12', 1, '20.00', 0, '003.', NULL, 1),
(606, '2022-01-01', 'Gene Sharp e Bruce Jenkins', 'Coletânea da Democracia: O antegolpe', NULL, '321.09', '978.85.69855.07.1', 'Curitiba-PR', 'Instituto Atuação', NULL, NULL, '2017', '1 ed.', 'v.1', 'ex.1', 'p.82', 1, '20.00', 0, '003.', NULL, 1),
(607, '2022-01-01', 'Larry Diamond', 'Coletânea da Democracia: Para entender a democracia', NULL, '321.8', '978.85.69855.08.8', 'Curitiba-PR', 'Instituto Atuação', NULL, NULL, '2017', '1 ed.', 'v.1', 'ex.1', 'p.473', 1, '20.00', 0, '003.', NULL, 1),
(608, '2022-01-01', 'Giovanni Sartori', 'Coletânea da Democracia: O que é democracia?', NULL, '321.8', '978.85.69855.10.1', 'Curitiba-PR', 'Instituto Atuação', NULL, NULL, '2017', '1 ed.', 'v.1', 'ex.1', 'p.402', 1, '20.00', 0, '003.', NULL, 1),
(609, '2022-01-01', 'Ernst-Wolfgang Böckenförde', 'Coletânea da Democracia: Estado de direito e democracia', NULL, '321.8', '978.85.69855.09.5', 'Curitiba-PR', 'Instituto Atuação', NULL, NULL, '2017', '1 ed.', 'v.1', 'ex.1', 'p.233', 1, '20.00', 0, '003.', NULL, 1),
(610, '2022-01-01', 'HARADA, Susumo ', 'Juqueri 1825', NULL, '921', NULL, 'São Paulo', '***', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p.38', 1, NULL, 1, '003.', NULL, 1),
(611, '2022-01-01', 'HARADA, Susumo ', 'Cotia E.F.S. 1875', NULL, '921', NULL, 'São Paulo', '***', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p.38', 1, NULL, 1, '003.', NULL, 1),
(612, '2022-01-01', 'Terasa Cristina de Novaes Marques', 'O voto feminino no Brasil', '342.83(81)', '321.8', '978.85.402.0675.5', 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '2018', '1 ed.', 'v.1', 'ex.1', 'p.149', 1, '10.00', 0, '003.', NULL, 1);
INSERT INTO `librarycollection` (`id`, `registrationDate`, `author`, `title`, `cdu`, `cdd`, `isbn`, `local`, `publisher_edition`, `number`, `month`, `year`, `edition`, `volume`, `copyNumber`, `pageNumber`, `typeAcquisitionId`, `price`, `prohibitedSale`, `provider`, `exclusionInfoTerm`, `registeredByUserId`) VALUES
(613, '2022-01-01', 'Lucila Pinsard Vianna', 'De invisíveis a protagonistas: populações tradicionais e unidades de conservação', '634.41', '333', '978.85.7419.852.1', 'São Paulo', 'Annablume', NULL, NULL, '2008', '1 ed.', 'v.1', 'ex.1', 'p.340', 1, '10.00', 0, '003.', NULL, 1),
(614, '2022-01-01', 'Marisa von Bülow', 'A batalha do livre comércio: a construção de redes transnacionais da sociedade civil nas Américas', '32', '320', '978.85.393.0505.6', 'São Paulo', 'UNESP - Universidade Estadual Paulista', NULL, NULL, '2014', '1 ed.', 'v.1', 'ex.1', 'p.293', 1, NULL, 1, '003.', NULL, 1),
(615, '2022-01-01', 'Alcides Redondo Rodrigues (Org.)', 'O vereador e a câmara municipal', NULL, '351', NULL, 'Rio de Janeiro', '***', NULL, NULL, '2001', '1 ed.', 'v.1', 'ex.1', 'p.132', 1, '10.00', 0, '003.', NULL, 1),
(616, '2022-01-01', 'Humberto Dantas', 'Educação Política: sugestões de ação a partir de nossa atuação', NULL, '328.81', '978.85.7504.210.6', 'Rio de Janeiro', 'Fundação Konrad Adenauer', NULL, NULL, '2017', '1 ed.', NULL, 'ex.1', 'p.133', 1, NULL, 1, '003.', NULL, 1),
(617, '2022-01-01', 'Humberto Dantas', 'Educação Política: sugestões de ação a partir de nossa atuação', NULL, '328.81', '978.85.7504.210.6', 'Rio de Janeiro', 'Fundação Konrad Adenauer', NULL, NULL, '2017', '1 ed.', NULL, 'ex.2', 'p.133', 1, NULL, 1, '003.', NULL, 1),
(618, '2022-01-01', 'Humberto Dantas', 'Educação Política: sugestões de ação a partir de nossa atuação', NULL, '328.81', '978.85.7504.210.6', 'Rio de Janeiro', 'Fundação Konrad Adenauer', NULL, NULL, '2017', '1 ed.', NULL, 'ex.3', 'p.133', 1, NULL, 1, '003.', NULL, 1),
(619, '2022-01-01', 'DANTAS, Humberto ', 'Educação Política: sugestões de ação a partir de nossa atuação', NULL, '328.81', '978.85.7504.210.6', 'Rio de Janeiro', 'Fundação Konrad Adenauer', NULL, NULL, '2017', '1 ed.', NULL, 'ex.4', 'p.133', 1, NULL, 1, '003.', NULL, 1),
(620, '2022-01-01', 'DANTAS, Humberto ', 'Educação Política: sugestões de ação a partir de nossa atuação', NULL, '328.81', '978.85.7504.210.6', 'Rio de Janeiro', 'Fundação Konrad Adenauer', NULL, NULL, '2017', '1 ed.', NULL, 'ex.5', 'p.133', 1, NULL, 1, '003.', NULL, 1),
(621, '2022-01-01', 'Humberto Dantas e Bruno Souza da Silva', 'Poder Legislativo Municipal: para entender de política começa aqui', NULL, '328.81', '978.85.7504.215.1', 'Rio de Janeiro', 'Fundação Konrad Adenauer', NULL, NULL, '2018', '1 ed.', 'v.1', 'ex.1', 'p.164', 1, NULL, 1, '003.', NULL, 1),
(622, '2022-01-01', 'Humberto Dantas e Bruno Souza da Silva', 'Poder Legislativo Municipal: para entender de política começa aqui', NULL, '328.81', '978.85.7504.215.1', 'Rio de Janeiro', 'Fundação Konrad Adenauer', NULL, NULL, '2018', '1 ed.', 'v.1', 'ex.2', 'p.164', 1, NULL, 1, '003.', NULL, 1),
(623, '2022-01-01', 'Humberto Dantas e Bruno Souza da Silva', 'Poder Legislativo Municipal: para entender de política começa aqui', NULL, '328.81', '978.85.7504.215.1', 'Rio de Janeiro', 'Fundação Konrad Adenauer', NULL, NULL, '2018', '1 ed.', 'v.1', 'ex.3', 'p.164', 1, NULL, 1, '003.', NULL, 1),
(624, '2022-01-01', 'Humberto Dantas e Bruno Souza da Silva', 'Poder Legislativo Municipal: para entender de política começa aqui', NULL, '328.81', '978.85.7504.215.1', 'Rio de Janeiro', 'Fundação Konrad Adenauer', NULL, NULL, '2018', '1 ed.', 'v.1', 'ex.4', 'p.164', 1, NULL, 1, '003.', NULL, 1),
(625, '2022-01-01', 'Humberto Dantas e Bruno Souza da Silva', 'Poder Legislativo Municipal: para entender de política começa aqui', NULL, '328.81', '978.85.7504.215.1', 'Rio de Janeiro', 'Fundação Konrad Adenauer', NULL, NULL, '2018', '1 ed.', 'v.1', 'ex.5', 'p.164', 1, NULL, 1, '003.', NULL, 1),
(626, '2022-01-01', 'Dulce Maria Pereira', 'Gestão Ambiental', '504.06', '363', '978.85.98601.57.1', 'São Paulo', 'UFOP - Universidade Federal de Ouro Preto', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.203', 1, '30.00', 0, '003.', NULL, 1),
(627, '2022-01-01', 'Irce Fernandes Gomes Guimarães', 'Estudo sobre qualidades e eficiências na gestão pública', '352', '351', '978.85.98601.43.4', 'São Paulo', 'UFOP - Universidade Federal de Ouro Preto', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.137', 1, '30.00', 0, '003.', NULL, 1),
(628, '2022-01-01', 'Iracilene Carvalho Ferreira', 'Cerimonial público', '395', '351', '978.85.98601.27.4', 'São Paulo', 'UFOP - Universidade Federal de Ouro Preto', NULL, NULL, '2008', '1 ed.', 'v.1', 'ex.1', 'p.102', 1, '30.00', 0, '003.', NULL, 1),
(629, '2022-01-01', 'Jaime Antônio Scheffler Sardi', 'Temas de Adminstração', '658', '351', NULL, 'São Paulo', 'UFOP - Universidade Federal de Ouro Preto', NULL, NULL, '2007', '1 ed.', 'v.1', 'ex.1', 'p.113', 1, '30.00', 0, '003.', NULL, 1),
(630, '2022-01-01', 'Jorge Luiz Brescia Murta', 'Estatística aplicada à administração', '519.2.658', '351', NULL, 'São Paulo', 'UFOP - Universidade Federal de Ouro Preto', NULL, NULL, '2008', '1 ed.', 'v.1', 'ex.1', 'p.171', 1, '30.00', 0, '003.', NULL, 1),
(631, '2022-01-01', 'Jaime Antônio Scheffler Sardi', 'Estado e sociedade no Brasil', '316.3:32(81)', '351', NULL, 'São Paulo', 'UFOP - Universidade Federal de Ouro Preto', NULL, NULL, '2009', '1 ed.', 'v.1', 'ex.1', 'p.107', 1, '30.00', 0, '003.', NULL, 1),
(632, '2022-01-01', 'Yára Mattos', 'Cultura Brasileira: Aspectos Gerais e Institucionais', '316.72(81)', '351', '96', 'São Paulo', 'UFOP - Universidade Federal de Ouro Preto', NULL, NULL, '2009', '1 ed.', 'v.1', 'ex.1', 'p.96', 1, '30.00', 0, '003.', NULL, 1),
(633, '2022-01-01', 'Yára Mattos', 'Cultura Brasileira: Aspectos Gerais e Institucionais', '316.72(81)', '351', '96', 'São Paulo', 'UFOP - Universidade Federal de Ouro Preto', NULL, NULL, '2009', '1 ed.', 'v.1', 'ex.2', 'p.96', 1, '30.00', 0, '003.', NULL, 1),
(634, '2022-01-01', 'Adriano Sérgio Lopes da Gama Cerqueira', 'Teoria política', NULL, '351', '978.85.98601.28.1', 'São Paulo', 'UFOP - Universidade Federal de Ouro Preto', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.66', 1, '30.00', 0, '003.', NULL, 1),
(635, '2022-01-01', 'Sebastião C. Velasco e Cruz', 'Contacorrente: ensaios de teoria, análise e crítica política', '32', '320', '978.85.393.0807.1', 'São Paulo', 'UNESP - Universidade Estadual Paulista', NULL, NULL, '2019', '1 ed.', 'v.1', 'ex.1', 'p.266', 1, '30.00', 0, '003.', NULL, 1),
(636, '2022-01-01', 'Pedro Sisnando Leite', 'O grande novo Nordeste de Virgílio Távora', '658.004', '338.9', '978.85.65599.17.7', 'Fortaleza-CE', 'Senado Federal', NULL, NULL, '2017', '1 ed.', 'v.1', 'ex.1', 'p.244', 1, '15.00', 0, '003.', NULL, 1),
(637, '2022-01-01', 'BOCCHI, Olsen Henrique ', 'O terceiro Setor: uma visão estratégica para projetos de interesse público', NULL, '658.048', '978.85.7838.004.5', 'Curitiba-PR', 'IBPEX', NULL, NULL, '2009', '1 ed.', NULL, 'ex.1', 'p.240', 1, '15.00', 0, '003.', NULL, 1),
(638, '2022-01-01', 'Alexandre Gomes', 'Lições da Cidade: questionamentos e desafios do desenvolvimento urbano na cidade de São Paulo', '711.4(816.1)', '711.4098161', '978.85.63.16354.7', 'São Paulo', 'CIA dos livros', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.184', 1, '15.00', 0, '003.', NULL, 1),
(639, '2022-01-01', 'Alexandre Gomes', 'Lições da Cidade: questionamentos e desafios do desenvolvimento urbano na cidade de São Paulo', '711.4(816.1)', '711.4098161', '978.85.63.16354.7', 'São Paulo', 'CIA dos livros', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.2', 'p.184', 1, '15.00', 0, '003.', NULL, 1),
(640, '2022-01-01', 'Ana Beatriz de Castro Carvalho Lacerda', 'A voz do cidadão na constrituinte ', '342.4(81)', '321.8', '978.85.402.0707.3', 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '2018', '1 ed.', 'v.1', 'ex.1', 'p.155', 1, '15.00', 0, '003.', NULL, 1),
(641, '2022-01-01', 'Eduardo Matarazzo Suplicy', 'Renda de cidadania: a saída é pela porta', NULL, '321.8', '978.85.249.2012.7', 'São Paulo', 'Cortez', NULL, NULL, '2013', '1 ed.', 'v.1', 'ex.1', 'p.463', 1, '15.00', 0, '003.', NULL, 1),
(642, '2022-01-01', 'Rildo Cosson', 'Letramento político: a perspectiva do legislativo', '342.532.37.035(81)', '328.81', '978.85.402.0729.5', 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '2019', '1 ed.', 'v.1', 'ex.1', 'p.218', 1, '10.00', 0, '003.', NULL, 1),
(643, '2022-01-01', 'BOMFIM, João Bosco Bezerra', 'Palavras do Presidente: A oratória dos presidentes do Senado: sob o signo de Rui Barbosa', '320.08', '923.2', '85.7238.230.5', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2007', '1 ed.', NULL, 'ex.1', 'p.392', 1, '10.00', 0, '003.', NULL, 1),
(644, '2022-01-01', 'MAIA, Agaciel da Silva', 'Parlamentares do Rio Grande do Norte: Senadores (do Império à República)', NULL, '923.813', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2007', '1 ed.', NULL, 'ex.1', 'p.179', 1, '10.00', 0, '003.', NULL, 1),
(645, '2022-01-01', 'Rachel Meneguello (Org.)', 'O legislativo Brasileiro: funcionamento, composição e opinião pública', NULL, '341.2536', '978.85.7018.396.5', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.177', 1, '10.00', 0, '003.', NULL, 1),
(646, '2022-01-01', 'Rafael Silveira e Silva (Org.)', 'Resgate da reforma política: diversidade e pluralismo no legislativo', NULL, '342.07', '978.85.7018.573.0', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2015', '1 ed.', 'v.1', 'ex.1', 'p.361', 1, '10.00', 0, '003.', NULL, 1),
(647, '2022-01-01', 'José Mario Brasiliense Carneiro e Humberto Dantas (Orgs.)', 'Parceria Social público-privada: textos de referência', NULL, '352', '978.85.89739.03.0', 'São Paulo', 'Oficina Municipal', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.383', 1, '10.00', 0, '003.', NULL, 1),
(648, '2022-01-01', 'Brasil', 'CPI da Pirataria - Relatório', '343.533(81)', '036.9', NULL, 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '2004', '1 ed.', NULL, 'ex.1', 'p.342', 1, '10.00', 0, '003.', NULL, 1),
(649, '2022-01-01', 'Senado Federal', 'Temas e agendas para o desenvolvimento sustentável', NULL, '333.715', '978.85.7018.464.1', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.263', 1, '10.00', 0, '003.', NULL, 1),
(650, '2022-01-01', 'Senado Federal - Comissão de Constituição, Justiça e Cidadania', 'Balanço de atividades de 2015', NULL, '036.9', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2015', '1 ed.', NULL, 'ex.1', 'p.336', 1, '10.00', 0, '003.', NULL, 1),
(651, '2022-01-01', 'Comitê de Gestão de Internet no Brasil', 'TIC Governo Eletrônico 2013', NULL, '004.6', '978.85.60062.91.1', 'São Paulo', '***', NULL, NULL, '2014', '1 ed.', NULL, 'ex.1', 'p.399', 1, '10.00', 0, '003.', NULL, 1),
(652, '2022-01-01', 'Luciana Botelho Pacheco', 'Coleção Prática Legislativa - Perguntas e respostas sobre o Regimento Interno da Câmara de Deputados', '342.532(81)', '320', '978.85.402.0719.6', 'São Paulo', 'Câmara dos Deputados', NULL, NULL, '2019', '5 ed.', 'v.1', 'ex.1', 'p.126', 1, '10.00', 0, '003.', NULL, 1),
(653, '2022-01-01', 'Vitor Marchetti (Org.)', 'Políticas Públicas em Debate', '35.073.1(81)', '323', '978.85.61488.08.6', 'São Bernardo do Campo-SP', 'MP Editora', NULL, NULL, '2013', '1 ed.', 'v.1', 'ex.1', 'p.320', 1, '15.00', 0, '003.', NULL, 1),
(654, '2022-01-01', 'Hely Lopes Meirelles', 'Direito administrativo brasileiro', NULL, '342', '978.85.392.0061.0', 'São Paulo', 'MALHEIROS', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.894', 1, '15.00', 0, '003.', NULL, 1),
(655, '2022-01-01', 'ALMEIDA, Marcelo Cavalcanti ', 'Curso Básico de Contabilidade: Introdução à metodologia da contabilidade e Contabilidade Básica', '978.85.224.6859.1', '657', '657', 'São Paulo', 'Atlas S.A.', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p.369', 1, '15.00', 0, '003.', NULL, 1),
(656, '2022-01-01', 'GIMENES, Cristiano Marchi ', 'Matemática Financeira', NULL, '650.015', '978.8143.536.7', 'São Paulo', 'Pearson Prentice Hall', NULL, NULL, '2009', '1 ed.', NULL, 'ex.1', 'p.298', 1, '15.00', 0, '003.', NULL, 1),
(657, '2022-01-01', 'MUROLO, Afrânio Carlos ', 'Matemática Aplicada a adminstração, economia e contabilidade', NULL, '510.07', '978.85.221.1313.2', 'São Paulo', 'Cengage Learning', NULL, NULL, '2013', '1 ed.', NULL, 'ex.1', 'p.506', 1, '15.00', 0, '003.', NULL, 1),
(658, '2022-01-01', 'BONESSO, Allaymer Ronaldo ', 'Manual de licitação e contrato administrativo', '347.44', '657.833', '978.85.362.3385.7', 'Curitiba-PR', 'Jurua', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.204', 1, '15.00', 0, '003.', NULL, 1),
(659, '2022-01-01', 'Cláudio De Cicco', 'Ciência política e Teoria Geral do Estado', '342.2.32', '320', '978.85.203.3837.7', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.319', 1, '15.00', 0, '003.', NULL, 1),
(660, '2022-01-01', 'GOMES, Eduardo', 'A lei de murphy no gerenciamento de projetos', NULL, '658.404', '987.85.7452.319.4', 'Rio de Janeiro', 'Brasport Editora', NULL, NULL, '2007', '1 ed.', NULL, 'ex.1', 'p.94', 1, '15.00', 0, '003.', NULL, 1),
(661, '2022-01-01', 'MATOS, Francisco Gomes de ', 'Ética na gestão empresarial: da conscientização à ação ', '174.4', '174.4', '978.85.02.10164.7', 'São Paulo', 'Saraiva', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.155', 1, '15.00', 0, '003.', NULL, 1),
(662, '2022-01-01', 'DORNELAS, José Carlos Assis', 'Empreendedorismo: transformando ideias em negócios', '65.017.3', '658.022', '978.85.352.6952.9', 'Rio de Janeiro', 'Elsevier', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.260', 1, '15.00', 0, '003.', NULL, 1),
(663, '2022-01-01', 'CAVALCANTE, Geraldo Ronchetti ', 'Comunicação e comportamento organizacional', '65.012.32', '658.4', '987.85.62725.04.3', 'Porto Alegre', 'ECDEP', NULL, NULL, '2009', '1 ed.', NULL, 'ex.1', 'p.216', 1, '15.00', 0, '003.', NULL, 1),
(664, '2022-01-01', 'Josiane C. Cintra, et. al.', 'Desenvolvimento pessoal e profissional', NULL, '358.3124', '978.85.7969.073.0', 'Valinhos-SP', 'Anhanguera Publicações', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.256', 1, '15.00', 0, '003.', NULL, 1),
(665, '2022-01-01', 'CHIAVENATO, Idalberto ', 'Administração: teoria, processo e prática', NULL, '658', '978.85.2014.3671.4', 'Barueri-SP', 'Manole', NULL, NULL, '2014', '1 ed.', NULL, 'ex.1', 'p.469', 1, '15.00', 0, '003.', NULL, 1),
(666, '2022-01-01', 'Leonardo Secchi', 'Polítiacas públlicas: conceitos, esquemas de análise, casos práticos', NULL, '320', '978.85.221.1640.9', 'São Paulo', 'Cengage Learning', NULL, NULL, '2013', '1 ed.', 'v.1', 'ex.1', 'p.168', 1, '15.00', 0, '003.', NULL, 1),
(667, '2022-01-01', 'FRANCO, Décio Henrique / RODRIGUES,  Edna de Almeida  (Org.)', 'Tecnologia e ferramentas de gestão', NULL, '658.406', '978.85.7516.441.9', 'Campinas -SP', 'Alínea Editora', NULL, NULL, '2013', '1 ed.', NULL, 'ex.1', 'p.361', 1, '15.00', 0, '003.', NULL, 1),
(668, '2022-01-01', 'Reinaldo Moreira Bruno', 'Lei de Responsabilidade fiscal e Orçamento municipal', '35.073.52', '352.4(22)', '978.85.362.3544.8', 'Curitiba-PR', 'Jurua', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.350', 1, '15.00', 0, '003.', NULL, 1),
(669, '2022-01-01', 'Rafael Antonio Baldo', 'Novos horizontes para a gestão pública', '35', '351(22)', '978.85.362.2657.6', 'Curitiba-PR', 'Jurua', NULL, NULL, '2014', '1 ed.', 'v.1', 'ex.1', 'p.184', 1, '15.00', 0, '003.', NULL, 1),
(670, '2022-01-01', 'Anderson Rafael Nascimento, et.al', 'Cidades : identidade e gestão', '657', '307.76', '978.85.02.13174.3', 'São Paulo', 'Saraiva', NULL, NULL, '2009', '1 ed.', 'v.1', 'ex.1', 'p.270', 1, '15.00', 0, '003.', NULL, 1),
(671, '2022-01-01', 'Roberta Camineiro Baggio', 'Federalismo no contexto da nova orgem global', '340.1', '340.1(22)', '85.362.1190.3', 'Curitiba-PR', 'Jurua', NULL, NULL, '2014', '1 ed.', 'v.1', 'ex.1', 'p.204', 1, '15.00', 0, '003.', NULL, 1),
(672, '2022-01-01', 'Fábio Giambiagi', 'Funanças públicas: teoria e prática no Brasil', '336.1(81)', '336.81', '978.85.352.4898.2', 'Rio de janeiro', 'Elsevier', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.498', 1, '15.00', 0, '003.', NULL, 1),
(673, '2022-01-01', 'PEREIRA, Adriana Camargo ', 'Sustentabilidade na prática: fundamentos, experiências e habilidades', NULL, '658.408', '978.85.7969.075.4', 'Valinhos-SP', 'Anhanguera Publicações', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p.224', 1, '15.00', 0, '003.', NULL, 1),
(674, '2022-01-01', 'CARMASSI, Arturo ', 'Journal Perpétuel ou as possibilidades do traço', NULL, '740', NULL, 'Roma- Itália', 'Studio Pagina', NULL, NULL, '1978', '1 ed.', NULL, 'ex.1', 'p.148', 1, '15.00', 0, '006.', NULL, 1),
(675, '2022-01-01', 'VALLOTTON, Felix ', 'Obra Gravada', NULL, '740', NULL, '***', 'GRAFICOS  CHESTERMAN LTDA', NULL, NULL, '1987', '1 ed.', NULL, 'ex.1', 'p.48', 1, '15.00', 0, '006.', NULL, 1),
(676, '2022-01-01', 'MASSARANI, Emanuel von Lauenstein ', 'José Guerra: a vida autônoma das esculturas de Guerra', NULL, '730', NULL, 'São Paulo', 'Impressão Oficial', NULL, NULL, '1983', '1 ed.', NULL, 'ex.2', 'p.24', 1, '15.00', 0, '006.', NULL, 1),
(677, '2022-01-01', 'MASSARANI, Emanuel von Lauenstein ', 'José Guerra: a vida autônoma das esculturas de Guerra', NULL, '730', NULL, 'São Paulo', 'Impressão Oficial', NULL, NULL, '1983', '1 ed.', NULL, 'ex.1', 'p.24', 1, '15.00', 0, '006.', NULL, 1),
(678, '2022-01-01', 'MORENO, Dolly ', 'Esculturas', NULL, '730', NULL, 'São Paulo', 'Cinevisa', NULL, NULL, '1989', '1 ed.', NULL, 'ex.1', 'p.95', 1, '15.00', 0, '006.', NULL, 1),
(679, '2022-01-01', 'Helena Kazue Nakai', 'Coleção Brasil Collection: A arte na defesa da Amazônia e do Pantanal', NULL, '750', NULL, 'São Paulo', '***', NULL, NULL, NULL, NULL, 'v.1', 'ex.1', 'p.32', 1, '20.00', 0, '006.', NULL, 1),
(680, '2022-01-01', 'Helena Kazue Nakai', 'Coleção Brasil Collection: A arte na defesa da Amazônia e do Pantanal', NULL, '750', NULL, 'São Paulo', '***', NULL, NULL, NULL, NULL, 'v.1', 'ex.2', 'p.32', 1, '20.00', 0, '006.', NULL, 1),
(681, '2022-01-01', 'Helena Kazue Nakai', 'Coleção Brasil Collection: A arte na defesa da Amazônia e do Pantanal', NULL, '750', NULL, 'São Paulo', '***', NULL, NULL, NULL, NULL, 'v.1', 'ex.3', 'p.32', 1, '20.00', 0, '006.', NULL, 1),
(682, '2022-01-01', 'VILLAÇA, Evelina ', 'Coleção Brasil Collection: Pinturas, Esculturas e Translucências', NULL, '750', NULL, 'São Paulo', '***', NULL, NULL, '2006', '1 ed.', NULL, 'ex.1', 'p.32', 1, '20.00', 0, '006.', NULL, 1),
(683, '2022-01-01', 'VILLAÇA, Evelina ', 'Coleção Brasil Collection: Pinturas, Esculturas e Translucências', NULL, '750', NULL, 'São Paulo', '***', NULL, NULL, '2006', '1 ed.', NULL, 'ex.2', 'p.32', 1, '20.00', 0, '006.', NULL, 1),
(684, '2022-01-01', 'VILLAÇA, Evelina ', 'Coleção Brasil Collection: Pinturas, Esculturas e Translucências', NULL, '750', NULL, 'São Paulo', '***', NULL, NULL, '2006', '1 ed.', NULL, 'ex.3', 'p.32', 1, '20.00', 0, '006.', NULL, 1),
(685, '2022-01-01', 'GARCIA, Suzana', 'Coleção Brasil Collection: Pinturas, Re-leituras e Re-visões', NULL, '750', NULL, 'São Paulo', '***', NULL, NULL, '2007', '1 ed.', NULL, 'ex.1', 'p.16', 1, '20.00', 0, '006.', NULL, 1),
(686, '2022-01-01', 'GARCIA, Suzana', 'Coleção Brasil Collection: Pinturas, Re-leituras e Re-visões', NULL, '750', NULL, 'São Paulo', '***', NULL, NULL, '2007', '1 ed.', NULL, 'ex.2', 'p.16', 1, '20.00', 0, '006.', NULL, 1),
(687, '2022-01-01', 'GARCIA, Suzana', 'Coleção Brasil Collection: Pinturas, Re-leituras e Re-visões', NULL, '750', NULL, 'São Paulo', '***', NULL, NULL, '2007', '1 ed.', NULL, 'ex.3', 'p.16', 1, '20.00', 0, '006.', NULL, 1),
(688, '2022-01-01', 'ALMENDRA, Elizabeth ', 'Coleção Brasil Collection: Nus femininos, abstratos e madonas', NULL, '750', NULL, 'São Paulo', '***', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.32', 1, '20.00', 0, '006.', NULL, 1),
(689, '2022-01-01', 'ALMENDRA, Elizabeth ', 'Coleção Brasil Collection: Nus femininos, abstratos e madonas', NULL, '750', NULL, 'São Paulo', '***', NULL, NULL, '2012', '1 ed.', NULL, 'ex.2', 'p.32', 1, '20.00', 0, '006.', NULL, 1),
(690, '2022-01-01', 'BUDWEG, Heinz ', 'Coleção Brasil Collection: Encantos do Brasil', NULL, '750', NULL, 'São Paulo', '***', NULL, NULL, '2007', '1 ed.', NULL, 'ex.1', 'p.44', 1, '20.00', 0, '006.', NULL, 1),
(691, '2022-01-01', 'BUDWEG, Heinz ', 'Coleção Brasil Collection: Encantos do Brasil', NULL, '750', NULL, 'São Paulo', '***', NULL, NULL, '2007', '1 ed.', NULL, 'ex.2', 'p.44', 1, '20.00', 0, '006.', NULL, 1),
(692, '2022-01-01', 'BUDWEG, Heinz ', 'Coleção Brasil Collection: Encantos do Brasil', NULL, '750', NULL, 'São Paulo', '***', NULL, NULL, '2007', '1 ed.', NULL, 'ex.3', 'p.44', 1, '20.00', 0, '006.', NULL, 1),
(693, '2022-01-01', 'MASSARANI, Emanuel von Lauenstein ', 'Gustavo Nilson: Surrealismo fantasmagórico', NULL, '740', NULL, 'São Paulo', 'HR Gráfica e Editora', NULL, NULL, NULL, '1 ed.', NULL, 'ex.1', 'p.107', 1, '20.00', 0, '006.', NULL, 1),
(694, '2022-01-01', 'MASSARANI, Emanuel von Lauenstein ', 'Gustavo Nilson: Surrealismo fantasmagórico', NULL, '740', NULL, 'São Paulo', 'HR Gráfica e Editora', NULL, NULL, NULL, '1 ed.', NULL, 'ex.2', 'p.107', 1, '20.00', 0, '006.', NULL, 1),
(695, '2022-01-01', 'MASSARANI, Emanuel von Lauenstein ', 'Gustavo Nilson: Surrealismo fantasmagórico', NULL, '740', NULL, 'São Paulo', 'HR Gráfica e Editora', NULL, NULL, NULL, '1 ed.', NULL, 'ex.3', 'p.107', 1, '20.00', 0, '006.', NULL, 1),
(696, '2022-01-01', 'BUSSAB, Jorge', 'Esculturas', NULL, '730', NULL, '***', '***', NULL, NULL, '2006', '1 ed.', NULL, 'ex.1', 'p.70', 1, '20.00', 0, '006.', NULL, 1),
(697, '2022-01-01', 'BUSSAB, Jorge', 'Esculturas', NULL, '730', NULL, '***', '***', NULL, NULL, '2006', '1 ed.', NULL, 'ex.2', 'p.70', 1, '20.00', 0, '006.', NULL, 1),
(698, '2022-01-01', 'BUSSAB, Jorge', 'Pintura, Escultura, gravura, tapeçaria e cerâmica', NULL, '750', NULL, '***', '***', NULL, NULL, '2007', '1 ed.', NULL, 'ex.1', 'p.156', 1, '20.00', 0, '006.', NULL, 1),
(699, '2022-01-01', 'BUSSAB, Jorge', 'Pintura, Escultura, gravura, tapeçaria e cerâmica', NULL, '750', NULL, '***', '***', NULL, NULL, '2007', '1 ed.', NULL, 'ex.2', 'p.156', 1, '20.00', 0, '006.', NULL, 1),
(700, '2022-01-01', 'PITLIUK, Marcio', 'Bia Doria: raizes do Brasil', NULL, '730.0981', '978.85.62273.03.2', 'São Paulo', 'Pit Cult', NULL, NULL, '2017', '1 ed.', NULL, 'ex.1', 'p.317', 1, '20.00', 0, '006.', NULL, 1),
(701, '2022-01-01', 'Câmara dos Deputados ', 'Interlúdios de Brasília: Aquarelas de Joaquim da Fonseca ', NULL, '750', NULL, 'São Paulo', 'Câmara dos Deputados', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.42', 1, '15.00', 0, '003.', NULL, 1),
(702, '2022-01-01', 'Câmara dos Deputados ', 'O universo de Tony Lima', NULL, '750', NULL, 'São Paulo', 'Câmara dos Deputados', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.29', 1, '15.00', 0, '003.', NULL, 1),
(703, '2022-01-01', 'Câmara dos Deputados ', 'A obra de Miriam Postal', NULL, '750', NULL, 'São Paulo', 'Câmara dos Deputados', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.20', 1, '15.00', 0, '003.', NULL, 1),
(704, '2022-01-01', 'BRASIL, Biblioteca do Exército Brasileiro', 'O Exército na História do Brasil', NULL, '981', '85.7011.209.2', 'Salvador - BA', '***', NULL, NULL, '1998', '1 ed.', NULL, 'ex.1', 'p.260', 1, '15.00', 0, '003.', NULL, 1),
(705, '2022-01-01', 'Senado Federal', 'Vida: uma poesia ao cerrado', NULL, '770', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2012', NULL, 'v.1', 'ex.1', NULL, 1, '15.00', 0, '003.', NULL, 1),
(706, '2022-01-01', 'GAMA, Mauricio Loureiro Gama', 'Galileo Emendabili', NULL, '730', NULL, 'São Paulo', '***', NULL, NULL, '1987', '1 ed.', NULL, 'ex.1', '111', 1, '15.00', 0, '003.', NULL, 1),
(707, '2022-01-01', 'BARRETO, Patrícia Salvação  (Org.)', 'Laboratório do Mundo: idéias e saberes do século XVIII', NULL, '708', NULL, 'São Paulo', 'Impressão Oficial', NULL, NULL, '2004', '1 ed.', NULL, 'ex.1', 'p.279', 1, '15.00', 0, '003.', NULL, 1),
(708, '2022-01-01', 'Assembleia Legislativa de SP', 'Os nikkeis na Assembleia de São Paulo', '929.32(815.6)', '921', NULL, 'São Paulo', 'Impressão Oficial', NULL, NULL, '2008', '1 ed.', NULL, 'ex.1', 'p.208', 1, '15.00', 0, '003.', NULL, 1),
(709, '2022-01-01', 'IRAJUBÁ, Câmara de (MG)', 'Memorial da Câmara Municipal de Itajubá', NULL, '921', NULL, 'Itajubá - MG', '***', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.145', 1, '15.00', 0, '003.', NULL, 1),
(710, '2022-01-01', 'Biennale Internazionale d\'arte contempotanea di Firenze', 'Florence Biennale: Art and the polis', NULL, '708', '8,78887E+12', '***', 'X EDIZIONE', NULL, NULL, '2015', '1 ed.', NULL, 'ex.1', 'p.421', 1, '15.00', 0, '006.', NULL, 1),
(711, '2022-01-01', 'Secretaria de Cultura de São Paulo', 'Pintores Italianos no Brasil', NULL, '750', NULL, 'São Paulo', 'Sociarte', NULL, NULL, '1982', '1 ed.', NULL, 'ex.1', NULL, 1, '15.00', 0, '006.', NULL, 1),
(712, '2022-01-01', 'AMADO, David J.F. do Vale(Coord.)', 'Terra à vista: 500 anos depois - Desenhos de Madalena Olivastro', NULL, '750', NULL, 'São Paulo', '***', NULL, NULL, '1999', '1 ed.', NULL, 'ex.1', 'p.48', 1, '15.00', 0, '006.', NULL, 1),
(713, '2022-01-01', 'WALDBERG, Patrick ', 'Carmassi', NULL, '750', NULL, '***', '***', NULL, NULL, NULL, '1 ed.', NULL, 'ex.1', 'p.210', 1, '15.00', 0, '006.', NULL, 1),
(714, '2022-01-01', 'MASSARANI, Emanuel von Lauenstein ', 'Alessandro Guisberti', NULL, '750', NULL, 'São Paulo', 'Impressão Oficial', NULL, NULL, NULL, '1 ed.', NULL, 'ex.3', 'p.123', 1, '15.00', 0, '006.', NULL, 1),
(715, '2022-01-01', 'Associação Paulista de críticos de arte', 'Os melhores de São Paulo 1956-1982', NULL, '750', NULL, 'São Paulo', 'Impressão Oficial', NULL, NULL, NULL, '1 ed.', NULL, 'ex.1', NULL, 1, '15.00', 0, '006.', NULL, 1),
(716, '2022-01-01', 'MASSARANI, Emanue von Lauenstein  (curador)', 'A opulência do Simples - Aquarelas de Maruska Cara', NULL, '750', NULL, 'São Paulo', 'Impressão Oficial', NULL, NULL, '2006', '1 ed.', NULL, 'ex.1', 'p.50', 1, '15.00', 0, '006.', NULL, 1),
(717, '2022-01-01', 'Associação Arte Contemporânea  Ítalo Brasileira', '15 artistas italianos contemporâneos no Brasil', NULL, '750', NULL, 'São Paulo', 'Impressão Oficial', NULL, NULL, NULL, '1 ed.', NULL, 'ex.1', 'p.89', 1, '15.00', 0, '006.', NULL, 1),
(718, '2022-01-01', 'MASSARANI, Emanuel von Lauenstein ', 'A arte milenar de Getsusen Kobayashi', NULL, '750', '978.85.61457.09.9', 'São Paulo', 'Impressão Oficial', NULL, NULL, NULL, '1 ed.', NULL, 'ex.1', 'p.103', 1, '15.00', 0, '006.', NULL, 1),
(719, '2022-01-01', 'MASSARANI, Emanuel von Lauenstein ', 'A arte milenar de Getsusen Kobayashi', NULL, '750', '978.85.61457.09.9', 'São Paulo', 'Impressão Oficial', NULL, NULL, NULL, '1 ed.', NULL, 'ex.1', 'p.103', 1, '15.00', 0, '006.', NULL, 1),
(720, '2022-01-01', 'Câmara Municipal de SP', 'Ciclo de debates pensando São Paulo', '35', '320', '987.85.66432.00.8', 'São Paulo', 'Impressão Oficial', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.244', 1, '15.00', 0, '003.', NULL, 1),
(721, '2022-01-01', 'Câmara Muncipal de Itapevi', 'Lei Orgânica Muncipal - Editada em 2014', NULL, '348', NULL, 'Itapevi-SP', 'Câmara de Itapevi', NULL, NULL, '2014', '1 ed.', 'v.1', 'ex.1', 'p.50', 3, NULL, 1, '**', NULL, 1),
(722, '2022-01-01', 'Câmara Muncipal de Itapevi', 'Lei Orgânica Muncipal - Editada em 2014', NULL, '348', NULL, 'Itapevi-SP', 'Câmara de Itapevi', NULL, NULL, '2014', '1 ed.', 'v.1', 'ex.2', 'p.50', 3, NULL, 1, '**', NULL, 1),
(723, '2022-01-01', 'Câmara Muncipal de Itapevi', 'Lei Orgânica Muncipal - Editada em 2014', NULL, '348', NULL, 'Itapevi-SP', 'Câmara de Itapevi', NULL, NULL, '2014', '1 ed.', 'v.1', 'ex.3', 'p.50', 3, NULL, 1, '**', NULL, 1),
(724, '2022-01-01', 'Câmara Muncipal de Itapevi', 'Lei Orgânica Muncipal - Editada em 2014', NULL, '348', NULL, 'Itapevi-SP', 'Câmara de Itapevi', NULL, NULL, '2014', '1 ed.', 'v.1', 'ex.4', 'p.50', 3, NULL, 1, '**', NULL, 1),
(725, '2022-01-01', 'Câmara Muncipal de Itapevi', 'Lei Orgânica Muncipal - Editada em 2014', NULL, '348', NULL, 'Itapevi-SP', 'Câmara de Itapevi', NULL, NULL, '2014', '1 ed.', 'v.1', 'ex.5', 'p.50', 3, NULL, 1, '**', NULL, 1),
(726, '2022-01-01', 'Câmara Muncipal de Itapevi', 'Regimento Interno de Itapevi - Editada em 2014', NULL, '348', NULL, 'Itapevi-SP', 'Câmara de Itapevi', NULL, NULL, '2014', '1 ed.', 'v.1', 'ex.1', 'p.92', 3, NULL, 1, '**', NULL, 1),
(727, '2022-01-01', 'Câmara Muncipal de Itapevi', 'Regimento Interno de Itapevi - Editada em 2014', NULL, '348', NULL, 'Itapevi-SP', 'Câmara de Itapevi', NULL, NULL, '2014', '1 ed.', 'v.1', 'ex.2', 'p.92', 3, NULL, 1, '**', NULL, 1),
(728, '2022-01-01', 'LEITE, Angela', 'Aplicações da matemática', NULL, '515', '978.85.221.0711.7', 'São Paulo', 'Cengage Learning', NULL, NULL, '2008', '1 ed.', NULL, 'ex.1', 'p.498', 1, '15.00', 0, '003.', NULL, 1),
(729, '2022-01-01', 'GUIMARÃES, Angelo de Moura ', 'Algorítimos e Estruturas de dados', '681.3.06', '001.642', '85.216.0378.9', 'Rio de janeiro', 'LTC Editora', NULL, NULL, '1985', '1 ed.', NULL, 'ex.1', 'p.216', 1, '15.00', 0, '003.', NULL, 1),
(730, '2022-01-01', 'Vitor Marchetti (Org.)', 'Políticas Públicas em Debate', '35.073.1(81)', '320', '978.85.61488.08.6', 'São Bernardo do Campo-SP', 'MP Editora', NULL, NULL, '2013', '1 ed.', 'v.1', 'ex.2', 'p.320', 1, '15.00', 0, '003.', NULL, 1),
(731, '2022-01-01', 'SILVA, Washinton Luís Vieira da ', 'Contabilidade', '657', '657', NULL, 'Ouro Preto-MG', '***', NULL, NULL, '2009', '1 ed.', NULL, 'ex.1', 'p.54', 1, '15.00', 0, '003.', NULL, 1),
(732, '2022-01-01', 'Dante Marcello Claramente Gallian', 'Vida, Trabalho, memória: a história da Academia Nacional d Direito do trabalho nas histórias de vida de seus fundadores', '351.83', '350', '978.85.85275.28.0', 'Porto Alegre', 'Lex Magister', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.267', 1, '15.00', 0, '003.', NULL, 1),
(733, '2022-01-01', 'Academia Paulista de Letras', 'Academia Paulista de Letras: Cadeira 10 - Paulo Setubal', NULL, '370.1', NULL, 'São Paulo', '***', NULL, NULL, NULL, '1 ed.', 'v.1', 'ex.1', 'p.18', 1, '15.00', 0, '003.', NULL, 1),
(734, '2022-01-01', 'GUGLIELMO, Giselda Penteado Di ', 'A mulher e o espelho', NULL, 'B869.915', '978.85.912609.0.4', 'São Paulo', '***', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p.64', 1, '15.00', 0, '003.', NULL, 1),
(735, '2022-01-01', 'TINOCO, Marcelo Tinoco', 'Era uma vez...', NULL, '750', NULL, 'EUA', 'Zipper', NULL, NULL, '2014', '1 ed.', NULL, 'ex.1', 'p.24', 1, '15.00', 0, '003.', NULL, 1),
(736, '2022-01-01', 'Bonifácio de Andrada', 'Ciência Política e seus aspectos atuais', NULL, '320', NULL, 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '2000', '1 ed.', 'v.1', 'ex.1', 'p.195', 1, '15.00', 0, '003.', NULL, 1),
(737, '2022-01-01', 'Bonifácio de Andrada', 'O centenário do líder José Bonifácio Lafayette de Andrada e as homenagens póstumas da Câmara de Deputados', NULL, '923.2', NULL, 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '2013', '1 ed.', 'v.1', 'ex.1', 'p.55', 1, '15.00', 0, '003.', NULL, 1),
(738, '2022-01-01', 'Kken Starkey', 'Como as organizações aprendem: relatos do sucesso das grandes empresas', NULL, '302.35', '85.86082.25.2', 'São Paulo', 'Futura Editora', NULL, NULL, '1997', '1 ed.', 'v.1', 'ex.1', 'p.484', 1, '15.00', 0, '003.', NULL, 1),
(739, '2022-01-01', 'Brasil, Tesouro Nacional', 'Manual de Desmonstrativos fiscais: aplicado à União e aos Estados, Distrito Federal e Municípios ', '336.1/5(81)', '336.81', '978.85.87841.53.7', 'Brasília-DF', '***', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.698', 1, '15.00', 0, '003.', NULL, 1),
(740, '2022-01-01', 'Bonifácio de Andrada', 'Paralamentarismo e Realidade Nacional', NULL, '320', NULL, 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '1997', '1 ed.', 'v.1', 'ex.1', 'p.300', 1, '15.00', 0, '003.', NULL, 1),
(741, '2022-01-01', 'Rennan Mafra', 'Entre o espetáculo, a festa e a argumetação - mídia, comunicação estratégica e mobilidade social', '316.444', '360', '85.7526.206.8', 'Belo Horizonte', 'Autêntica Editora', NULL, NULL, '2006', '1 ed.', 'v.1', 'ex.1', 'p.192', 1, '15.00', 0, '003.', NULL, 1),
(742, '2022-01-01', 'RODRIGUES, Martius Vicente Rodrigues Y s (Org.)', 'O valor da inovação', '65.016.1', '658.452', '85.352.1617.0', 'Rio de janeiro', 'Elsevier', NULL, NULL, '2005', '1 ed.', NULL, 'ex.1', 'p.145', 1, '15.00', 0, '003.', NULL, 1),
(743, '2022-01-01', 'Stella Bardavid', 'Serviço social de caso', NULL, '361.3', NULL, 'São Paulo', '***', NULL, NULL, '1978', '1 ed.', 'v.1', 'ex.1', 'p.121', 1, '15.00', 0, '003.', NULL, 1),
(744, '2022-01-01', 'Moniz Bandeira', 'Estado nacional e política internacional na América Latina: o continente nas relaçõe Argentina-Brasil', NULL, '327.81082', '85.85669.17.9', 'São Paulo', 'Ensaio Editora', NULL, NULL, '1995', '1 ed.', 'v.1', 'ex.1', 'p.333', 1, '15.00', 0, '003.', NULL, 1),
(745, '2022-01-01', 'Nélson Werneck Sodré', 'Contribuição à história do PCB', NULL, '329.981', NULL, 'São Paulo', 'Global Editora', NULL, NULL, '1984', '1 ed.', 'v.1', 'ex.1', 'p.119', 1, '15.00', 0, '003.', NULL, 1),
(746, '2022-01-01', 'VIETEZ, Cândido Giraldez ', 'A empresa sem patrão', NULL, '658.452', NULL, 'São Paulo', 'UNESP - Universidade Estadual Paulista', NULL, NULL, '1997', '1 ed.', NULL, 'ex.1', 'p.140', 1, '15.00', 0, '003.', NULL, 1),
(747, '2022-01-01', 'William Josiah Goode', 'Métodos em pesquisa social', NULL, '300.72', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1977', '1 ed.', 'v.1', 'ex.1', 'p.488', 1, '15.00', 0, '003.', NULL, 1),
(748, '2022-01-01', 'Ives Gandra da Silva Martins', 'Asperctos constitucionais do Plano Brasil Novo', '342(81)', NULL, '85.218.0067.3', 'Rio de janeiro', 'Forense Editora', NULL, NULL, '1991', '1 ed.', 'v.1', 'ex.1', 'p.194', 1, '15.00', 0, '003.', NULL, 1),
(749, '2022-01-01', 'José Carlos de Oliveira', 'Concessões e permissões de serviços públicos', NULL, '352.16', '85.7283.156.8', 'São Paulo', 'Edipro', NULL, NULL, '1997', '1 ed.', 'v.1', 'ex.1', 'p.192', 1, '15.00', 0, '003.', NULL, 1),
(750, '2022-01-01', 'Anna Cecilia de Morares Bianchi', 'Manual de orientação: estágio supervidionado', NULL, '370.733', '85.221.0387.9', 'São Paulo', 'Thompson Learning', NULL, NULL, '2003', '1 ed.', 'v.1', 'ex.1', 'p.98', 1, '15.00', 0, '003.', NULL, 1),
(751, '2022-01-01', 'MARX, Roberto ', 'Trabalho em grupos e autonomia como instrumentos de competição: experiências internacional, casos brasileiros, metodologia de implantação', NULL, '658.402', '85.224.1826.8', 'São Paulo', 'Atlas S.A.', NULL, NULL, '1997', '1 ed.', NULL, 'ex.1', 'p.165', 1, '15.00', 0, '003.', NULL, 1),
(752, '2022-01-01', 'VALLE, Maria Ribeiro do', 'Tenho Algo a dizer: memórias da Unesp na ditadura civil-militar (1964-1985)', NULL, '923', '978.85.7983.623.7', 'São Paulo', 'Cultura Acadêmica', NULL, NULL, '2014', '1 ed.', NULL, 'ex.1', 'p.240', 1, '15.00', 0, '003.', NULL, 1),
(753, '2022-01-01', 'Andréa Cristina Oliveira Gozetto e Rodrigo Navarro', 'MBA em Relações governamentais da FGV: Trabalhos Acadêmicos Selecionados', NULL, '370', '978.85.8150.593.0', 'São Paulo', 'Life Editora', NULL, NULL, '2019', '1 ed.', 'v.1', 'ex.1', 'p.504', 1, '15.00', 0, '003.', NULL, 1),
(754, '2022-01-01', 'Alexis Charles Henri Maurice Clérel de Tocqueville', 'Democracia na América', NULL, '321.8', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1969', '1 ed.', 'v.1', 'ex.1', 'p.364', 1, '15.00', 0, '003.', NULL, 1),
(755, '2022-01-01', 'Reginaldo Carmello Correia de Moraes', 'Globalização e radicalismo agrário: globalização e políticas públicas', '316.485.22', '306.3', '85.7139.724.4', 'São Paulo', 'UNESP - Universidade Estadual Paulista', NULL, NULL, '2006', '1 ed.', 'v.1', 'ex.1', 'p.115', 1, '15.00', 0, '003.', NULL, 1),
(756, '2022-01-01', 'Maria Helena de Almeida Lima', 'Serviço Social e sociedade brasileira', NULL, '361.98', NULL, 'São Paulo', 'Cortez', NULL, NULL, '1982', '1 ed.', 'v.1', 'ex.1', 'p.141', 1, '15.00', 0, '003.', NULL, 1),
(757, '2022-01-01', 'Arnaldo Malheiros', 'Manual de Eleições Municipais', NULL, '320', NULL, 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '1963', '1 ed.', 'v.1', 'ex.1', 'p.180', 1, '15.00', 0, '003.', NULL, 1),
(758, '2022-01-01', 'STOCKTON, R. Stansbury ', 'Sistemas Básicos de Controle de estoque (conceitos e análises)', NULL, '658.7', NULL, 'São Paulo', 'Atlas S.A.', NULL, NULL, '1980', '1 ed.', NULL, 'ex.1', 'p.138', 1, '15.00', 0, '003.', NULL, 1),
(759, '2022-01-01', 'SCHLUNZEN JUNIOR, Klaus ', 'Aprendiagem, cultura e tecnologia', NULL, '658', '85.7139.463.6', 'São Paulo', 'UNESP - Universidade Estadual Paulista', NULL, NULL, '2003', '1 ed.', NULL, 'ex.1', 'p.161', 1, '15.00', 0, '003.', NULL, 1),
(760, '2022-01-01', 'Bonifácio de Andrada', 'A Educação e as contituintes latino-americanas', NULL, '379', NULL, 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '2005', '1 ed.', 'v.1', 'ex.1', 'p.404', 1, '15.00', 0, '003.', NULL, 1),
(761, '2022-01-01', 'Karen Armenovitch Katchaturov', 'A expansão ideológica dos EUA na América Latina', '327.2', '327.73', NULL, 'Rio de janeiro', 'Civilização Brasileira', NULL, NULL, '1980', '1 ed.', 'v.1', 'ex.1', 'p.273', 1, '15.00', 0, '003.', NULL, 1),
(762, '2022-01-01', 'T.R.Bottomore', 'Críticos da Sociedade: O pensamento adical na América do Norte', NULL, '320', NULL, 'Rio de janeiro', 'Zahar Editora', NULL, NULL, '1970', '1 ed.', 'v.1', 'ex.1', 'p.127', 1, '15.00', 0, '003.', NULL, 1),
(763, '2022-01-01', 'SILVA, José Luiz Werneck da', 'A reformação da história ou para não esquecer', NULL, '981.06', '85.85061.31.6', 'Rio de janeiro', 'Zahar Editora', NULL, NULL, '1985', '1 ed.', NULL, 'ex.1', 'p.96', 1, '15.00', 0, '003.', NULL, 1),
(764, '2022-01-01', 'Adhemar Gabriel Bahadian', 'A tentativa de controle do poder econômico nas nações unidas: estudo do conjunto de regras e princípios para o controle das apráticas comerciais restritivas', '382.4', '327', NULL, 'Brasília-DF', 'IPRI', NULL, NULL, '1992', '1 ed.', 'v.1', 'ex.1', 'p.160', 1, '15.00', 0, '003.', NULL, 1),
(765, '2022-01-01', 'Annita Valléria Calmon Mendes', 'Ética na administração pública federal: a implementação de comissões de ética setoriais: entre o desafio e a oportunidade de mudar o modelo de gestão', '174.35.08(81)', '378.103', '978.85.7631.231.4', 'Brasília-DF', 'FUNAG/CHDD', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.124', 1, '15.00', 0, '003.', NULL, 1),
(766, '2022-01-01', 'Bonifácio de Andrada', 'A crise dos partidos políticos, do sistema eleitoral e a militância política', NULL, '341.28', NULL, 'Barbacena', '***', NULL, NULL, '2007', '1 ed.', 'v.1', 'ex.1', 'p.162', 1, '15.00', 0, '003.', NULL, 1),
(767, '2022-01-01', 'Bonifácio de Andrada', 'Elementos da Ciência Política', NULL, '320', NULL, 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '2003', '1 ed.', 'v.1', 'ex.1', 'p.180', 1, '15.00', 0, '003.', NULL, 1),
(768, '2022-01-01', 'COUTINHO, Afrânio ', 'Conceito de Literatura Brasileira', NULL, '808.07', '85.00.32254.5', '***', '***', NULL, NULL, NULL, '1 ed.', NULL, 'ex.1', 'p.176', 1, '15.00', 0, '003.', NULL, 1),
(769, '2022-01-01', 'Wgner Pralon Mancuso', 'Lobby e políticas públicas', NULL, '324.4', '978.85.225.2063.3', 'Rio de janeiro', 'FGV Editora', NULL, NULL, '2018', '1 ed.', 'v.1', 'ex.1', 'p.140', 1, '15.00', 0, '003.', NULL, 1),
(770, '2022-01-01', 'Brasil, Tesouro Nacional', 'Manual de Contabilidade aplicada ao setor público: aplicado à União e aos Estados, Distrito Federal e Municípios', '336.121.8(81)', '657.61', '978.85.87841.54.4', 'Brasília-DF', 'Secretarias Federais', NULL, NULL, '2013', '1 ed.', NULL, 'ex.1', 'p.795', 1, '15.00', 0, '003.', NULL, 1),
(771, '2022-01-01', 'PAIVA, Manoel ', 'Matemática - Ensino Médio - Volume Único', NULL, '510.7', '85.16.04674.5', 'São Paulo', 'Moderna Editora', NULL, NULL, '2005', '1 ed.', NULL, 'ex.1', 'p.578', 1, '15.00', 0, '003.', NULL, 1),
(772, '2022-01-01', 'SAMAPAIO, José Luiz ', 'Universo da física: Mecânica - Volume 1', NULL, '531.07', '978.85.357.0590.4', 'São Paulo', 'Atual', NULL, NULL, '2005', '1 ed.', 'v.1', 'ex.1', 'p.456', 1, '15.00', 0, '003.', NULL, 1),
(773, '2022-01-01', 'SAMAPAIO, José Luiz ', 'Universo da física: Hidrostática, termologia e óptica - Volume 2', NULL, '531.07', '978.85.357.0590.4', 'São Paulo', 'Atual', NULL, NULL, '2005', '1 ed.', 'v.2', 'ex.1', 'p.520', 1, '15.00', 0, '003.', NULL, 1),
(774, '2022-01-01', 'SAMAPAIO, José Luiz ', 'Universo da física: Ondulatória, Eletromagnetismo e Física Moderna - Volume 3', NULL, '531.07', '978.85.357.0590.4', 'São Paulo', 'Atual', NULL, NULL, '2005', '1 ed.', 'v.3', 'ex.1', 'p.495', 1, '15.00', 0, '003.', NULL, 1),
(775, '2022-01-01', 'KOHAMA, Heilio ', 'Contabilidade pública: teoria e prática', NULL, '657.61', '978.85.224.5835.6', 'São Paulo', 'Atlas S.A.', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p.347', 1, '15.00', 0, '003.', NULL, 1),
(776, '2022-01-01', 'James Giacomoni', 'Orçamento Público', NULL, '350.722', '978.85.224.6967.3', 'São Paulo', 'Atlas S.A.', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.374', 1, '20.00', 0, '003.', NULL, 1),
(777, '2022-01-01', 'John F.Kennedy', 'Política e Coragem', NULL, '320', NULL, 'Belo Horizonte', 'Difusão Pan americana', NULL, NULL, '1962', NULL, 'v.1', 'ex.1', 'p.284', 1, '10.00', 0, '003.', NULL, 1),
(778, '2022-01-01', 'Carlos Kozel', 'Pela senda da saúde', NULL, '362.1', NULL, 'São Paulo', 'Linográfica Editora Ltda', NULL, NULL, NULL, NULL, 'v.1', 'ex.1', 'p.311', 1, '10.00', 0, '003.', NULL, 1),
(779, '2022-01-01', 'Subsecretaria de Edições técnicas do Senado Federal', 'Perspectiva Senado: Proposta de Sistema Tributário', NULL, '336', '978.85.7018.259.3', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2010', NULL, 'v.1', 'ex.1', 'p.203', 1, '10.00', 0, '003.', NULL, 1),
(780, '2022-01-01', 'David Miliband(Coord.)', 'Reiventando a Esquerda', NULL, '320.51.3094', '85.7139.151.3', 'São Paulo', 'UNESP - Universidade Estadual Paulista', NULL, NULL, '1997', '1 ed.', NULL, 'ex.1', 'p.314', 1, '10.00', 0, '003.', NULL, 1),
(781, '2022-01-01', 'SILVA, Sergio Milliet da Costa e', 'Diário Crítico de Sergio Milliet da Costa e Silva - Volume 2', NULL, 'B869.93', NULL, 'São Paulo', 'Brasilienses', NULL, NULL, '1945', '1 ed.', 'V.2', 'ex.1', 'p.334', 1, '10.00', 0, '003.', NULL, 1),
(782, '2022-01-01', 'PAULA, Nelson de', 'Collage: Um testemunho fenomenológico', NULL, 'B869', NULL, '***', '***', NULL, NULL, NULL, '1 ed.', NULL, 'ex.1', 'p.83', 1, '10.00', 0, '003.', NULL, 1),
(783, '2022-01-01', 'VALLADARES, Clarival do Prado ', 'Paisagem Rediviva', NULL, '750', NULL, 'Bahia', '***', NULL, NULL, '1962', '1 ed.', NULL, 'ex.1', 'p.243', 1, '10.00', 0, '003.', NULL, 1),
(784, '2022-01-01', 'Arnaldo Niskier', 'Educação para o trabalho: O homem é a meta - III', '37', '351', NULL, 'Rio de janeiro', 'Secretarias ', NULL, NULL, '1982', '1 ed.', NULL, 'ex.1', 'p.280', 1, '10.00', 0, '003.', NULL, 1),
(785, '2022-01-01', 'BASSETTI, José Sabino ', 'Lampiao - o cangaço e seus segredos', '920.71', '921', '978.85.63448.53.8', 'Salto-SP', '***', 'n.6', NULL, '2015', '1 ed.', NULL, 'ex.1', 'p.160', 1, '10.00', 0, '003.', NULL, 1),
(786, '2022-01-01', 'ANTÔNIO, João ', 'Casa dos Loucos', '869.0(81)8', 'B869.93', '85.325.0467.1', 'Rio de janeiro', 'Rocco Ltda', NULL, NULL, '1994', '1 ed.', NULL, 'ex.1', 'p.151', 1, '10.00', 0, '003.', NULL, 1),
(787, '2022-01-01', 'BARRETO, Lima Barreto', 'Coleção Grandes mestres da literatura brasileira: Recordações do Escrivão Isaías Caminha - Volume 6', NULL, '928', NULL, 'Rio de janeiro', 'Escala Editora', NULL, NULL, NULL, '1 ed.', NULL, 'ex.1', 'p.146', 1, '10.00', 0, '003.', NULL, 1),
(788, '2022-01-01', 'Vários autores', 'Vox populi, vox ... Wagen', NULL, 'B869.93', NULL, '***', '***', NULL, NULL, '1970', '1 ed.', NULL, 'ex.1', NULL, 1, '10.00', 0, '003.', NULL, 1),
(789, '2022-01-01', 'MACHADO FILHO, Aires da Mata', 'Nova ortografia (Atualização de 1971)', NULL, '415', NULL, 'Minas Gerais', 'VEGA S.A', NULL, NULL, '1972', '1 ed.', NULL, 'ex.1', 'p.139', 1, '10.00', 0, '003.', NULL, 1),
(790, '2022-01-01', 'Bonifácio de Andrada', 'A universalidade e o ensino superior: observaçõess sobre o ensino universitário', NULL, '323', NULL, 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '2007', '1 ed.', NULL, 'ex.1', 'p.114', 1, '10.00', 0, '003.', NULL, 1),
(791, '2022-01-01', 'GRISHAM, John ', 'O último jurado', '821.111(73).3', '813', '85.325.1811.7', 'Rio de janeiro', 'Rocco Ltda', NULL, NULL, '2004', '1 ed.', NULL, 'ex.1', 'p.391', 1, '10.00', 0, '003.', NULL, 1),
(792, '2022-01-01', 'ALVES, Sílvia ', 'Sol de outono', NULL, 'B869.915', NULL, 'São Paulo', 'Milesi', NULL, NULL, '1981', '1 ed.', NULL, 'ex.1', 'p.121', 1, '10.00', 0, '003.', NULL, 1),
(793, '2022-01-01', 'FRANCE, Anatole ', 'À sombra do olmo', '821.133', '843', '978.85.7799.110.5', 'Rio de janeiro', 'BestBolso', NULL, NULL, '2009', '1 ed.', NULL, 'ex.1', 'p.166', 1, '10.00', 0, '003.', NULL, 1),
(794, '2022-01-01', 'BRADLEY, Marion Zimmer ', 'As Brumas de Avalon : Livro 1 A Senhora da Magia', NULL, '813.54', NULL, 'Rio de janeiro', 'Imago ', NULL, NULL, '1985', '1 ed.', 'v.1', 'ex.1', 'p.280', 1, '10.00', 0, '003.', NULL, 1),
(795, '2022-01-01', 'BRADLEY, Marion Zimmer ', 'As Brumas de Avalon : Livro 2 A grande rainha ', NULL, '813.54', NULL, 'Rio de janeiro', 'Imago ', NULL, NULL, '1985', '1 ed.', 'v.2', 'ex.1', 'p.267', 1, '10.00', 0, '003.', NULL, 1),
(796, '2022-01-01', 'BRADLEY, Marion Zimmer ', 'As Brumas de Avalon : Livro 3 O Gamo-Rei', NULL, '813.54', NULL, 'Rio de janeiro', 'Imago ', NULL, NULL, '1985', '1 ed.', 'v.3', 'ex.1', 'p.246', 1, '10.00', 0, '003.', NULL, 1),
(797, '2022-01-01', '***', 'Edições Ouro: Como escrever cartas em inglês (The complete Letter Writer)', NULL, '810', NULL, 'Rio de janeiro', 'Edições Ouro', NULL, NULL, NULL, '1 ed.', 'v.288', 'ex.1', 'p.190', 1, '10.00', 0, '003.', NULL, 1),
(798, '2022-01-01', 'FIGUEIREDO, J. Carlos ', 'Edições Ouro: Modelos de cartas comerciais', NULL, '469.046.9', NULL, 'Rio de janeiro', 'Edições Ouro', NULL, NULL, NULL, '1 ed.', 'v.152', 'ex.1', 'p.136', 1, '10.00', 0, '003.', NULL, 1),
(799, '2022-01-01', 'MONTEZUMA, Helena ', 'Edições Ouro: Técnicas de Redação comercial', NULL, '469.046.9', NULL, 'Rio de janeiro', 'Edições Ouro', NULL, NULL, NULL, '1 ed.', 'v.17', 'ex.1', 'p.135', 1, '10.00', 0, '003.', NULL, 1),
(800, '2022-01-01', 'LESSA, Orígenes', 'Napoleão em parada de Lucas', NULL, '028.5', NULL, 'Rio de janeiro', 'Edições Ouro', NULL, NULL, '1970', '1 ed.', 'v.1', 'ex.1', 'p.136', 1, '10.00', 0, '003.', NULL, 1),
(801, '2022-01-01', 'MESSIAS, Adriano ', 'História mal - assombradas de Portugual e Espanha', NULL, '028.5', '978.85.7848.053.0', 'São Paulo', 'Bituta', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p.188', 1, '10.00', 0, '003.', NULL, 1),
(802, '2022-01-01', 'MORAVIA, Alberto ', 'Histórias da Pré-história', NULL, '028.5', '978.85.7326.286.5', 'São Paulo', 'Editora 34', NULL, NULL, '2009', '1 ed.', 'v.1', 'ex.1', 'p.240', 1, '10.00', 0, '003.', NULL, 1),
(803, '2022-01-01', 'FOLLETT, Ken ', 'Uma fortuna perigosa', '821.111.3', '823', '978.85.7799.302.4', 'Rio de janeiro', 'BestBolso', NULL, NULL, '2015', '3 ed.', NULL, 'ex.1', 'p.558', 1, '10.00', 0, '003.', NULL, 1),
(804, '2022-01-01', 'ADES, Dawn', 'O dada e o surrealismo com 62 ilustrações a cor', NULL, '750', NULL, '***', 'Labor Editorial S.A.', NULL, NULL, '1976', '1 ed.', NULL, 'ex.1', 'p.128', 1, '10.00', 0, '003.', NULL, 1),
(805, '2022-01-01', 'CHRISTIE, Agatha ', 'Aventura em Bagdá', NULL, '813', NULL, 'Rio de janeiro', 'Nova Fronteira S.A.', NULL, NULL, NULL, '7 ed.', NULL, 'ex.1', 'p.236', 1, '10.00', 0, '003.', NULL, 1),
(806, '2022-01-01', 'CHRISTIE, Agatha ', 'Assassinato no expresso do oriente', NULL, '813', NULL, 'Rio de janeiro', 'Nova Fronteira S.A.', NULL, NULL, '1933', '9 ed.', NULL, 'ex.1', 'p.189', 1, '10.00', 0, '003.', NULL, 1),
(807, '2022-01-01', 'CHRISTIE, Agatha ', 'Os primeiros casos de poirot', NULL, '813', NULL, 'Rio de janeiro', 'Nova Fronteira S.A.', NULL, NULL, '1974', '4 ed.', NULL, 'ex.1', 'p.241', 1, '10.00', 0, '003.', NULL, 1),
(808, '2022-01-01', 'CHRISTIE, Agatha ', 'O assassinato deRoger Ackroyd', NULL, '823', '978.85.250.4843.1', 'São Paulo', 'Globo', NULL, NULL, '2010', '2 ed.', NULL, 'ex.1', 'p.306', 1, '10.00', 0, '003.', NULL, 1),
(809, '2022-01-01', 'GRISHAM, John', 'A casa pintada', '820(73).3', '813', '85.325.1304.2', 'Rio de janeiro', 'Rocco Ltda', NULL, NULL, '2001', '1 ed.', NULL, 'ex.1', 'p.394', 1, '10.00', 0, '003.', NULL, 1),
(810, '2022-01-01', 'LUNARDELLI, Vera ', 'Folhas de Outono', NULL, 'B869.93', '85.86234.69.9', 'São Paulo', 'Clio Editora', NULL, NULL, '2005', '1 ed.', NULL, 'ex.1', 'p.157', 1, '10.00', 0, '003.', NULL, 1),
(811, '2022-01-01', 'GASPARETTO, Libia ', 'Quando chega a hora', NULL, '813', '85.85872.61.6', 'São Paulo', 'Vida e conciência Editora', NULL, NULL, '1999', '1 ed.', NULL, 'ex.1', 'p.404', 1, '10.00', 0, '003.', NULL, 1),
(812, '2022-01-01', 'FEARING, Kenneth ', 'Sem Saída', '820(73).3', '813', '85.01.03454.1', 'Rio de janeiro', 'Rocco Ltda', NULL, NULL, '1997', '1 ed.', NULL, 'ex.1', 'p.145', 1, '10.00', 0, '003.', NULL, 1),
(813, '2022-01-01', 'ALENCAR, José de ', 'Senhora', NULL, 'B869.93', NULL, 'Rio de janeiro', 'Melhoramentos Editora', NULL, NULL, '1975', '13 ed.', NULL, 'ex.1', 'p.287', 1, '10.00', 0, '003.', NULL, 1),
(814, '2022-01-01', 'PILCHER, Rosamunde ', 'O quarto azul e outros contos', '820(411)3', 'B869.93', '85.286.0586.8', 'Rio de janeiro', 'Brertrand Brasil', NULL, NULL, '1997', '2 ed.', NULL, 'ex.1', 'p.304', 1, '10.00', 0, '003.', NULL, 1),
(815, '2022-01-01', 'MARCONI, Marina de Andrade ', 'Garimpos e garinpeiros', NULL, '622', NULL, 'São Paulo', 'Secretarias ', NULL, NULL, '1978', '1 ed.', NULL, 'ex.1', 'p.152', 1, '10.00', 0, '003.', NULL, 1),
(816, '2022-01-01', 'QUEIROZ, Rachel de ', 'Mandacaru', NULL, 'B869.93', '978.85.86707.59.9', 'São Paulo', 'Instituto Moreira Salles', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p.158', 1, '10.00', 0, '003.', NULL, 1),
(817, '2022-01-01', 'BIGARELLA, João José ', 'Fragmentos étnicos', NULL, '929.2', NULL, 'Curitiba-PR', 'Imprensa Oficial', NULL, NULL, '2005', '1 ed.', NULL, 'ex.1', 'p.704', 1, '10.00', 0, '003.', NULL, 1);
INSERT INTO `librarycollection` (`id`, `registrationDate`, `author`, `title`, `cdu`, `cdd`, `isbn`, `local`, `publisher_edition`, `number`, `month`, `year`, `edition`, `volume`, `copyNumber`, `pageNumber`, `typeAcquisitionId`, `price`, `prohibitedSale`, `provider`, `exclusionInfoTerm`, `registeredByUserId`) VALUES
(818, '2022-01-01', 'BIGARELLA, Iris Koehler ', 'O grande enigma', NULL, '750', '978.85.62770.00.5', 'Curitiba-PR', '***', NULL, NULL, '2009', '1 ed.', NULL, 'ex.1', 'p.420', 1, '10.00', 0, '003.', NULL, 1),
(819, '2022-01-01', 'URSINI, Alain Silver e James ', 'The noir Style', NULL, '778.5', '1.58567.485.0', 'New York', '***', NULL, NULL, '1999', '1 ed.', NULL, 'ex.1', 'p.248', 1, '10.00', 0, '003.', NULL, 1),
(820, '2022-01-01', 'SECURATO, José Roberto ', 'Cálculo Financeiro das tesourarias - Bancos e Empresas', NULL, '658.15', '85.85405.02.3', 'São Paulo', 'Saint Paul Editora', NULL, NULL, '2003', '1 ed.', NULL, 'ex.1', 'p.397', 1, '15.00', 0, '003.', NULL, 1),
(821, '2022-01-01', 'Antonio Evaristo Teixeira Lanzana', 'Economia brasileira: fundamentos e atualidades', NULL, '330.981', '85.224.3239.2', 'São Paulo', 'Atlas S.A.', NULL, NULL, '2002', '1 ed.', NULL, 'ex.1', 'p.196', 1, '15.00', 0, '003.', NULL, 1),
(822, '2022-01-01', 'LAPPONI, Juan Carlos ', 'Estatística usando excel', NULL, '519.502', '85.85624.12.4', 'São Paulo', 'Lapponi Editora', NULL, NULL, '2000', '1 ed.', NULL, 'ex.1', 'p.450', 1, '15.00', 0, '003.', NULL, 1),
(823, '2022-01-01', 'Giovanni Arrighi', 'Adam Smith em Pequim', '330', '330', '978.85.7559.112.3', 'São Paulo', 'Boitempo', NULL, NULL, '2008', '1 ed.', NULL, 'ex.1', 'p.432', 1, '15.00', 0, '003.', NULL, 1),
(824, '2022-01-01', 'Thomas Frank', 'Deus no céu e o mercado na terra', '330.342.14', '330.122', '85.01.06224.3', 'Rio de janeiro', 'Record', NULL, NULL, '2004', '1 ed.', NULL, 'ex.1', 'p.494', 1, '15.00', 0, '003.', NULL, 1),
(825, '2022-01-01', 'FICHER, Roger ', 'Como chegar ao sim: negociação de acordos sem concessões', '174.4', '158.5', '85.312.0956.0', 'Rio de janeiro', 'Imago ', NULL, NULL, '2005', '1 ed.', NULL, 'ex.1', 'p.216', 1, '15.00', 0, '003.', NULL, 1),
(826, '2022-01-01', 'LOPES, Alessandro Broedel ', 'A informação contábil e o mercado de capitais', NULL, '657.3', '85.221.0285.6', 'São Paulo', 'Thompson Learning', NULL, NULL, '2002', '1 ed.', NULL, 'ex.1', 'p.147', 1, '20.00', 0, '003.', NULL, 1),
(827, '2022-01-01', 'LUDÍCIBUS, Sérgio de ', 'Curso de Contabilidade para não contadores', NULL, '657', '85.224.2680.5', 'São Paulo', 'Atlas S.A.', NULL, NULL, '2000', '1 ed.', NULL, 'ex.1', 'p.282', 1, '20.00', 0, '003.', NULL, 1),
(828, '2022-01-01', 'YAMAMOTO, Mariana Mitiyo (Coord.)', 'Aprendendo contabilidade em moeda constante', NULL, '657', '85.224.1091.7', 'São Paulo', 'Atlas S.A.', NULL, NULL, '1994', '1 ed.', NULL, 'ex.1', 'p.314', 1, '20.00', 0, '003.', NULL, 1),
(829, '2022-01-01', 'SIMONSEN, Mario Henrique ', 'Macroeconomia', '339', '657', '978.85.224.5565.2', 'São Paulo', 'Atlas S.A.', NULL, NULL, '2009', '1 ed.', NULL, 'ex.1', 'p.732', 1, '20.00', 0, '003.', NULL, 1),
(830, '2022-01-01', 'MANDINO, OG', 'O maior vendedor do mundo', '17.023.36', '650.12', '978.85.01.01355.2', 'Rio de janeiro', 'Record', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p.110', 1, '20.00', 0, '003.', NULL, 1),
(831, '2022-01-01', 'BLIX, Hans', 'Desarmando o Iraque', NULL, '956.704', '85.89876.60.8', 'São Paulo', 'A Girafa Editora', NULL, NULL, '2004', '1 ed.', NULL, 'ex.1', 'p.406', 1, '20.00', 0, '003.', NULL, 1),
(832, '2022-01-01', 'GUIMARÃES, José Epitácio Passos ', 'Epítome da história da mineração no mundo antigo, no Brasil e nos Estados Unidos da América: valores e seus feitos e de suas personagens', NULL, '622.09', NULL, 'São Paulo', 'Secretarias ', NULL, NULL, '1981', '1 ed.', 'v.1', 'ex.1', 'p.173', 1, '20.00', 0, '003.', NULL, 1),
(833, '2022-01-01', 'CALLIOLI, Carlos A.', 'Álgebra linear e aplicações', NULL, '512.5', NULL, 'São Paulo', 'Atual', NULL, NULL, '1987', '1 ed.', NULL, 'ex.1', 'p.332', 1, '20.00', 0, '003.', NULL, 1),
(834, '2022-01-01', 'CARDOSO, Fernando Henrique', 'Pensadores que inventaram o Brasil', NULL, '928', '978.85.359.2287.5', 'São Paulo', 'Companhia das Letras', NULL, NULL, '2013', '1 ed.', NULL, 'ex.1', 'p.329', 1, '20.00', 0, '003.', NULL, 1),
(835, '2022-01-01', 'PIZA, Maria José de Toledo', 'Itu, Cidade histórica', NULL, '921', NULL, 'Itu-SP', '***', NULL, NULL, '1972', '1 ed.', NULL, 'ex.1', 'p.83', 1, '20.00', 0, '003.', NULL, 1),
(836, '2022-01-01', 'VIANA FILHO, Luiz', 'A vida de José de Alencar', '929.821.134.3(81)', '928.690', '978.85.7139.811.5', 'Salvador - BA', 'EDUFBA', NULL, NULL, '2008', '1 ed.', NULL, 'ex.1', 'p.426', 1, '15.00', 0, '003.', NULL, 1),
(837, '2022-01-01', 'OLIVEIRA, Plinio Corrêa de', 'Notas autobiográficas', NULL, '928', '978.85.64202.00.9', 'São Paulo', 'Editora Retornarei', NULL, NULL, '2010', '1 ed.', 'V.2', 'ex.1', 'p.565', 1, '15.00', 0, '003.', NULL, 1),
(838, '2022-01-01', 'Luiz Augusto Tagliacollo', 'Gestão Global', NULL, '382', '978.85.7129.532.2', 'São Paulo', 'Aduaneiras LTDA', NULL, NULL, '2008', '1 ed.', NULL, 'ex.1', 'p.349', 1, '15.00', 0, '003.', NULL, 1),
(839, '2022-01-01', 'BERKUJN, Scott ', 'A arte do gerenciamento de projetos', '004.658', '658.452', '978.85.7780.170.1', 'Porto Alegre', 'Bookman', NULL, NULL, '2008', '1 ed.', NULL, 'ex.1', 'p.388', 1, '15.00', 0, '003.', NULL, 1),
(840, '2022-01-01', 'DAMODORAN, Aswath ', 'A face oculta da avaliação: avaliação de empresas da velha tecnologia, da nova tecnologia e da nova economia', NULL, '658.452', '85.346.1384.2', 'São Paulo', 'Makron Books Ltda', NULL, NULL, '2002', '1 ed.', NULL, 'ex.1', 'p.466', 1, '15.00', 0, '003.', NULL, 1),
(841, '2022-01-01', 'KLEINDORFER, Paul R. ', 'O desafio das redes: estratégia, lucro e risco em um mundo interligado', '658.114.5', '658.88', '978.85.407.0118.2', 'Porto Alegre', 'Bookman', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.515', 1, '15.00', 0, '003.', NULL, 1),
(842, '2022-01-01', 'REZENDE, Fernando Antonio', 'Finanças Públicas', NULL, '657', '85.224.2835.2', 'São Paulo', 'Atlas S.A.', NULL, NULL, '2001', '2 ed.', NULL, 'ex.1', 'p.382', 1, '15.00', 0, '003.', NULL, 1),
(843, '2022-01-01', 'RYBA, Andréa ', 'Elementos da engenharia econômica', NULL, '658.15', '978.85.8212.360.7', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.152', 1, '20.00', 0, '003.', NULL, 1),
(844, '2022-01-01', 'Gilvan Brogini', 'Tributação e benefícios fiscais no comércio exterior', '34.336.2:339.5', '327', '978.85.7838.719.8', 'Curitiba-PR', 'Ibpex', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p.228', 1, '20.00', 0, '003.', NULL, 1),
(845, '2022-01-01', 'Randy Charles Epping', 'Economia mundial para iniciantes: 64 conceitos economicos básicos que vão mudar a maneira de você ver o mundo', NULL, '337', '85.86518.14.4', 'São Paulo', 'Bei comunicação', NULL, NULL, '2001', '1 ed.', 'v.1', 'ex.1', 'p.170', 1, '20.00', 0, '003.', NULL, 1),
(846, '2022-01-01', 'Arnoldo Wald', 'Coleção Comentários ao novo código civil -                       Volume  XIV(14): Livro  II', '347.72(81)', '347', '85.309.2170.4', 'Rio de janeiro', 'Forense Editora', NULL, NULL, '2005', '1 ed.', 'v.14', 'ex.2', 'p.1021', 1, '20.00', 0, '003.', NULL, 1),
(847, '2022-01-01', 'Judith Martins- Costa', 'Coleção Comentários ao novo código civil -                       Volume  V(5): Tomo  I', '347.44', '347', '85.309.1967.x', 'Rio de janeiro', 'Forense Editora', NULL, NULL, '2006', '1 ed.', 'v.5.I', 'ex.2', 'p.798', 1, '20.00', 0, '003.', NULL, 1),
(848, '2022-01-01', 'Judith Martins- Costa', 'Coleção Comentários ao novo código civil -                       Volume  V(5): Tomo  II', '347.44', '347', '85.309.1844.4', 'Rio de janeiro', 'Forense Editora', NULL, NULL, '2004', '1 ed.', 'v.5.II', 'ex.2', 'p.588', 1, '20.00', 0, '003.', NULL, 1),
(849, '2022-01-01', 'Flávio Tartuce', 'Coleção Direito civil: Direito das obrigações e responsabilidade civil     Volume 2 ', '347.4(81)', '347', '978.85.309.85.40.2', 'Rio de janeiro', 'Forense Editora', NULL, NULL, '2014', '1 ed.', 'v.2', 'ex.1', 'p.634', 1, '20.00', 0, '003.', NULL, 1),
(850, '2022-01-01', 'Flávio Tartuce', 'Coleção Direito civil: Teoria geral dos contratos e contratações em espécie Volume 3', '347.44(81)', '347', '978.85.309.5241.9', 'Rio de janeiro', 'Forense Editora', NULL, NULL, '2014', NULL, 'v.3', 'ex.1', 'p.708', 1, '20.00', 0, '003.', NULL, 1),
(851, '2022-01-01', 'Flávio Tartuce', 'Coleção Direito civil: Direito da família                                   Volume 5', '347.6(81)', '347', '978.85.309.4921.1', 'Rio de janeiro', 'Forense Editora', NULL, NULL, '2014', NULL, 'v.5', 'ex.1', 'p.621', 1, '20.00', 0, '003.', NULL, 1),
(852, '2022-01-01', 'Maria Helena Diniz', 'Curso de Direito civil brasileiro - Teoria geral das obrigações - Volume 2', '347(81)', '347', '85.02.04541.5', 'São Paulo', 'Saraiva', NULL, NULL, '2004', NULL, 'v.2', 'ex.1', 'p.458', 1, '20.00', 0, '003.', NULL, 1),
(853, '2022-01-01', 'Maria Helena Diniz', 'Curso de Direito civil brasileiro - Teoria das obrigações contratuais e extracontratuais  - Volume 3', '347(81)', '347', '85.02.04492.3', 'São Paulo', 'Saraiva', NULL, NULL, '2003', NULL, 'v.3', 'ex.1', 'p.821', 1, '20.00', 0, '003.', NULL, 1),
(854, '2022-01-01', 'Maria Helena Diniz', 'Curso de Direito civil brasileiro - Direito da Família  - Volume 5', '347(81)', '347', '85.02.04543.1', 'São Paulo', 'Saraiva', NULL, NULL, '2004', NULL, 'v.5', 'ex.1', 'p.612', 1, '30.00', 0, '003.', NULL, 1),
(855, '2022-01-01', 'Maria Helena Diniz', 'Curso de Direito civil brasileiro - Direito das sucessões  - Volume 6', '347(81)', '347', '85.02.04544.x', 'São Paulo', 'Saraiva', NULL, NULL, '2004', NULL, 'v.6', 'ex.1', 'p.406', 1, '30.00', 0, '003.', NULL, 1),
(856, '2022-01-01', 'Maria Helena Diniz', 'Curso de Direito civil brasileiro - Responsabilidade civil- Volume 7', '347(81)', '347', '85.02.04545.0', 'São Paulo', 'Saraiva', NULL, NULL, '2004', NULL, 'v.7', 'ex.1', 'p.647', 1, '30.00', 0, '003.', NULL, 1),
(857, '2022-01-01', 'Susan Faludi', 'Dominados: como a cultura traiu o homem americano', '316.346.2.055.2', '305.310973', '85.325.2033.2', 'Rio de janeiro', 'Rocco Ltda', NULL, NULL, '2006', NULL, 'v.1', 'ex.1', 'p.642', 1, '20.00', 0, '003.', NULL, 1),
(858, '2022-01-01', 'KEMP, Philipe', 'Tudo sobre cinema', '791', '791.43', '978.85.7542.668.5', 'Rio de janeiro', 'Sextante', NULL, NULL, '2011', NULL, 'v.1', 'ex.1', 'p.576', 1, '20.00', 0, '003.', NULL, 1),
(859, '2022-01-01', 'JOBIM, Danton', 'Espírito do Jornalismo', NULL, '070.6', '85.314.0051.1', 'São Paulo', 'EDUSP EDITORA DA UNIV. DE SP', NULL, NULL, '1992', NULL, 'v.1', 'ex.1', 'p.222', 1, '20.00', 0, '003.', NULL, 1),
(860, '2022-01-01', 'Joni Tadeu Borges', 'Financiamento ao comércio exterior: o que uma empresa precisa saber', NULL, '382.0981', '978.85.7838.057.1', 'Curitiba-PR', 'Ibpex', NULL, NULL, '2009', '1 ed.', 'v.1', 'ex.1', 'p.256', 1, '20.00', 0, '003.', NULL, 1),
(861, '2022-01-01', 'BACHX, Arago de Carvalho ', 'Prelúdio à análise combinatória', NULL, '512.5', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1975', '1 ed.', NULL, 'ex.1', 'p.234', 1, '20.00', 0, '003.', NULL, 1),
(862, '2022-01-01', 'LEVY, Bernard Henry ', 'O século de Sartre: inquérito filosófico', '1(44)', '194', '85.209.1229.x', 'Rio de janeiro', 'Nova Fronteira S.A.', NULL, NULL, '2001', '1 ed.', 'v.1', 'ex.1', 'p.570', 1, '20.00', 0, '003.', NULL, 1),
(863, '2022-01-01', 'Daron Acemuglu', 'Por que as nações fracassam: as origens do poder da prosperidade e da pobreza', '338.22', '337', '978.85.352.3857.0', 'Rio de janeiro', 'Elsevier', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.401', 1, '20.00', 0, '003.', NULL, 1),
(864, '2022-01-01', 'Abbé de Saint-Pierre', 'Projeto para tornar perpétua a paz na Europa', NULL, '327', '85.7060.151.4', 'São Paulo', 'Imprensa Oficial', NULL, NULL, '2003', '1 ed.', 'v.1', 'ex.1', 'p.694', 1, '20.00', 0, '003.', NULL, 1),
(865, '2022-01-01', 'Fundação Alexandres Gustão', 'Brasil e China no reordenamento das relações internacionais: desafios e oportunidades', '327.3(81:51)', '327', '978.85.7631.343.4', 'Brasília-DF', 'Fundação Alexandre de Gusmão ', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.536', 1, '20.00', 0, '003.', NULL, 1),
(866, '2022-01-01', 'MARTI, Michael E.', 'A China de Deng Xiaoping', '94(510)\"1976/2000\"', '951.058', '978.85.209.3052.6', 'Rio de janeiro', 'Nova Fronteira S.A.', NULL, NULL, '2007', '1 ed.', NULL, 'ex.1', 'p.357', 1, '10.00', 0, '003.', NULL, 1),
(867, '2022-01-01', 'Janet M.Thomas', 'Economia ambiental: fundamentos, políticas e aplicações', NULL, '333.7', '978.85.221.0652.3', 'São Paulo', 'Cengage Learning', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.556', 1, '10.00', 0, '003.', NULL, 1),
(868, '2022-01-01', 'SILVA, Roberto Peres de Queiroz e', 'Temas básicos em comunicação', NULL, '070', NULL, 'São Paulo', 'Edições Paulinas', NULL, NULL, '1983', '1 ed.', NULL, 'ex.1', 'p.250', 1, '10.00', 0, '003.', NULL, 1),
(869, '2022-01-01', 'RODRIGUES, Chrystian Marcelo ', 'Série Gestão Financeira: Análise de Crédito e risco', NULL, '658.88', '978.85.65704.55.7', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.197', 1, '10.00', 0, '003.', NULL, 1),
(870, '2022-01-01', 'ANTONOVZ, Tatiane ', 'Série Gestão Financeira: Contabilidade ambiental', NULL, '657.4', '978.85.443.0054.1', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2014', '1 ed.', NULL, 'ex.1', 'p.193', 1, '10.00', 0, '003.', NULL, 1),
(871, '2022-01-01', 'Luiz Alberto Santos Rocha', 'Projeto de poços de petróleo: geopressões e assentamentos de colunas de revestimento', '622.25', '622.3382', '978.85.7193.214.2', 'Rio de janeiro', 'Interciência', NULL, NULL, '2009', '1 ed.', 'v.1', 'ex.1', 'p.561', 1, '10.00', 0, '003.', NULL, 1),
(872, '2022-01-01', 'JENKINS, John Major ', '2012: a história', NULL, '299.93', '978.85.7635.718.6', 'São Paulo', 'Larousse do Brasil', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p.462', 1, '15.00', 0, '003.', NULL, 1),
(873, '2022-01-01', 'Ignácio de Loyola Brandão', 'Desvirando a página: a vida de Olavo Setubal', NULL, '338.092', '978.85.260.130.7', 'São Paulo', 'Global Editora', NULL, NULL, '2008', '1 ed.', 'v.1', 'ex.1', 'p.527', 1, '20.00', 0, '003.', NULL, 1),
(874, '2022-01-01', 'LUFÍCIBUS, Sergio de  (Org.)', 'Contabilidade introdutória', NULL, '657', '85.224.0091.1', 'São Paulo', 'Atlas S.A.', NULL, NULL, '1986', '1 ed.', NULL, 'ex.1', 'p.310', 1, '20.00', 0, '003.', NULL, 1),
(875, '2022-01-01', 'LUFÍCIBUS, Sergio de  (Org.)', 'Contabilidade introdutória (Caderno de Exercícios)', NULL, '657', '85.224.0091.1', 'São Paulo', 'Atlas S.A.', NULL, NULL, '1986', '1 ed.', NULL, 'ex.1', 'p.310', 1, '20.00', 0, '003.', NULL, 1),
(876, '2022-01-01', 'HASTINGS, David F. ', 'Banking: Gestão de ativos, passivos e resultados em instituições financeiras', '657.336.7', '657.9', '85.02.05121.0', 'São Paulo', 'Saraiva', NULL, NULL, '2006', '1 ed.', NULL, 'ex.1', 'p.357', 1, '15.00', 0, '003.', NULL, 1),
(877, '2022-01-01', 'Alexandre Póvoa', 'Mundo financeiro:  o olhar de um gestor', '336.76', '332.6', '978.85.02.09534.2', 'São Paulo', 'Saraiva', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.568', 1, '10.00', 0, '003.', NULL, 1),
(878, '2022-01-01', 'MÁLAGA, Flávio Kezam ', 'Análises de demonstrativos fincanceiros e da performance empresarial: para empresas não financeiras', NULL, '657.3', '978.85.8004.052.4', 'São Paulo', 'Saint Paul Editora', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.381', 1, '19.90', 0, '003.', NULL, 1),
(879, '2022-01-01', 'Tribunal de contas', 'Licitações e contratos: orientações e jurisprudência do TCU', NULL, '657.833', '978.85.7018.319.4', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2010', '4 ed.', NULL, 'ex.1', 'p.910', 1, '30.00', 0, '003.', NULL, 1),
(880, '2022-01-01', 'José Luiz Machado', 'Blocos Econômicos no panorama mundial: análise geográfica e econômica', NULL, '337', '978.85.7838.830.0', 'Curitiba-PR', 'Ibpex', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.197', 1, '20.00', 0, '003.', NULL, 1),
(881, '2022-01-01', 'ODUM, Eugene P. ', 'Fundamentos da ecologia', NULL, '577', '978.85.221.0541.0', 'São Paulo', 'Cengage Learning', NULL, NULL, '2008', '1 ed.', NULL, 'ex.1', 'p.612', 1, '20.00', 0, '003.', NULL, 1),
(882, '2022-01-01', 'LAMA, Dalai ', 'O livro da sabedoria', NULL, '294.3923', '85.336.1346.6', 'São Paulo', 'Martins', NULL, NULL, '2000', '1 ed.', NULL, 'ex.1', 'p.115', 1, '20.00', 0, '003.', NULL, 1),
(883, '2022-01-01', 'SAUDERS, Anthony Saunders', 'Adminsitração de instituições financeiras', NULL, '657.833', '978.85.224.2451.1', 'São Paulo', 'Atlas S.A.', NULL, NULL, '2007', '1 ed.', NULL, 'ex.1', 'p.663', 1, '20.00', 0, '003.', NULL, 1),
(884, '2022-01-01', 'BYHAM, William C. ', 'Zaap! O poder da energização: como melhorar a qualidade e produtividade e a satisfação de seus funcionários', '658.314.7', '658.314', '85.7001.740.5', 'Rio de janeiro', 'Campus Editora', NULL, NULL, '1997', '18 ed.', NULL, 'ex.1', 'p.153', 1, '15.00', 0, '003.', NULL, 1),
(885, '2022-01-01', 'CIAMPONI, Durval ', 'Introdução à Metapsicologia: indícios da vida espiritual', NULL, '291.44', '85.7513.026.9', 'São Paulo', 'Elevação Editora', NULL, NULL, '2002', '1 ed.', NULL, 'ex.1', 'p.157', 1, '17.00', 0, '003.', NULL, 1),
(886, '2022-01-01', 'PERCHERON, Maurice ', 'Buda e Budismo', '294.3', '294.3', '85.220.2385.8', 'Rio de janeiro', 'Agir Editora', NULL, NULL, '1994', '1 ed.', NULL, 'ex.1', 'p.224', 1, '20.00', 0, '003.', NULL, 1),
(887, '2022-01-01', 'BARRADONI, Giovanna ', 'A filosofia americana: conversações', NULL, '191', '85.7139.454.7', 'São Paulo', 'UNESP - Universidade Estadual Paulista', NULL, NULL, '2003', '1 ed.', NULL, 'ex.1', 'p.233', 1, '10.00', 0, '003.', NULL, 1),
(888, '2022-01-01', 'EDINGTON, J. ', '50 tons para o sucesso', NULL, '270', '978.85.2203.229.7', 'Rio de janeiro', 'Agir Editora', NULL, NULL, '2015', '1 ed.', NULL, 'ex.1', 'p.256', 1, '30.00', 0, '003.', NULL, 1),
(889, '2022-01-01', 'SHINYASHIKI, Roberto ', 'O poder da solução', NULL, '158', '85.7312.393.1', 'São Paulo', 'Gente Editora', NULL, NULL, '2003', '1 ed.', 'v.1', 'ex.1', 'p.198', 1, '20.00', 0, '003.', NULL, 1),
(890, '2022-01-01', 'MOSCOVICI, Fela ', 'Razão e emoção: a inteligência emocional em questão', NULL, '658.300.19', '85.85651.29.6', 'Salvador - BA', 'Casa da Qualidade', NULL, NULL, '1997', '2 ed.', NULL, 'ex.1', 'p.133', 1, '20.00', 0, '003.', NULL, 1),
(891, '2022-01-01', 'Amélia Máximo de Araujo e Loriza Lacerda de Almeida (org.)', 'Incubadora de cooperativas populares: as experiências da Unesp', NULL, '378', '978.85.7983.336.6', 'São Paulo', 'UNESP - Universidade Estadual Paulista', NULL, NULL, '2012', NULL, 'v.1', 'ex.1', 'p.80', 1, '25.00', 0, '003.', NULL, 1),
(892, '2022-01-01', 'Rodrigo Berté', 'Gestão Socioambiental no Brasil', NULL, '304.20981', '978.85.7838.415.9', 'Curitiba-PR', 'Ibpex', NULL, NULL, '2009', NULL, 'v.1', 'ex.1', 'p.211', 1, '30.00', 0, '003.', NULL, 1),
(893, '2022-01-01', 'CORBARI, Ely Célia ', 'Controle Interno e externo na administração públcia', NULL, '657.833', '978.85.8212.125.3', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.250', 1, '24.00', 0, '003.', NULL, 1),
(894, '2022-01-01', 'Roosevelt Brasil Queiroz', 'Formação e gestão de pólíticas públicas', NULL, '354.81', '978.85.65704.74.8', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.277', 1, '26.00', 0, '003.', NULL, 1),
(895, '2022-01-01', 'Roosevelt Brasil Queiroz', 'Formação e gestão de pólíticas públicas', NULL, '354.81', '978.85.65704.74.8', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.3', 'p.277', 1, '28.00', 0, '003.', NULL, 1),
(896, '2022-01-01', 'Roosevelt Brasil Queiroz', 'Formação e gestão de pólíticas públicas', NULL, '354.81', '978.85.65704.74.8', 'Curitiba-PR', 'Ibpex', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.4', 'p.277', 1, '15.00', 0, '003.', NULL, 1),
(897, '2022-01-01', 'Jeffrey Sachs', 'A riqueza de todos', '338.1', '338.9', '978.85.209.2137.1', 'Rio de Janeiro', 'Nova Fronteira S.A.', NULL, NULL, '2008', '1 ed.', 'v.1', 'ex.1', 'p.479', 1, '15.00', 0, '003.', NULL, 1),
(898, '2022-01-01', 'SILVER, Nate ', 'O sinal e o ruído: por que tantas previsões falham e outras não', '519.2', '519.5', '978.85.8057.346.6', 'Rio de Janeiro', 'Intrínseca Editora', NULL, NULL, '2013', '1 ed.', NULL, 'ex.1', 'p.544', 1, '15.00', 0, '003.', NULL, 1),
(899, '2022-01-01', 'Amaury Patrick Gremaud', 'Economia Brasileira contemporânea', NULL, '330.981', '978.85.224.4835.7', 'São Paulo', 'Atlas S.A.', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.659', 1, '28.00', 0, '003.', NULL, 1),
(900, '2022-01-01', 'YALLOP, David ', 'O poder da glória: o lado negro do Vaticano de João Paulo II', NULL, '282', '978.85.7665.312.7', 'São Paulo', 'Planeta Brasil', NULL, NULL, '2007', '1 ed.', NULL, 'ex.1', 'p.597', 1, '16.00', 0, '003.', NULL, 1),
(901, '2022-01-01', 'TORRES, Claudio ', 'A Bíblia do marketing digita', NULL, '658.8', '978.85.7522.202.7', 'São Paulo', 'Novatec Editora', NULL, NULL, '2009', '1 ed.', NULL, 'ex.1', 'p.399', 1, '20.00', 0, '003.', NULL, 1),
(902, '2022-01-01', 'Walter Bruyére-Ostells', 'História dos mercenários: de 1789 aos nossos dias', NULL, '355.35409', '978.85.7244.743.0', 'São Paulo', 'Contexto Editora', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.239', 1, '23.00', 0, '003.', NULL, 1),
(903, '2022-01-01', 'SENNE, Edson Luiz Fraça ', 'Primeiro Curso de programação em C', NULL, '004', '85.7502.186.9', 'Florianópolis', 'Visual Books', NULL, NULL, '2006', '1 ed.', NULL, 'ex.1', 'p.308', 1, '20.00', 0, '003.', NULL, 1),
(904, '2022-01-01', 'DACORSO NETO, César ', 'Elementos de análise vetorial', NULL, '516.83', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1976', '1 ed.', NULL, 'ex.1', 'p.160', 1, '20.00', 0, '003.', NULL, 1),
(905, '2022-01-01', 'John Naisbitt', 'Paradoxo Global', NULL, '330.9', '85.7001.885.1', 'Rio de Janeiro', 'Campus Editora', NULL, NULL, '1999', '1 ed.', 'v.1', 'ex.1', 'p.333', 1, '20.00', 0, '003.', NULL, 1),
(906, '2022-01-01', 'FURTADO, Peter ', '1001 Dias que abalaram o mundo', '930.24', '902.02', '978.85.99296.45.5', 'Rio de Janeiro', 'Sextante', NULL, NULL, '2009', '1 ed.', NULL, 'ex.1', 'p.959', 1, '20.00', 0, '006.', NULL, 1),
(907, '2022-01-01', 'A. M. Comanducci', 'Dizionario Ilustrado dei Pittori, Designatori e Incisori italiani Moderni e Contemporanei - Volume 1', NULL, '850', NULL, 'Italia', 'Luigi Patuzzi Editore Milano', NULL, NULL, '1970', '1 ed.', 'v.1', 'ex.1', 'p.1 - 644', 1, '20.00', 0, '006.', NULL, 1),
(908, '2022-01-01', 'A. M. Comanducci', 'Dizionario Ilustrado dei Pittori, Designatori e Incisori italiani Moderni e Contemporanei - Volume 4', NULL, '850', NULL, 'Italia', 'Luigi Patuzzi Editore Milano', NULL, NULL, '1970', '1 ed.', 'v.4', 'ex.1', 'p.2089 - 2786', 1, '25.00', 0, '006.', NULL, 1),
(909, '2022-01-01', 'SOUZA, Alcidio Mafra de Souza ', 'Museu Nacional de Belas Artes ( Brasil)', NULL, '708.981', NULL, 'São Paulo', 'Banco Safra', NULL, NULL, '1985', '1 ed.', NULL, 'ex.1', 'p.396', 1, '20.00', 0, '006.', NULL, 1),
(910, '2022-01-01', 'MAIA, Tom / CAMARGO, Thereza Regina de ', 'Do Rio a Santos: velho litoral', NULL, '740', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1979', '1 ed.', NULL, 'ex.1', 'p.191', 1, '30.00', 0, '006.', NULL, 1),
(911, '2022-01-01', 'Gérard Père et Fils', 'O mundo do Havana', NULL, '338.173', NULL, 'São Paulo', 'Ática', NULL, NULL, '1997', '1 ed.', NULL, 'ex.1', 'p.140', 1, '20.00', 0, '006.', NULL, 1),
(912, '2022-01-01', 'Fundação Pinacoteca Benedicto Calixto', 'Seis Grandes Pintoras', NULL, '750', NULL, 'São Paulo', '***', NULL, NULL, '1993', '1 ed.', NULL, 'ex.1', NULL, 1, '30.00', 0, '006.', NULL, 1),
(913, '2022-01-01', 'Musée Jacquemart-André', 'Du Greco à Dalí: les grands maîtres espagnols de la collection Pérez Simón', NULL, '840', '978.90.53.49.7760', '***', '***', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.223', 1, '20.00', 0, '006.', NULL, 1),
(914, '2022-01-01', 'Fernando B. Meneguin e Rafael Silveira e Silva (Org.)', 'Avaliação de impacto legislativo: cenários e perspectivas para sua aplicação', NULL, '340.328', '978.85.7018.887.8', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2017', '1 ed.', 'v.1', 'ex.1', 'p.123', 1, '20.00', 0, '003.', NULL, 1),
(915, '2022-01-01', 'Centro de Gestão e Estudos Estratégicos', 'Mestres 2012: Estudo Demográfico da base técnico-científica brasileira', '378.21', '370.7', '978.85.60755.49.3', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.428', 1, '10.00', 0, '003.', NULL, 1),
(916, '2022-01-01', 'Archie Brown', 'Ascensão e queda do comunismo', '321.74', '320.532', '978.85.01.09000.3', 'Rio de Janeiro', 'Record', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.852', 1, '15.00', 0, '003.', NULL, 1),
(917, '2022-01-01', 'LIMA, Luiz Costa', 'O redemunho do horror: as margens do ocidente', NULL, '809.916', '85.7449.648.4', 'Rio de Janeiro', 'Planeta Brasil', NULL, NULL, '2003', '1 ed.', NULL, 'ex.1', 'p.454', 1, '15.00', 0, '003.', NULL, 1),
(918, '2022-01-01', 'RANDALL, Lisa', 'Batendo à porta do céu', NULL, '530', '978.85.359.2365.8', 'São Paulo', 'Companhia das Letras', NULL, NULL, '2013', '1 ed.', NULL, 'ex.1', 'p.571', 1, '15.00', 0, '003.', NULL, 1),
(919, '2022-01-01', 'Luiz Alberto Moniz Bandeira', 'La formación del Imperio Americano', NULL, '330.9', NULL, 'Buenos Aires', 'Grupo Editorial Norma ', NULL, NULL, '2007', '1 ed.', 'v.1', 'ex.1', 'p.712', 1, '20.00', 0, '003.', NULL, 1),
(920, '2022-01-01', 'Sylvan Darnil', '80 homens para mudar o mundo', NULL, '338.04092', '978.85.7831.015.8', 'São Paulo', 'Clio Editora', NULL, NULL, '2009', '1 ed.', 'v.1', 'ex.1', 'p.238', 1, '30.00', 0, '003.', NULL, 1),
(921, '2022-01-01', 'Mike Davis', 'Holocaustos culturais', '362.1(091)', '363.8091724', '85.01.06131.x', 'Rio de Janeiro', 'Record', NULL, NULL, '2002', '1 ed.', 'v.1', 'ex.1', 'p.486', 1, '30.00', 0, '003.', NULL, 1),
(922, '2022-01-01', 'Jaime Cortesão', 'Alexandre de Gusmão e o Tratado de Madrid - Tomo I', NULL, '327', NULL, 'São Paulo', 'Imprensa Oficial', NULL, NULL, '2006', '1 ed.', 'v.1', 'ex.1', 'p.433', 1, '30.00', 0, '003.', NULL, 1),
(923, '2022-01-01', 'Jaime Cortesão', 'Alexandre de Gusmão e o Tratado de Madrid - Tomo II', NULL, '327', NULL, 'São Paulo', 'Imprensa Oficial', NULL, NULL, '2006', '1 ed.', 'v.2', 'ex.1', 'p.433', 1, '30.00', 0, '003.', NULL, 1),
(924, '2022-01-01', 'Senado Federal', 'Estatuto do Idoso - Atualizada em 2018', '3.053.9(81)(094)', '348', '978.85.402.0640.3', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2018', '1 ed.', 'v.1', 'ex.1', 'p.91', 1, NULL, 1, '003.', NULL, 1),
(925, '2022-01-01', 'Senado Federal', 'Estatuto do Idoso - Atualizada em 2018', '3.053.9(81)(094)', '348', '978.85.402.0640.3', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2018', '1 ed.', 'v.1', 'ex.4', 'p.91', 1, NULL, 1, '003.', NULL, 1),
(926, '2022-01-01', 'Senado Federal', 'Estatuto do Idoso - Atualizada em 2018', '3.053.9(81)(094)', '348', '978.85.402.0640.3', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2018', '1 ed.', 'v.1', 'ex.5', 'p.91', 1, NULL, 1, '003.', NULL, 1),
(927, '2022-01-01', 'Jaques Bushtsky', 'Código tributário nacional e sistema contitucional tributário', '34:336.22', '343', '85.7647.579.0', 'São Paulo', 'IOB Thomson', NULL, NULL, '2006', '1 ed.', 'v.1', 'ex.1', 'p.536', 1, '20.00', 0, '003.', NULL, 1),
(928, '2022-01-01', 'José Geraldo Brito Filomeno, Luiz Guilherme da Costa Wagner Junior e Renato Afonso Gonçalves (coord)', 'O Código Civil e sua interdisciplinaridade: os reflexos do código civil nos demais ramos do Direito', '340', '342.1', '85.7308.718.8', 'Belo Horizonte', 'Del Rey', NULL, NULL, '2004', '1 ed.', 'v.1', 'ex.1', 'p.656', 1, '15.00', 0, '003.', NULL, 1),
(929, '2022-01-01', 'Tribunal Regional Federal', 'A constituição na visão dos tribunais - Interpretação e julgados, artigos por artigos - Volume 1 (Art. 1º a 43)', NULL, '348', '85.02.02368.3', 'São Paulo', 'Saraiva', NULL, NULL, '1997', '1 ed.', 'v.1', 'ex.1', 'p.522', 1, '15.00', 0, '003.', NULL, 1),
(930, '2022-01-01', 'Tribunal Regional Federal', 'A constituição na visão dos tribunais - Interpretação e julgados, artigos por artigos - Volume 2 (Art. 44 a 169)', NULL, '348', '85.02.02369.1', 'São Paulo', 'Saraiva', NULL, NULL, '1997', '1 ed.', 'v.2', 'ex.1', 'p.1203', 1, '15.00', 0, '003.', NULL, 1),
(931, '2022-01-01', 'Tribunal Regional Federal', 'A constituição na visão dos tribunais - Interpretação e julgados, artigos por artigos - Volume 3 (Art.170 a 243)', NULL, '348', '85.02.02370.5', 'São Paulo', 'Saraiva', NULL, NULL, '1997', '1 ed.', 'v.3', 'ex.1', 'p.1650', 1, '15.00', 0, '003.', NULL, 1),
(932, '2022-01-01', 'James R. McGuigan', 'Economia de empresas: aplicações, estratégias e táticas', NULL, '338.5024658', NULL, 'São Paulo', 'Cengage Learning', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.500', 1, '15.00', 0, '003.', NULL, 1),
(933, '2022-01-01', 'VARGAS, Ricardo Viana ', 'Análise de valor agregado: revolucionando o gerenciamento de prazos e custos', NULL, '658.3', '978.85.7452.469.6', 'Rio de Janeiro', 'Brasport Editora', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.116', 1, '15.00', 0, '003.', NULL, 1),
(934, '2022-01-01', 'Octavio Ianni', 'O Colapso do Populismo no Brasil', NULL, '320.5', NULL, 'Rio de Janeiro', 'Civilização Brasileira', NULL, NULL, '1975', '1 ed.', 'v.1', 'ex.1', 'p.223', 1, '20.00', 0, '003.', NULL, 1),
(935, '2022-01-01', 'Câmara Muncipal de Itapevi', 'Lei Orgânica Muncipal - Editada em 2014', NULL, '348', NULL, 'Itapevi-SP', 'Câmara de Itapevi', NULL, NULL, '2014', '1 ed.', 'v.1', 'ex.6', 'p.50', 3, NULL, 1, '***', NULL, 1),
(936, '2022-01-01', 'MELO, Osmar Alves de ', 'O sono dos justos', '821.134.1(81)34', 'B869.93', '978.85.400.2385.7', 'Goiânia', 'Kelps Editora', NULL, NULL, '2018', '1 ed.', NULL, 'ex.1', 'p.132', 1, '15.00', 0, '003.', NULL, 1),
(937, '2022-01-01', 'Senado Federal', 'Estatuto da Microempresa', NULL, '348', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2009', '1 ed.', 'v.1', 'ex.2', 'p.158', 1, NULL, 1, '003.', NULL, 1),
(938, '2022-01-01', 'Assembleia Legislativa de SP', 'Constituição do Estado de São Paulo (Atualizada em 2016)', '342.4(815.6)', '348', '978.85.61175.65.8', 'São Paulo', 'Triunfal Gráfica e Editora', NULL, NULL, '2017', '1 ed.', 'v.1', 'ex.1', 'p.500', 1, NULL, 1, '003.', NULL, 1),
(939, '2022-01-01', 'Assembleia Legislativa de SP', 'Constituição do Estado de São Paulo Anotada (Atualizada em 2006)', '342.4(815.6)', '348', NULL, 'São Paulo', 'Imprensa Oficial', NULL, NULL, '2006', '1 ed.', 'v.1', 'ex.1', 'p.541', 1, NULL, 1, '003.', NULL, 1),
(940, '2022-01-01', 'Brasil', 'Estatuto da Cidade e legislação correlatas (Atualizada em 2004)/ Lei de Responsabilidade Fiscal', '34.711.4(81)(094)', '348', '85.7060.322.3/ 85.7060.308.8', 'São Paulo', 'Imprensa Oficial', NULL, NULL, '2004', '1 ed.', 'v.1', 'ex.1', 'p.69/63', 1, NULL, 1, '003.', NULL, 1),
(941, '2022-01-01', 'Ricardo Tripoli', 'Manual Jurídico de Proteção Animal', NULL, '341.347', NULL, 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '2013', '1 ed.', 'v.1', 'ex.1', 'p.60', 1, '25.00', 0, '003.', NULL, 1),
(942, '2022-01-01', 'Alcides Redondo Rodrigues (Org.)', 'Gestão Fiscal Responsável: simpes municipal: Caderno IBAM 7 - O papel da Câmara municipal na gestão fiscal ', NULL, '336', NULL, '***', 'Instituto Brasileiro de Administração Municipal - IBAM', NULL, NULL, '2001', '1 ed.', 'v.7', 'ex.1', 'p.32', 1, '10.00', 0, '003.', NULL, 1),
(943, '2022-01-01', 'Assembleia Legislativa de SP', 'Estatuto das Cidades - Atualilzado em 2001', NULL, '348', NULL, 'São Paulo', '***', NULL, NULL, '2001', NULL, 'v.1', 'ex.1', 'p.52', 1, NULL, 1, '003.', NULL, 1),
(944, '2022-01-01', 'Marcos Ramayana (Org.)', 'Código eletoral - Lei nº 4.737 de 15 de julho de 1965', NULL, '348', '85.87873.12.1', 'Rio de Janeiro', 'Ideia Jurídica Editora', NULL, NULL, '2001', NULL, 'v.1', 'ex.1', 'p.341', 1, '10.00', 0, '003.', NULL, 1),
(945, '2022-01-01', 'Irany Novah Moraes', 'Erro médico e a lei', '347.141.61', '346.03', '85.85486.14.7', 'São Paulo', 'Lejus Editora', NULL, NULL, '1998', NULL, 'v.1', 'ex.1', 'p.608', 1, '10.00', 0, '003.', NULL, 1),
(946, '2022-01-01', 'Eunice Maria Goffi Marquesini Oliveira Lucena', 'Gestão Fiscal Responsável: simpes municipal: Caderno IBAM 6 - Gestão de recursos Humanos e a LRF', NULL, '351.3', NULL, '***', 'Instituto Brasileiro de Administração Municipal - IBAM', NULL, NULL, '2001', NULL, 'v.6', 'ex.1', 'p.76', 1, '10.00', 0, '003.', NULL, 1),
(947, '2022-01-01', 'Câmara Muncipal de Louveira', 'Lei Orgânica Muncipal - Editada em 2009', NULL, '348', NULL, 'Louveira-SP', 'Câmara ', NULL, NULL, '2009', NULL, 'v.1', 'ex.1', 'p.160', 1, NULL, 1, '003.', NULL, 1),
(948, '2022-01-01', 'BERLOWITZ, Leslie / DONOGHUE, Denis  / MENAND, Louis', 'A América em Teoria', '97', '970', '85.218.0100.9', 'Rio de Janeiro', 'Forense Editora', NULL, NULL, '1993', '1 ed.', NULL, 'ex.1', 'p.284', 1, '20.00', 0, '003.', NULL, 1),
(949, '2022-01-01', 'José Eduardo da Costa', 'Coleção professor Agostinho Alvim - Evicção nos contratos onerosos: fundamento, natureza e estrutura', '347.451.031.31', '347', '85.02.04749.3', 'São Paulo', 'Saraiva', NULL, NULL, '2004', NULL, 'v.1', 'ex.1', 'p.116', 1, '20.00', 0, '003.', NULL, 1),
(950, '2022-01-01', '***', 'Coleção Manuais de Legislação Atlas: Organização Judiciária do Estado de São Paulo - Volume 4', NULL, '348', NULL, 'São Paulo', 'Atlas S.A.', NULL, NULL, '1986', NULL, 'v.1', 'ex.1', 'p.223', 1, '20.00', 0, '003.', NULL, 1),
(951, '2022-01-01', 'Caramuru Afonto Francisco', 'Dos abusos nas eleições: a tutela jurídica da legitimidade e normalidade do processo eleitoral', NULL, '342.07', '85.7453+301.7', 'São Paulo', 'Juarez de Oliveira Ltda', NULL, NULL, '2002', NULL, 'v.1', 'ex.1', 'p.248', 1, '20.00', 0, '003.', NULL, 1),
(952, '2022-01-01', 'Eneida Gonçalves de Macedo Haddad', 'Coleção questãoes da nossa época: O direito à velhice: os aposentádos e a previdência social - Volume 10', NULL, '362.60981', '85.249.0482.8', 'São Paulo', 'Cortez', NULL, NULL, '1993', NULL, 'v.10', 'ex.1', 'p.115', 1, '20.00', 0, '003.', NULL, 1),
(953, '2022-01-01', 'José Alberto Couto Maciel', 'Direito do trabalho ao alcance de todos', '34.331', '350', NULL, 'São Paulo', '***', NULL, NULL, '1993', NULL, 'v.1', 'ex.1', 'p.126', 1, '20.00', 0, '003.', NULL, 1),
(954, '2022-01-01', 'Carlos Takahashi', 'Os 3 B\'s do Cerimonial: Introdução às Normas do Cerimonial Público Brasileiro', NULL, '395.5', NULL, '***', '***', NULL, NULL, NULL, NULL, 'v.1', 'ex.2', 'p.256', 1, '20.00', 0, '003.', NULL, 1),
(955, '2022-01-01', 'Donaldo J. Fellippe e Samuel Andrade Jr.', 'Petições Cíveis - Volume 1', NULL, '347', NULL, 'São Paulo', 'Julex livros', NULL, NULL, '1989', NULL, 'v.1', 'ex.1', 'p.98', 1, '10.00', 0, '003.', NULL, 1),
(956, '2022-01-01', 'Bonifácio de Andrada', 'A perda do Mandato: Câmara dos Deputados e Supremo Tribunal Federal', '342.534.6(81)', '320', '978.85.384.0311.1', 'Belo Horizonte', 'Del Rey', NULL, NULL, '2013', '1 ed.', 'v.1', 'ex.1', 'p.68', 1, '20.00', 0, '003.', NULL, 1),
(957, '2022-01-01', 'Brasil', 'Coletânica Básica Penal: Dispositivos Constitucionais Pertinentes', NULL, '345', '978.85.7018.323.1', 'Brasília-DF', 'Senado Federal', NULL, NULL, '210', NULL, 'v.1', 'ex.1', 'p.321', 1, '15.00', 0, '003.', NULL, 1),
(958, '2022-01-01', 'Bonifácio de Andrada', 'Elementos de Ciência Política', NULL, '320', NULL, 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '2002', NULL, 'v.1', 'ex.1', 'p.85', 1, '10.00', 0, '003.', NULL, 1),
(959, '2022-01-01', 'Fernando Henrique Cardoso e Carlos Estevam Martins', 'Coleção Biblioteca Universitária: Série 2: Ciências Sociais - Volume 53 : Política e Sociedade - Vol.1', NULL, '320', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1979', NULL, 'v.1', 'ex.1', 'p.433', 1, '10.00', 0, '003.', NULL, 1),
(960, '2022-01-01', 'Friedrich Engels', 'O socialismo jurídico', '340.12/ 34.(091)', '330', NULL, 'São Paulo', 'Ensaio Editora', NULL, NULL, '1991', NULL, 'v.1', 'ex.1', 'p.77', 1, '15.00', 0, '003.', NULL, 1),
(961, '2022-01-01', 'Gilberto Dimenstein', 'As armadilhas do poder, Bastidores da imprensa', NULL, '384', NULL, 'São Paulo', 'Summus', NULL, NULL, '1990', NULL, 'v.1', 'ex.1', 'p.155', 1, '15.00', 0, '003.', NULL, 1),
(962, '2022-01-01', 'Bárbara Freitag', 'Coleção Polêmicas do nosso tempo - Volume 26 : Política educacional e indústria cultural', NULL, '379', '85.249.0095.4', 'São Paulo', 'Cortez', NULL, NULL, '1987', NULL, 'v.26', 'ex.1', 'p.86', 1, '15.00', 0, '003.', NULL, 1),
(963, '2022-01-01', 'José de Ribamar Barreiros Soares', 'Série Conhecendo o legislativo - Nº 1 - O que faz uma comissão parlamentar de inquérito', '342.537.7(81)', '320', '85.7365.363.9', 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '2004', NULL, 'v.1', 'ex.1', 'p.90', 1, '15.00', 0, '003.', NULL, 1),
(964, '2022-01-01', 'Vários autores', 'Improbidade Administrativa - Questões polêmicas e atuais', NULL, '658.408', '85.7420.297.5', 'São Paulo', 'Malheiros', NULL, NULL, '2001', '1 ed.', NULL, 'ex.1', 'p.294', 1, '15.00', 0, '003.', NULL, 1),
(965, '2022-01-01', 'Vicente Ráo', 'O direito e a vida dos direitos - Volume 1', '34', '340.1', '85.203.1400.7', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '1991', '1 ed.', 'v.1', 'ex.1', 'p.519', 1, '15.00', 0, '003.', NULL, 1),
(966, '2022-01-01', 'Vicente Ráo', 'O direito e a vida dos direitos - Volume 2', NULL, '341.1', '85.203.0770.1', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '1991', '1 ed.', 'v.2', 'ex.1', 'p.984', 1, '15.00', 0, '003.', NULL, 1),
(967, '2022-01-01', 'José Rogério Cruz e Tucci', 'Ação Monetária: Lei 9.079 de 14.07.1995', '347.922(094)', '341.46', '85.203.2067.8', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '2001', '1 ed.', 'v.1', 'ex.1', 'p.142', 1, '20.00', 0, '003.', NULL, 1),
(968, '2022-01-01', 'São Paulo', 'Estatuto dos Funcionários públicos civis do Estado de São Paulo (atualilzada em 2000)', '35.08(816.1)(094)', '348', '85.7283.269.6', 'Bauru-SP', 'Edipro', NULL, NULL, '2000', '1 ed.', 'v.1', 'ex.1', 'p.104', 1, '20.00', 0, '003.', NULL, 1),
(969, '2022-01-01', 'TORNAGHI, Newton ', 'Contabilidade para não contadores: Noções elementáres (Volume 1)', NULL, '657', NULL, 'Rio de Janeiro', '***', NULL, NULL, '1969', '1 ed.', 'v.1', 'ex.1', 'p.42', 1, '20.00', 0, '003.', NULL, 1),
(970, '2022-01-01', 'TORNAGHI, Newton ', 'Contabilidade para não contadores: Análise de Balanço (Volume 2)', NULL, '657', NULL, 'Rio de Janeiro', '***', NULL, NULL, '1969', '1 ed.', 'v.2', 'ex.1', 'p.56', 1, '20.00', 0, '003.', NULL, 1),
(971, '2022-01-01', 'TORNAGHI, Newton ', 'Contabilidade para não contadores: Exercícios 1ª Série - (Volume 3)', NULL, '657', NULL, 'Rio de Janeiro', '***', NULL, NULL, '1969', '1 ed.', 'v.3', 'ex.1', 'p.70', 1, '20.00', 0, '003.', NULL, 1),
(972, '2022-01-01', 'TORNAGHI, Newton ', 'Contabilidade para não contadores: Exercícios 4ª série (Volume 6)', NULL, '657', NULL, 'Rio de Janeiro', '***', NULL, NULL, '1969', '1 ed.', 'v.6', 'ex.1', 'p.53', 1, '20.00', 0, '003.', NULL, 1),
(973, '2022-01-01', 'MELGOSA, Julian / BORGES, Michelson ', 'O poder da esperança: Segredos do bem-estar emocional', NULL, '152', '978.85.345.2400.1', 'Tatuí-sp', 'Casa Publicadora Brasileira', NULL, NULL, '2017', '1 ed.', NULL, 'ex.1', 'p.96', 1, '20.00', 0, '003.', NULL, 1),
(974, '2022-01-01', '***', 'Novas Tábuas de Logarítimos', NULL, '512.922', NULL, 'São Paulo', '***', NULL, NULL, '1954', '1 ed.', 'v.1', 'ex.1', 'p.174', 1, '20.00', 0, '003.', NULL, 1),
(975, '2022-01-01', 'KNAPIK, Janete ', 'Gestão de pessoas e talentos', NULL, '658.3', '978.85.65704.76.2', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.354', 1, '20.00', 0, '003.', NULL, 1),
(976, '2022-01-01', 'NICHOLLS, David ', 'Um dia', '821.11.3', '823', '978.85.8057.159.2', 'Rio de Janeiro', 'Intrínseca Editora', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.320', 1, '20.00', 0, '003.', NULL, 1),
(977, '2022-01-01', 'Fundação Konrad Adenauer', 'Democracia , diálogo e cooperação: a fundação Konrad Adenauer no Brasil: 50 anos', NULL, '320.981', '978.85.7504.229.8', 'Rio de Janeiro', 'Fundação Konrad Adenauer', NULL, NULL, '2019', '1 ed.', 'v.1', 'ex.1', 'p.224', 1, NULL, 1, '004.', NULL, 1),
(978, '2022-01-01', 'ENI, José Virgíllio Lopes ', 'Project finance: financimanto com foco em empreendimentos (parcerias público privadas, leveraged by-outs e outras figuras afins)', NULL, '658.404', '978.85.020.6027.2', 'São Paulo', 'Saraiva', NULL, NULL, '2007', '1 ed.', NULL, 'ex.2', 'p.442', 1, '10.00', 0, '003.', NULL, 1),
(979, '2022-01-01', 'PESCE, Vince', 'Manual completo de vendedor profissional', '658.85', '658.85', '85.01.03835.0', 'Rio de Janeiro', 'Saraiva', NULL, NULL, '1994', '2 ed.', NULL, 'ex.1', 'p.316', 1, '10.00', 0, '003.', NULL, 1),
(980, '2022-01-01', 'Regina Luna Santos Cardoso', 'Elaboração de indicadores de desenpenho institucional e organizacional no setor público: técnicas e ferramentas', '35.65.011', '351', NULL, 'São Paulo', '***', NULL, NULL, '1999', '1 ed.', 'v.1', 'ex.1', 'p.80', 1, '20.00', 0, '003.', NULL, 1),
(981, '2022-01-01', 'RAW, Isaias ', 'Aventuras da microbiologia', NULL, '570', '85.86179.33.7', 'São Paulo', '***', NULL, NULL, '2002', '1 ed.', NULL, 'ex.1', 'p.171', 1, '25.00', 0, '003.', NULL, 1),
(982, '2022-01-01', 'Silvana Krauser, Carlos Machado e Luis Felipe Miguel (Org.)', 'Coligações e disputas eleitorais na Nova República: aportes teóricos-metodológicos, tendências e estudos de caso', NULL, '324.81', '978.85.7504.204.5', 'Rio de Janeiro', 'Fundação Konrad Adenauer', NULL, NULL, '2017', '1 ed.', 'v.1', 'ex.1', 'p.400', 1, '25.00', 0, '003.', NULL, 1),
(983, '2022-01-01', 'CAMARGO, Camila ', 'Análise de investimentos e demonstrativos financeiros', NULL, '658.152', '978.85.99583.15.9', 'Curitiba-PR', 'Ibpex', NULL, NULL, '2007', '1 ed.', NULL, 'ex.1', 'p.256', 1, '25.00', 0, '003.', NULL, 1),
(984, '2022-01-01', 'CASTANHEIRA, Nelson Pereira ', 'Matemática financeira aplicada', NULL, '650.015', '978.85.8212.002.6', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.275', 1, '20.00', 0, '003.', NULL, 1),
(985, '2022-01-01', 'Alice A. Amsdem', 'A ascensão do \"resto\": os desafios ao Ocidente de economia com industrialização tardia', '338.45(1-772)', '338.90091724', '978.85.7139.916.7', 'São Paulo', 'UNESP - Universidade Estadual Paulista', NULL, NULL, '2009', '1 ed.', 'v.1', 'ex.1', 'p.586', 1, '20.00', 0, '003.', NULL, 1),
(986, '2022-01-01', 'LUECKE, Ricchard ', 'Ferramentas para empreendedores: ferramentas tecnicas para desenvolver e expandir sues negócios', '65.016.2', '658.11', '978.85.01.07495.9', 'Rio de Janeiro', 'Record', NULL, NULL, '2007', '1 ed.', NULL, 'ex.1', 'p.292', 1, '30.00', 0, '003.', NULL, 1),
(987, '2022-01-01', 'Andrew Smith', 'Ferramentas mentais para traders: vença suas emoções e ganhe nos investimentos', '336.76', '332.6', '85.352.1927.7', 'Rio de Janeiro', 'Elsevier', NULL, NULL, '205', '1 ed.', 'v.1', 'ex.1', 'p.205', 1, '30.00', 0, '003.', NULL, 1),
(988, '2022-01-01', 'Richards Aldous', 'Reagan e Thatcher: uma relação dificil', '327(73)(41)', '327.73041', '978.85.01.09842.9', 'Rio de Janeiro', 'Record', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.334', 1, '30.00', 0, '003.', NULL, 1),
(989, '2022-01-01', 'BUSH, George W.', 'Momentos de Decisão', NULL, '973', '978.85.7679.640.4', 'Barueri-SP', 'Novo Século Editora', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.566', 1, '30.00', 0, '003.', NULL, 1),
(990, '2022-01-01', 'Ivone Zeger', 'Como a lei resolve questões da família', '347.6(81)', '347', '978.85.88641.06.8', 'São Paulo', 'Mescla', NULL, NULL, '2007', '1 ed.', 'v.1', 'ex.1', 'p.146', 1, '30.00', 0, '003.', NULL, 1),
(991, '2022-01-01', 'Peter Dicken', 'Mudança global: mapeando as novas fronteiras da economia mundial', '005.44', '337', '978.85.7780.626.3', 'Porto Alegre', 'Bookman', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.632', 1, '30.00', 0, '003.', NULL, 1),
(992, '2022-01-01', 'CZINKOTA, Michael R. ', 'Marketing internacional', NULL, '658.848', '978.85.221.0609.7', 'São Paulo', 'Cengage Learning', NULL, NULL, '2008', '1 ed.', NULL, 'ex.1', 'p.626', 1, '30.00', 0, '003.', NULL, 1),
(993, '2022-01-01', 'Clécio Campolina Diniz, Mauro Borges Lemos (Org.)', 'Economia e território', '332.1', '330.91', '85.7041.476.5', 'Belo Horizonte', 'UFMG', NULL, NULL, '2005', '1 ed.', 'v.1', 'ex.1', 'p.578', 1, '30.00', 0, '003.', NULL, 1),
(994, '2022-01-01', 'PRAHALAD, C.K. ', 'A riqueza na base da pirâmide: como erradicar a pobreza como o lucro', '658.114:316.663', '658.8', '85.363.0544.4', 'Porto Alegre', 'Bookman', NULL, NULL, '2005', '1 ed.', NULL, 'ex.1', 'p.391', 1, '30.00', 0, '003.', NULL, 1),
(995, '2022-01-01', 'Jean Demine', 'Avaliação de Bancos & gestão baseada no valor: aperfeiçoamento de depósitos e de empréstimos, avaliação de desempenho e gestão de riscos', '978.85.224.6084.7', '332', '33210681', 'São Paulo', 'Atlas S.A.', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.496', 1, '30.00', 0, '003.', NULL, 1),
(996, '2022-01-01', 'Samantha Power', 'O homem que queria salvar o mundo: uma biografia de Sergio Vieira de Mello', NULL, '327.2092', '978.85.359.1284.5', 'São Paulo', 'Companhia das Letras', NULL, NULL, '2008', '1 ed.', 'v.1', 'ex.1', 'p.667', 1, '30.00', 0, '003.', NULL, 1),
(997, '2022-01-01', 'Alice Schroeder', 'O bola de neve: Warren Buffett e o negócio da Vida', '336.76', '332.6', '978.85.7542.440.7', 'Rio de Janeiro', 'Sextante', NULL, NULL, '2008', '1 ed.', 'v.1', 'ex.1', 'p.959', 1, '30.00', 0, '003.', NULL, 1),
(998, '2022-01-01', 'Patrick Denaud', 'Iraque, a guerra permanente: a posição do regime iraquiano', '94(567)', '956.704', '85.7303.410.6', 'Rio de Janeiro', 'Qualitymark', NULL, NULL, '2003', '1 ed.', NULL, 'ex.1', 'p.256', 1, '30.00', 0, '003.', NULL, 1),
(999, '2022-01-01', 'CASAROTTO FILHO, Nelson', 'Análise de investimentos : matemática financeira, engenharia econômica, tomada de decisão, estratégia empresarial', NULL, '658.152', '85.224.2572.8', 'São Paulo', 'Atlas S.A.', NULL, NULL, '2000', '1 ed.', NULL, 'ex.1', 'p.458', 1, '30.00', 0, '003.', NULL, 1),
(1000, '2022-01-01', 'Lee A. Lacocca', 'Lee Lacocca com Catherine Whitney', NULL, '303.340973', '978.85.352.2367.5', 'Rio de Janeiro', 'Elsevier', NULL, NULL, '2007', '1 ed.', 'v.1', 'ex.1', 'p.230', 1, '30.00', 0, '003.', NULL, 1),
(1001, '2022-01-01', 'SLOTERDIJK, Peter ', 'Crítica da razão crítica', '17', '183.4', '978.85.7448.209.5', 'São Paulo', 'Estação Liberdade', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.720', 1, '30.00', 0, '003.', NULL, 1),
(1002, '2022-01-01', 'Eduardo Biacchi Gomes', 'Blocos Econômicos : solução de controvérsias', '347.7', '346.07', '978.85.362.2917.1', 'Curitiba-PR', 'Jurua', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.336', 1, '30.00', 0, '003.', NULL, 1),
(1003, '2022-01-01', 'SANDEL, Michael J.', 'O que o dinheiro não compra: os limites morais do mercado', '174', '174', '978.85.200.1148.5', 'Rio de Janeiro', 'Civilização Brasileira', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.237', 1, '30.00', 0, '003.', NULL, 1),
(1004, '2022-01-01', 'FIANI, Ronaldo ', 'Teoria dos jogos: para cursos de administração e economia', '519.83', '519.3', '85.352.2073.9', 'Rio de Janeiro', 'Elsevier', NULL, NULL, '2006', '1 ed.', NULL, 'ex.1', 'p.388', 1, '30.00', 0, '003.', NULL, 1),
(1005, '2022-01-01', 'Miriam Leitão', 'Saga Brasileira', '335', '332', '978.85.01.08871.0', 'Rio de Janeiro', 'Record', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.475', 1, '30.00', 0, '003.', NULL, 1),
(1006, '2022-01-01', 'BURRUS,  Daniel', 'O futuro como um bom negócio: como as percepções certas sobre o futuro determinam oportunidades únicas de negócios', '65.011.4', '658.409', '978.85.352.4570.7', 'Rio de Janeiro', 'Elsevier', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p.279', 1, '30.00', 0, '003.', NULL, 1),
(1007, '2022-01-01', 'Ian Bremmer', 'O fim das lideranças mundiais: o que muda com o G-Zero, onde nenhum país mais está no comando', '330', '330.9', '978.85.02.20063.0', 'São Paulo', 'Saraiva', NULL, NULL, '2013', '1 ed.', 'v.1', 'ex.1', 'p.238', 1, '30.00', 0, '003.', NULL, 1),
(1008, '2022-01-01', 'Christopher W. Morris', 'Um ensaio sobre o Estado moderno', NULL, '320.1', '85.7629.034.0', 'São Paulo', 'Landy Editora', NULL, NULL, '2005', '1 ed.', 'v.1', 'ex.1', 'p.426', 1, '30.00', 0, '003.', NULL, 1),
(1009, '2022-01-01', 'GITOMER, Jeffrey ', 'A Bíblia de Vendas', NULL, '658.4', '85.89384.65.9', 'São Paulo', 'M.Books do Brasil', NULL, NULL, '2005', '1 ed.', NULL, 'ex.1', 'p.345', 1, '30.00', 0, '003.', NULL, 1),
(1010, '2022-01-01', 'SATINOVER, Jeffrey ', 'O cérebro quântico: as novas descobertas da neurociência e a próxima geração de seres humanos', NULL, '612.82', '978.85.7657.065.3', 'São Paulo', 'Aleph Editora', NULL, NULL, '2008', '1 ed.', NULL, 'ex.1', 'p.356', 1, '15.00', 0, '003.', NULL, 1),
(1011, '2022-01-01', 'MORRIS, Robert D. ', 'A morte azul: o intrigante perigo do passado e do presente na água que você bebe', NULL, '614', '978.85.62844.04.1', 'Campinas-SP', 'Saberes Editora', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p.413', 1, '15.00', 0, '003.', NULL, 1),
(1012, '2022-01-01', 'Albert Gore', 'Uma verdade inconveniente: o que devemos saber (e fazer) sobre o aquecimento global', NULL, '363.73874', '85.204.2581.x', 'Barueri-SP', 'Manole', NULL, NULL, '2006', '1 ed.', 'v.1', 'ex.1', 'p.328', 1, '15.00', 0, '003.', NULL, 1),
(1013, '2022-01-01', 'RAZZOLINI FILHO, Edelvino', 'Transporte modais', NULL, '658.788', '978.85.8212.197.9', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.317', 1, '15.00', 0, '003.', NULL, 1),
(1014, '2022-01-01', 'CAIÇARA JÚNIOR, Cícero ', 'Sistemas integrados de gestão: ERP: uma aboradagem gerencial', NULL, '658.403', '978.85.8212.422.2', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.207', 1, '15.00', 0, '003.', NULL, 1),
(1015, '2022-01-01', 'LUZ, Érico Eleoterio da ', 'Controladoria corporativa', NULL, '658.401', '978.85.8212.910.4', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2014', '2 ed.', NULL, 'ex.2', 'p.151', 1, '15.00', 0, '003.', NULL, 1);
INSERT INTO `librarycollection` (`id`, `registrationDate`, `author`, `title`, `cdu`, `cdd`, `isbn`, `local`, `publisher_edition`, `number`, `month`, `year`, `edition`, `volume`, `copyNumber`, `pageNumber`, `typeAcquisitionId`, `price`, `prohibitedSale`, `provider`, `exclusionInfoTerm`, `registeredByUserId`) VALUES
(1016, '2022-01-01', 'Jorge Luiz Bernardi', 'A organização municipal e a polítia urbana', NULL, '352.160981', '978.85.7838.903.01', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.467', 1, '15.00', 0, '003.', NULL, 1),
(1017, '2022-01-01', 'BRANCO, Miguel de Sousa Borges Leal CastelO', 'Apontamentos biográficos de algusn piauienses ilustres e de outras pessoas notáveis que ocuparam cargos importentes na província do Piuí', NULL, '920.981', '978.85.64231.06.1', 'Teresina - PI', 'Academia Piauiense de Letras', NULL, NULL, '2012', '1 ed.', 'V.3', 'ex.1', 'p.158', 1, '15.00', 0, '003.', NULL, 1),
(1018, '2022-01-01', 'MIRANDA, Reginaldo ', 'Coleção Centenário : São Gonçalo da Regeneração: manchas e contramanchas de uma comunidade sertaneja: da aldeia indígena aos tempos atuais - Vol 6', NULL, '981.012', '978.85.64231.12.2', 'Teresina - PI', 'Academia Piauiense de Letras', NULL, NULL, '2012', '1 ed.', 'v.6', 'ex.1', 'p.462', 1, '15.00', 0, '003.', NULL, 1),
(1019, '2022-01-01', 'MIRANDA, Reginaldo ', 'Coleção Centenário : Aldeamento dos Acoroás - Vol 7', NULL, '981.012', '978.85.64231.14.6', 'Teresina - PI', 'Academia Piauiense de Letras', NULL, NULL, '2012', '1 ed.', 'v.7', 'ex.1', 'p.84', 1, '15.00', 0, '003.', NULL, 1),
(1020, '2022-01-01', 'Susumo Harada e Vera Lúcia', 'Haikai imagens para ver e sentir', NULL, '90 (ESTANTE)', NULL, '***', '***', NULL, NULL, NULL, '1 ed.', 'v.1', 'ex.1', NULL, 1, '15.00', 0, '003.', NULL, 1),
(1021, '2022-01-01', 'MACHADO JÚNIOR, Armando Marcondes ', 'Resgate Histórico: Divisão do Estado de São Paulo', '352(81)', '921', NULL, 'São Paulo', 'Mageart', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p.1005', 1, '15.00', 0, '003.', NULL, 1),
(1022, '2022-01-01', 'RAZZOLINI FILHO, Edelvino ', 'Gerência de serviços para a gestão comercial: um enfoque prático', NULL, '658.4012', '978.85.7838.689.4', 'Curitiba-PR', 'Ibpex', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p.194', 1, '15.00', 0, '003.', NULL, 1),
(1023, '2022-01-01', 'RAZZOLINI JÚNIOR, Edelvino', 'Gerência de produtos para a gestão comercial: um enfoque prático', NULL, '658.578', '978.85.7838.668.9', 'Curitiba-PR', 'Ibpex', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p.154', 1, '15.00', 0, '003.', NULL, 1),
(1024, '2022-01-01', 'CANFIELD, Jack ', 'Histórias para aquecer o coração dos pais', '159.947', '158.1', '85.7542.073.9', 'Rio de Janeiro', 'Sextante', NULL, NULL, '2003', '1 ed.', NULL, 'ex.1', 'p.128', 1, '15.00', 0, '003.', NULL, 1),
(1025, '2022-01-01', 'Ernesto Lozardo', 'Globalização: a certeza imprevisível das nações', NULL, '337', NULL, 'São Paulo', '***', NULL, NULL, '2007', '1 ed.', 'v.1', 'ex.1', 'p.607', 1, '15.00', 0, '003.', NULL, 1),
(1026, '2022-01-01', 'Joaquim Cabral Netto', 'Guia para concurso do Ministériio Público', '34.(81)(079.1)/ 34(81)', '340.07', NULL, 'Belo Horizonte', 'Del Rey', NULL, NULL, '1996', '1 ed.', 'v.1', 'ex.1', 'p.813', 1, '15.00', 0, '003.', NULL, 1),
(1027, '2022-01-01', 'INNOCENTE, Antonio Wilson', 'Araras, Câmara e Vereadores de 1873 a 2016', NULL, '921', NULL, 'Araras-SP', 'Câmara ', NULL, NULL, '2015', 'ed.1', NULL, 'ex.1', 'p.76', 1, '15.00', 0, '003.', NULL, 1),
(1028, '2022-01-01', 'Cristina Buarque de Holanda (Org.)', 'A Constituição de 88: trinta anos depois', '342.4(81)', '341.24981', '978.85.8480.138.7', 'Curitiba-PR', 'UNIVERSIDADES', NULL, NULL, '2018', '1 ed.', 'v.1', 'ex.1', 'p.400', 1, '15.00', 0, '003.', NULL, 1),
(1029, '2022-01-01', 'Associação Brasileira de Normas Técnicas', 'Normas para desenho técnico', NULL, '744.4', NULL, 'Porto Alegre', 'Globo', NULL, NULL, '1983', '1 ed.', NULL, 'ex.1', 'p.332', 1, '15.00', 0, '003.', NULL, 1),
(1030, '2022-01-01', 'Roberto Antonio Lando', 'Amplifiador operacional', NULL, '621.381535', NULL, 'São Paulo', '***', NULL, NULL, '1983', '1 ed.', 'v.1', 'ex.1', 'p.269', 1, '15.00', 0, '003.', NULL, 1),
(1031, '2022-01-01', 'Roberto Luis Luchi Demo', 'Embargos de declaração: aspectos processuais e procedimentais', '347.952.5', '347', '85.309.1506.2', 'Rio de Janeiro', 'Forense Editora', NULL, NULL, '2003', '1 ed.', 'v.1', 'ex.1', 'p.201', 1, '15.00', 0, '003.', NULL, 1),
(1032, '2022-01-01', 'Sylvio Capanema de Souza', 'Da ação de despejo', '347.922.6(81)', '347', NULL, 'Rio de Janeiro', 'Forense Editora', NULL, NULL, '1997', '1 ed.', 'v.1', 'ex.1', 'p.446', 1, '15.00', 0, '003.', NULL, 1),
(1033, '2022-01-01', 'Armando João Dalla Costa', 'Estratégias e negócios das empresas diante da internacionalização ', NULL, '337', '978.85.8212.371.3', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.216', 1, '15.00', 0, '003.', NULL, 1),
(1034, '2022-01-01', 'Erival da Silva Oliveira', 'Prática constitucional', '342(81)', '342', '978.85.203.5292.2', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '2014', '1 ed.', 'v.1', 'ex.1', 'p.683', 1, '15.00', 0, '003.', NULL, 1),
(1035, '2022-01-01', 'Carlos Alberto Júlio', 'A Economia do Cedro: uma revolução em curso: defina o seu papel, as oportunidades e as possibilidades do Brasil neste mundo', NULL, '338.5024658', '978.85.6232.812.1', 'São Paulo', 'Virgiliae Editora', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.239', 1, '15.00', 0, '003.', NULL, 1),
(1036, '2022-01-01', 'MASSARANI, EmanueL von Lauenstein ', 'Alessandro Guisberti', NULL, '750', NULL, 'São Paulo', 'Impressão Oficial', NULL, NULL, NULL, '1 ed.', NULL, 'ex.2', 'p.123', 1, '15.00', 0, '003.', NULL, 1),
(1037, '2022-01-01', 'MASSARANI, EmanueL von Lauenstein ', 'Alessandro Guisberti', NULL, '750', NULL, 'São Paulo', 'Impressão Oficial', NULL, NULL, NULL, '1 ed.', NULL, 'ex.3', 'p.123', 1, '15.00', 0, '003.', NULL, 1),
(1038, '2022-01-01', 'Maria Amélia Máximo de Araujo', 'Extensão Universitária um laboratório social', NULL, '378.103', '978.85.7983.179.9', 'São Paulo', 'Cultura Acadêmica', NULL, NULL, '2011', NULL, NULL, 'ex.1', 'p.82', 1, '15.00', 0, '003.', NULL, 1),
(1039, '2022-01-01', 'Stephen Baker', 'Numerati: conheça os mumerati: eles já conhecem você', '316.422.44', '303.483', '978.85.02.07892.5', 'São Paulo', 'Saraiva', NULL, NULL, '2009', NULL, 'v.1', 'ex.1', 'p.255', 1, '15.00', 0, '003.', NULL, 1),
(1040, '2022-01-01', 'DARCY, Miguel / CARDOSO, Fernando Henrique', 'A soma e o resto: um olhar sobre a vida aos 80 anos', '929.32(81)', '923.1', '978.85.200.1084.6', 'Rio de Janeiro', 'Civilização Brasileira', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p.191', 1, '15.00', 0, '003.', NULL, 1),
(1041, '2022-01-01', 'Assembleia Legislativa de SP', 'Constituição do Estado de São Paulo (Atualizada em 2016)', '342.4(815.6)', '348', NULL, 'São Paulo', 'Imprensa Oficial', NULL, NULL, '2006', NULL, 'v.1', 'ex.1', 'p.541', 1, '15.00', 0, '003.', NULL, 1),
(1042, '2022-01-01', 'A Igreja de Jesus Cristo dos Santos dos Últimos Dias', 'Preincípios do Evangelho', NULL, '289', NULL, '***', '***', NULL, NULL, '1980', NULL, 'v.1', 'ex.1', 'p.362', 1, '15.00', 0, '003.', NULL, 1),
(1043, '2022-01-01', 'SCHUBERT, Suely Caldas ', 'O semeador de estrelas', NULL, '133.9', '85.7347.850.x', 'Salvador-BA', 'Alvorada Editora', NULL, NULL, '1989', '1 ed.', NULL, 'ex.1', 'p.325', 1, '15.00', 0, '003.', NULL, 1),
(1044, '2022-01-01', 'HERSKOWITZ, Irwin Heman ', 'Princípios básicos de genética molecular', NULL, '576', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1971', '1 ed.', NULL, 'ex.1', 'p.340', 1, '15.00', 0, '003.', NULL, 1),
(1045, '2022-01-01', 'Câmara Municipal de Hortolândia', 'Lei Orgânca do Município de Hortolândia', NULL, '348', NULL, 'Hortolândia', 'Câmara ', NULL, NULL, '2009', '1 ed.', 'v.1', 'ex.1', 'p.100', 1, NULL, 1, '003.', NULL, 1),
(1046, '2022-01-01', 'Senado Federal', 'Lei de Responsabilidade fiscal ', NULL, '348', NULL, 'Brasília-DF', 'Interlegis Edições', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.73', 1, NULL, 1, '003.', NULL, 1),
(1047, '2022-01-01', 'Senado Federal', 'Lei de Responsabilidade fiscal ', NULL, '348', NULL, 'Brasília-DF', 'Interlegis Edições', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.73', 1, NULL, 1, '003.', NULL, 1),
(1048, '2022-01-01', 'Senado Federal', 'Lei de Diretrizes e bases da educação ', NULL, '348', NULL, 'Brasília-DF', 'Interlegis Edições', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.93', 1, NULL, 1, '003.', NULL, 1),
(1049, '2022-01-01', 'Senado Federal', 'Lei de Diretrizes e bases da educação ', NULL, '348', NULL, 'Brasília-DF', 'Interlegis Edições', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.2', 'p.93', 1, NULL, 1, '003.', NULL, 1),
(1050, '2022-01-01', 'Joseph E. Stiglitz', 'O mundo em queda livre: Os Estados Unidos e o mercado e o naufrágio da economia mundial', NULL, '330.973', '978.85.359.1754.3', 'São Paulo', 'Companhia das Letras', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.575', 1, '15.00', 0, '003.', NULL, 1),
(1051, '2022-01-01', 'COSTA, Oswaldo Luiz do Valle ', 'Análise de risco e retorno em investimentos financeiros', '658.15', '658.15', '85.204.204.2072.9', 'Barueri-SP', 'Manole', NULL, NULL, '2005', '1 ed.', NULL, 'ex.1', 'p.239', 1, '15.00', 0, '003.', NULL, 1),
(1052, '2022-01-01', 'SHALDON, Sidney ', 'Escrito nas estrelas', '820(76).3', '813', NULL, 'Rio de Janeiro', 'Record', NULL, NULL, '1999', '1 ed.', NULL, 'ex.1', 'p.414', 1, '15.00', 0, '003.', NULL, 1),
(1053, '2022-01-01', 'GASPARETTO, Zíbia ', 'Sem Medo de viver', NULL, '133.9', '85.85872.37.3', 'São Paulo', 'Vida e conciência Editora', NULL, NULL, '1996', '17 ed.', NULL, 'ex.1', 'p.343', 1, '15.00', 0, '003.', NULL, 1),
(1054, '2022-01-01', 'MOIX, Terenci ', 'Não diga que foi um sonho: O romance de Cleópatra e Marco Antônio', NULL, '849.93', '85.250.3387.1', 'São Paulo', 'Globo', NULL, NULL, '2001', '1 ed.', NULL, 'ex.1', 'p.437', 1, '15.00', 0, '003.', NULL, 1),
(1055, '2022-01-01', 'MECLER, Ian', 'O poder de Realização da Cabala', '296.65', '296.16', '978.85.01.09567.1', 'Rio de Janeiro', 'Civilização Brasileira', NULL, NULL, '2011', '1 ed.', NULL, 'ex.2', 'p.154', 1, '15.00', 0, '003.', NULL, 1),
(1056, '2022-01-01', 'FELÍCIO, Cláudia Maria de ', 'Fonoaudiologia nas Desordens Temporomandibulares - uma ação educativa - terapêutica', NULL, '610', NULL, 'São Paulo', 'Pancast Editora', NULL, NULL, '1994', '1 ed.', NULL, 'ex.1', 'p.179', 1, '15.00', 0, '003.', NULL, 1),
(1057, '2022-01-01', 'MONTEIRO, Carlos Augusto ', 'Saúde e nutição das crianças de São Paulo: diagnóstico, contrastes sociais e  tendências', NULL, '614.0981611', '85.271.0059.2', 'São Paulo', 'UNIVERSIDADES', NULL, NULL, '1988', '1 ed.', NULL, 'ex.1', 'p.165', 1, '15.00', 0, '003.', NULL, 1),
(1058, '2022-01-01', 'SALA, Agustí ', 'Wall Street - Êxitos fracassos no mundo dos negócios', NULL, '658.001', '978.972.33.2358.0', 'Lisboa', '***', NULL, NULL, '2007', '1 ed.', NULL, 'ex.1', 'p.238', 1, '15.00', 0, '003.', NULL, 1),
(1059, '2022-01-01', 'Paiva Netto', 'É urgente reeducar', NULL, '370', '978.85.7513.184.8', 'São Paulo', 'Elevação Editora', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.253', 1, '15.00', 0, '003.', NULL, 1),
(1060, '2022-01-01', 'Yvete Flácio da Costa (Org.)', 'Questões atuais de direito e processo', NULL, '340', '978.85.7818.031.7', 'Franca-SP', 'UNESP - Universidade Estadual Paulista', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.2', 'p.211', 1, '15.00', 0, '003.', NULL, 1),
(1061, '2022-01-01', 'Ian Bremmer', 'O fim do livre mercado: quem vence a guerra ente estado e corporações?', '330.142.1', '330.122', '978.85.02.11748.8', 'São Paulo', 'Saraiva', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.198', 1, '15.00', 0, '003.', NULL, 1),
(1062, '2022-01-01', 'Senado Federal', 'Estatuto da Cidade', NULL, '348', NULL, 'Brasília-DF', 'Secretarias ', NULL, NULL, '2008', '1 ed.', 'v.1', 'ex.1', 'p.102', 1, '15.00', 0, '003.', NULL, 1),
(1063, '2022-01-01', 'Senado Federal', 'Coleção Ambiental: Volulme 6 - Atmosfera, desmatamento, poluição e camada de ozônio', NULL, '348', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2007', '1 ed.', 'v.1', 'ex.1', 'p.191', 1, '15.00', 0, '003.', NULL, 1),
(1064, '2022-01-01', 'Enio Freitas', 'A alma imortal: uma visão mística e antopológica', '572.1', '301', NULL, 'Porto Alegre', 'AGE Editora', NULL, NULL, '2002', '1 ed.', 'v.1', 'ex.1', 'p.133', 1, '15.00', 0, '003.', NULL, 1),
(1065, '2022-01-01', 'Brasil', 'Código tributário nacional e Constituição Federal', '34.336.2(81)(094.4)', '348', NULL, 'São Paulo', 'Saraiva', NULL, NULL, '2007', '1 ed.', 'v.1', 'ex.1', 'p.227', 1, '15.00', 0, '003.', NULL, 1),
(1066, '2022-01-01', 'Roberto Barcellos de Magalhães', 'Comentários à Constituição Federal de 1988 - Volume 1 (Art.1º a 91)', NULL, '348', NULL, 'Rio de Janeiro', 'Lumen Juris Editora', NULL, NULL, '1997', '1 ed.', 'v.1', 'ex.1', 'p.401', 1, '15.00', 0, '003.', NULL, 1),
(1067, '2022-01-01', 'Roberto Barcellos de Magalhães', 'Comantários à Constituição Federal de 1988 - Volume 2', NULL, '348', NULL, 'Rio de Janeiro', 'Lumen Juris Editora', NULL, NULL, '1997', '1 ed.', 'v.2', 'ex.1', 'p.394', 1, '15.00', 0, '003.', NULL, 1),
(1068, '2022-01-01', 'Brasil', 'Constituição da República Federativa do Brasil - 1988 (Atualizada em 2003)', '342.4(81)', '348', '85.02.04645.4', 'São Paulo', 'Saraiva', NULL, NULL, '2004', '1 ed.', 'v.1', 'ex.1', 'p.386', 1, '15.00', 0, '003.', NULL, 1),
(1069, '2022-01-01', 'BRAGA, Pedro', 'James Joyce em Brasília', '82.31', 'B869.93', '978.85.7238.417.9', 'Brasília-DF', 'LGE Editora', NULL, NULL, '2009', '1 ed.', NULL, 'ex.2', 'p.110', 1, '15.00', 0, '003.', NULL, 1),
(1070, '2022-01-01', 'ZATS, Lia', 'Pagu', '087.5', '028.5', '85.98750.08.5', 'São Paulo', 'Instituto Callis', NULL, NULL, '2005', '1 ed.', NULL, 'ex.1', 'p.95', 1, '15.00', 0, '004.', NULL, 1),
(1071, '2022-01-01', 'HARADA, Susumo', 'Pequeno ensaio do Paebiru', NULL, '921', NULL, 'São Paulo', '***', NULL, NULL, '2008', '1 ed.', NULL, 'ex.1', 'p.64', 1, '15.00', 0, '003.', NULL, 1),
(1072, '2022-01-01', 'BRAGA, Pedro', 'os amantes de Alcalá', '82.3(81)', 'B869.93', '978.85.7062.959.2', 'Brasília-DF', 'Thesaurus Editora', NULL, NULL, '2010', '1 ed.', NULL, 'ex.2', 'p.107', 1, '15.00', 0, '003.', NULL, 1),
(1073, '2022-01-01', 'GASPARETTO, Luiz Antonio', 'Faça o Certo', NULL, '133.9', '85.85872.24.1', 'São Paulo', 'Vida e conciência Editora', NULL, NULL, '2002', '1 ed.', NULL, 'ex.1', 'p.134', 1, '15.00', 0, '003.', NULL, 1),
(1074, '2022-01-01', 'BRAGA, Pedro', 'Além da imagem visual', '81.22', '150', '978.85.7238.272.0', 'Brasília-DF', 'LGE Editora', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p.87', 1, '15.00', 0, '003.', NULL, 1),
(1075, '2022-01-01', 'SARAMAGO, José ', 'O homem duplicado', NULL, '869.3', '978.85.359.1288.3', 'São Paulo', 'Companhia das Letras', NULL, NULL, '2008', '4 ed.', NULL, 'ex.1', 'p.284', 1, '15.00', 0, '003.', NULL, 1),
(1076, '2022-01-01', 'MAQUIAVEL', 'Coleção a obra prima de cada autor - Volume 2 - O príncipe ', NULL, '808', '857232267.1', 'São Paulo', 'Martin-Claret', NULL, NULL, '1998', '1 ed.', 'v.2', 'ex.1', 'p.214', 1, '15.00', 0, '003.', NULL, 1),
(1077, '2022-01-01', 'PLATÃO', 'Coleção a obra prima de cada autor Volume 36 - A República', NULL, '808', '85.7232.398.8', 'São Paulo', 'Martin-Claret', NULL, NULL, '2006', '1 ed.', 'v.36', 'ex.1', 'p.320', 1, '15.00', 0, '003.', NULL, 1),
(1078, '2022-01-01', 'BECCARIA, Cesare ', 'Coleção a obra prima de cada autor Volume 48 - Dos delitos e das penas', NULL, '808', '85.7232.425.9', 'São Paulo', 'Martin-Claret', NULL, NULL, '2000', '1 ed.', 'v.48', 'ex.1', 'p.128', 1, '15.00', 0, '003.', NULL, 1),
(1079, '2022-01-01', 'NIETZSCHE, Freedrich ', 'Coleção a obra prima de cada autor Volume 50 - O anticristo', NULL, '808', '85.7232.427.5', 'São Paulo', 'Martin-Claret', NULL, NULL, '2007', '1 ed.', 'v.51', 'ex.1', 'p.112', 1, '15.00', 0, '003.', NULL, 1),
(1080, '2022-01-01', 'ARISTÓTELES', 'Coleção a obra prima de cada autor Volume 53 - Ética a Nicômaco', NULL, '808', '85.7232.430.5', 'São Paulo', 'Martin-Claret', NULL, NULL, '2006', '1 ed.', 'v.53', 'ex.1', 'p.240', 1, '15.00', 0, '003.', NULL, 1),
(1081, '2022-01-01', 'SÓFOCLES', 'Coleção a obra prima de cada autor Volume 99 - Édipo Rei Antígona', NULL, '808', '857232488.7', 'São Paulo', 'Martin-Claret', NULL, NULL, '2007', '1 ed.', 'v.99', 'ex.1', 'p.143', 1, '15.00', 0, '003.', NULL, 1),
(1082, '2022-01-01', 'AUSTEN, Jane ', 'Coleção a obra prima de cada autor Volume 243 - Orgulho e preconceito', NULL, '808', '85.7232.630.8', 'São Paulo', 'Martin-Claret', NULL, NULL, '2006', '1 ed.', 'v.1243', 'ex.1', 'p.316', 1, '15.00', 0, '003.', NULL, 1),
(1083, '2022-01-01', 'Marcelino de Carvalho', 'Guia de boas maneiras', NULL, '395.7', '85.04.00096.6', 'São Paulo', 'Nacional', NULL, NULL, '1980', '1 ed.', NULL, 'ex.1', 'p.217', 1, '15.00', 0, '003.', NULL, 1),
(1084, '2022-01-01', 'GONÇALVES, Mariza Lima ', 'Iniciação às práticas científicas', NULL, '001.42', '978.85.349.4181.5', 'São Paulo', 'Paulus Editoras', NULL, NULL, '2015', '1 ed.', NULL, 'ex.1', 'p.140', 1, '15.00', 0, '003.', NULL, 1),
(1085, '2022-01-01', 'SANTOS NETO, Francisco do Espírito', 'Renovando Atitudes', NULL, '133.93', '85.86470.02.3', 'Catanduva', 'Boa Nova Editora', NULL, NULL, '1997', '1 ed.', NULL, 'ex.1', 'p.235', 1, '15.00', 0, '003.', NULL, 1),
(1086, '2022-01-01', 'VIZINCZEY, Stephen ', 'O milionário Inocente', NULL, '813', '85.1.028670.1', 'Rio de Janeiro', 'Record', NULL, NULL, '1985', '1 ed.', NULL, 'ex.1', 'p.434', 1, '15.00', 0, '003.', NULL, 1),
(1087, '2022-01-01', 'BRAGA, Pedro', 'O filósofo da rua do pecado', NULL, 'B869.93', '978.85.7238.475.9', 'Brasília-DF', 'LGE Editora', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p.128', 1, '15.00', 0, '003.', NULL, 1),
(1088, '2022-01-01', 'PIRESH, Silvio ', 'O descaminho de Santiago', NULL, 'B869.93', '978.85.88075.48.1', 'São Paulo', 'Limiar Editora', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p.309', 1, '15.00', 0, '003.', NULL, 1),
(1089, '2022-01-01', 'GUEINIERI FILHO, Santin ', 'Horizontes de Eventos', '821.134.3(81)', 'B869.93', '85.7640.101.0', 'Rio de Janeiro', 'Litteris Editora', NULL, NULL, '2006', '1 ed.', NULL, 'ex.1', 'p.144', 1, '15.00', 0, '003.', NULL, 1),
(1090, '2022-01-01', 'CORRÊIA, Ayrton ', 'Puxando pela memória', '821.134.3(81).8', 'B869.93', '978.85.67532.04.2', 'Itapevi-SP', '***', NULL, NULL, '2016', '1 ed.', NULL, 'ex.1', 'p.206', 1, '15.00', 0, '003.', NULL, 1),
(1091, '2022-01-01', 'BRAGA, Pedro', 'O riso e o trágico', '82.95/ 82.09', '808.07', '978.85.7238.430.8', 'Brasília-DF', 'LGE Editora', NULL, NULL, '2010', '1 ed.', NULL, 'ex.2', 'p.133', 1, '15.00', 0, '003.', NULL, 1),
(1092, '2022-01-01', 'ARONOVICH, Lola ', 'Escreva Lola escreva: Crônicas de cinema', NULL, '791.43', '978.85.7166.136.3', 'São Paulo', 'Com Arte Editora', NULL, NULL, '2013', '1 ed.', NULL, 'ex.1', 'p.176', 1, '15.00', 0, '003.', NULL, 1),
(1093, '2022-01-01', 'SEVERINO, Antônio Joaquim', 'Metodologia do Trabalho científico', NULL, '001.42', '85.249.0059.4', 'São Paulo', 'Cortez', NULL, NULL, '2000', '1 ed.', NULL, 'ex.1', 'p.279', 1, '13.86', 0, '004.', NULL, 1),
(1094, '2022-01-01', 'GIL, Antonio Carlos ', 'Métodos e técnicas de Pesquisa Social', NULL, '001.42', '85.224.1041.0', 'São Paulo', 'Atlas S.A.', NULL, NULL, '1995', '4 ed.', NULL, 'ex.1', 'p.207', 1, '17.00', 0, '004.', NULL, 1),
(1095, '2022-01-01', 'ROCHA, Julio Cesar ', 'Introdução à quimica ambiental', '54/549:574.2.9', '540', '85.363.0467.7', 'Porto Alegre', 'Bookman', NULL, NULL, '2004', '1 ed.', NULL, 'ex.1', 'p.154', 1, '20.00', 0, '003.', NULL, 1),
(1096, '2022-01-01', 'BAUER, Ruben ', 'Gestão da mudança: caos e complexidade nas organizações', NULL, '658.001', '85.224.2153.9', 'São Paulo', 'Atlas S.A.', NULL, NULL, '1999', '1 ed.', NULL, 'ex.1', 'p.253', 1, '20.00', 0, '003.', NULL, 1),
(1097, '2022-01-01', 'POLICE NETO, José  (Org.)', 'Coleção Bairro vivo: São Paulo nasce nos bairros: Bairro Vivo - Norte', '711.4(815.6)', '711.409', '978.85.63163.48.6', 'São Paulo', 'Cia dos Livros', NULL, NULL, '2013', '1 ed.', 'v.1', 'ex.1', 'p.133', 1, '10.00', 0, '003.', NULL, 1),
(1098, '2022-01-01', 'POLICE NETO, José  (Org.)', 'Coleção Bairro vivo: São Paulo nasce nos bairros: Bairro Vivo - Centro', '711.4(815.6)', '711.409', '978.85.63163.45.5', 'São Paulo', 'Cia dos Livros', NULL, NULL, '2013', '1 ed.', 'v.1', 'ex.1', 'p.69', 1, '10.00', 0, '003.', NULL, 1),
(1099, '2022-01-01', 'POLICE NETO, José  (Org.)', 'Coleção Bairro vivo: São Paulo nasce nos bairros: Bairro Vivo - Oeste', '711.4(815.6)', '711.409', '978.85.63163.52.3', 'São Paulo', 'Cia dos Livros', NULL, NULL, '2013', '1 ed.', 'v.3', 'ex.1', 'p.111', 1, '10.00', 0, '003.', NULL, 1),
(1100, '2022-01-01', 'POLICE NETO, José  (Org.)', 'Coleção Bairro vivo: São Paulo nasce nos bairros: Bairro Vivo - Sul', '711.4(815.6)', '711.409', '978.85.63163.53.0', 'São Paulo', 'Cia dos Livros', NULL, NULL, '2013', '1 ed.', 'v.4', 'ex.1', 'p.159', 1, '10.00', 0, '003.', NULL, 1),
(1101, '2022-01-01', 'POLICE NETO, José  (Org.)', 'Coleção Bairro vivo: São Paulo nasce nos bairros: Bairro Vivo - Leste', '711.4(815.6)', '711.409', '978.85.63163.47.9', 'São Paulo', 'Cia dos Livros', NULL, NULL, '2013', '1 ed.', 'v.5', 'ex.1', 'p.223', 1, '10.00', 0, '003.', NULL, 1),
(1102, '2022-01-01', 'Armando Catelli (Org.)', 'Controladoria: uma abordagem de gestão econômica', NULL, '352.8', '85.224.2910.3', 'São Paulo', 'Atlas S.A.', NULL, NULL, '2001', '1 ed.', 'v.1', 'ex.1', 'p.570', 1, '20.00', 0, '003.', NULL, 1),
(1103, '2022-01-01', 'Senado Federal', 'Estatuto do desarmamento', NULL, '348', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.66', 1, NULL, 1, '003.', NULL, 1),
(1104, '2022-01-01', 'Senado Federal', 'Estatuto do desarmamento', NULL, '348', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.66', 1, NULL, 1, '003.', NULL, 1),
(1105, '2022-01-01', 'Senado Federal', 'Segurança Nacional: legislação e doutrina', NULL, '348', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2006', '1 ed.', 'v.1', 'ex.1', 'p.142', 1, NULL, 1, '003.', NULL, 1),
(1106, '2022-01-01', 'Senado Federal', 'Estatuto da Cidade', NULL, '348', NULL, 'Brasília-DF', 'Secretarias ', NULL, NULL, '2008', '1 ed.', 'v.1', 'ex.2', 'p.102', 1, NULL, 1, '003.', NULL, 1),
(1107, '2022-01-01', 'Senado Federal', 'Estatuto da Cidade', NULL, '348', NULL, 'Brasília-DF', 'Secretarias ', NULL, NULL, '2008', '1 ed.', 'v.1', 'ex.3', 'p.102', 1, NULL, 1, '003.', NULL, 1),
(1108, '2022-01-01', 'Senado Federal', 'Estatuto do Idoso (2003)', NULL, '348', NULL, 'Brasília-DF', 'Secretarias ', NULL, NULL, '2003', '1 ed.', 'v.1', 'ex.1', 'p.66', 1, NULL, 1, '003.', NULL, 1),
(1109, '2022-01-01', 'Senado Federal', 'Estatuto do Idoso (2003)', NULL, '348', NULL, 'Brasília-DF', 'Secretarias ', NULL, NULL, '2003', '1 ed.', 'v.1', NULL, 'p.66', 1, NULL, 1, '003.', NULL, 1),
(1110, '2022-01-01', 'Senado Federal', 'Estatuto da Microempresa (Quadro comparativo)', NULL, '348', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2009', '1 ed.', 'v.1', 'ex.1', 'p.158', 1, NULL, 1, '003.', NULL, 1),
(1111, '2022-01-01', 'Senado Federal', 'Estatuto da Microempresa (Quadro comparativo)', NULL, '348', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2009', '1 ed.', 'v.1', 'ex.2', 'p.158', 1, NULL, 1, '003.', NULL, 1),
(1112, '2022-01-01', 'Senado Federal', 'Estatuto da Criança e do Adolescente ', NULL, '348', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2009', '1 ed.', 'v.1', 'ex.1', 'p.205', 1, NULL, 1, '003.', NULL, 1),
(1113, '2022-01-01', 'Senado Federal', 'Estatuto da Criança e do Adolescente ', NULL, '348', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2009', '1 ed.', 'v.1', 'ex.2', 'p.205', 1, NULL, 1, '003.', NULL, 1),
(1114, '2022-01-01', 'RUDIO, Franz Vitor ', 'Introdução ao projeto de pesquisa científica', '001.891', '001.42', '85.326.0027.1', 'Petrópolis', 'Vozes Editora', NULL, NULL, '1986', '1 ed.', NULL, 'ex.1', 'p.128', 1, '20.00', 0, '003.', NULL, 1),
(1115, '2022-01-01', 'TACHIZAWA, Takeshy ', 'Como fazer monografia na prática', NULL, '001.42', '85.225.0260.9', 'Rio de Janeiro', 'FGV Editora', NULL, NULL, '2004', '1 ed.', NULL, 'ex.1', 'p.148', 1, '20.00', 0, '003.', NULL, 1),
(1116, '2022-01-01', 'BARROS, Aidil de Jesus Paes de', 'Projeto de pesquisa: propostas metodológicas', '001.8', '001.42', '85.326.0018.2', 'Petrópolis', 'Vozes Editora', NULL, NULL, '1990', '1 ed.', NULL, 'ex.1', 'p.102', 1, '20.00', 0, '003.', NULL, 1),
(1117, '2022-01-01', 'WEG, Rosana Morais ', 'O texto científico: como fazer  projetos, artigos, relatórios, memoriais, trabalhos de conclusão, teses, dissertações e teses e participações de eventos científicos', NULL, '001.42', '978.85.63163.12.7', 'São Paulo', 'Cia dos Livros', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p.153', 1, '20.00', 0, '003.', NULL, 1),
(1118, '2022-01-01', 'Ministério da Educação', 'Avaliação do ensino superior: encontro internacional', NULL, '378.81', NULL, 'Brasília-DF', 'Secretarias ', NULL, NULL, '1988', '1 ed.', 'v.1', 'ex.1', 'p.147', 1, '20.00', 0, '003.', NULL, 1),
(1119, '2022-01-01', 'Hélia Sonia Raphael (org.)', 'Avaliação sob exame', NULL, '378.81', '85.7496.036.5', 'Campinas-SP', '***', NULL, NULL, '2002', '1 ed.', 'v.1', 'ex.1', 'p.224', 1, '20.00', 0, '003.', NULL, 1),
(1120, '2022-01-01', '***', 'Coleção Negro Retrato do Brasil - Volume 7', NULL, '305.8', NULL, '***', '***', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.124', 1, '20.00', 0, '003.', NULL, 1),
(1121, '2022-01-01', 'ITAPEFVI, Associação Cultural e Esportiva de - ACEI', 'Os 60 anos da ACEI contados pela culinária: 1955-2015', NULL, '981', NULL, 'Barueri-SP', '***', NULL, NULL, '2015', '1 ed.', NULL, 'ex.1', 'p.103', 1, '20.00', 0, '003.', NULL, 1),
(1122, '2022-01-01', 'José Cretella Júnior', ' Comentários à Lei do mandado de segurança: de acordo com a constituição de 5 de outubro de 1988', '342.7 (81)', '348', '85.309.0153.3', 'Rio de Janeiro', 'Forense Editora', NULL, NULL, '1995', '1 ed.', 'v.1', 'ex.1', 'p.450', 1, '20.00', 0, '003.', NULL, 1),
(1123, '2022-01-01', 'Tribunal de Contas de SP', 'Lei Complementar nº 709 de 14 de Janeiro de 1993', NULL, '348', NULL, 'São Paulo', '***', NULL, NULL, NULL, '1 ed.', 'v.1', NULL, 'p.23', 1, '20.00', 0, '003.', NULL, 1),
(1124, '2022-01-01', 'Jônatas Milhomens', 'Manual de petições: civíl, criminais, trabalhistas', '342.736(81)', '340', '85.309.0235.1', 'Rio de Janeiro', 'Forense Editora', NULL, NULL, '1995', '1 ed.', 'v.1', 'ex.1', 'p.415', 1, '20.00', 0, '003.', NULL, 1),
(1125, '2022-01-01', 'LANDI, Francisco Romeu (Coord.)', 'Vigor e inovação na pesquisa basileira: resultados de projetos temáticos em São Paulo', NULL, '507.208161', NULL, 'São Paulo', 'UNIVERSIDADES', NULL, NULL, '1998', '1 ed.', NULL, 'ex.1', 'p.171', 1, '20.00', 0, '003.', NULL, 1),
(1126, '2022-01-01', 'Valdemar Sguissadi (Org.)', 'Avaliação universitária em questão: reformas do estado e da educação superior', NULL, '370.010981', '85.85701.44.7', 'Campinas-SP', '***', NULL, NULL, '1997', '1 ed.', 'v.1', 'ex.1', 'p.174', 1, '20.00', 0, '003.', NULL, 1),
(1127, '2022-01-01', 'LAMARE, Rinaldo de ', 'A educação da criança', NULL, '155.4', NULL, 'Rio de Janeiro', 'Vip Editora', NULL, NULL, NULL, '1 ed.', NULL, 'ex.1', 'p.122', 1, '20.00', 0, '003.', NULL, 1),
(1128, '2022-01-01', 'Paulo Sumariva', 'Criminologia: teoria e prática', NULL, '364', '978.85.7626.851.2', 'Niterói-RJ', 'Impetus Des. Educ. Ltda', NULL, NULL, '2015', '1 ed.', 'v.1', 'ex.1', 'p.216', 1, '20.00', 0, '003.', NULL, 1),
(1129, '2022-01-01', 'Washinton de Barros Monteiro', 'Curso de Direito Civil ', NULL, '347', NULL, 'São Paulo', 'Saraiva', NULL, NULL, '19983', '1 ed.', 'v.1', 'ex.1', 'p.323', 1, '20.00', 0, '003.', NULL, 1),
(1130, '2022-01-01', 'Prefeitura de Caieiras', 'Lei Organica do Município de Caieiras-SP', NULL, '348', NULL, 'São Paulo', 'Câmara ', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.105', 1, NULL, 1, '003.', NULL, 1),
(1131, '2022-01-01', 'Maximilianus Cláudio Américo Führer', 'Coleção Resumos: Resumo de Direito Comercial (Volume 1)', NULL, '340', NULL, 'São Paulo', 'Malheiros', NULL, NULL, '1992', '1 ed.', 'v.1', 'ex.1', 'p.144', 1, '25.00', 0, '003.', NULL, 1),
(1132, '2022-01-01', 'Maximilianus Cláudio Américo Führer', 'Coleção Resumos: Resumo de obrigações e contratos (Volume 2)', NULL, '340', NULL, 'São Paulo', 'Malheiros', NULL, NULL, '1996', '1 ed.', 'v.2', 'ex.1', 'p.126', 1, '25.00', 0, '003.', NULL, 1),
(1133, '2022-01-01', 'Maximilianus Cláudio Américo Führer', 'Coleção Resumos: Resumo do direito civil (Volume 3)', NULL, '340', NULL, 'São Paulo', 'Malheiros', NULL, NULL, '1992', '1 ed.', 'v.3', 'ex.1', 'p.144', 1, '25.00', 0, '003.', NULL, 1),
(1134, '2022-01-01', 'Maximilianus Cláudio Américo Führer', 'Coleção Resumos: Resumo de Processo civil (Volume 4)', NULL, '340', NULL, 'São Paulo', 'Malheiros', NULL, NULL, '1992', '1 ed.', 'v.4', 'ex.1', 'p.159', 1, '25.00', 0, '003.', NULL, 1),
(1135, '2022-01-01', 'Maximilianus Cláudio Américo Führer', 'Coleção Resumos: Resumo de processo penal     (Volume 5)', NULL, '340', NULL, 'São Paulo', 'Malheiros', NULL, NULL, '1996', NULL, 'v.5', 'ex.1', 'p.175', 1, '25.00', 0, '003.', NULL, 1),
(1136, '2022-01-01', 'Maximilianus Cláudio Américo Führer', 'Coleção Resumos: Resumo de processo penal     (Volume 6)', NULL, '340', NULL, 'São Paulo', 'Malheiros', NULL, NULL, '1996', NULL, 'v.6', 'ex.1', 'p.175', 1, '25.00', 0, '003.', NULL, 1),
(1137, '2022-01-01', 'Maximilianus Cláudio Américo Führer', 'Coleção Resumos: Resumo de direito administrativo (Volume 7)', NULL, '340', NULL, 'São Paulo', 'Malheiros', NULL, NULL, '1997', NULL, 'v.7', 'ex.1', 'p.127', 1, '25.00', 0, '003.', NULL, 1),
(1138, '2022-01-01', 'Maximilianus Cláudio Américo Führer', 'Coleção Resumos: Resumo de direito tributário (Volume 8)', NULL, '340', NULL, 'São Paulo', 'Malheiros', NULL, NULL, '1997', NULL, 'v.8', 'ex.1', 'p.127', 1, '25.00', 0, '003.', NULL, 1),
(1139, '2022-01-01', 'Paulo Duarte', 'O processo dos rinocerontes (Razões de defesas e outras razões)', NULL, '341', NULL, 'São Paulo', '***', NULL, NULL, '1967', NULL, 'v.1', 'ex.1', 'p.223', 1, '25.00', 0, '003.', NULL, 1),
(1140, '2022-01-01', 'Secretaria de Segurança Pública Federal', 'Secretaria de Segurança: Normas e regulamentos', NULL, '348', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, NULL, NULL, 'v.1', 'ex.1', 'p.173', 1, NULL, 1, '003.', NULL, 1),
(1141, '2022-01-01', 'Senado Federal', 'Estatuto das cidades e Desenvolvimento urbano', NULL, '348', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, NULL, NULL, 'v.1', 'ex.1', 'p.180', 1, NULL, 1, '003.', NULL, 1),
(1142, '2022-01-01', 'Luiz Olavo Babtista', 'Contratos internacionais', '347.44.341', '327', '978.85.7721.121.0', 'São Paulo', 'Lex Editoras', NULL, NULL, '2010', NULL, 'v.1', 'ex.1', 'p.334', 1, '25.00', 0, '003.', NULL, 1),
(1143, '2022-01-01', 'Yara Muller Leite', 'Dos recursos em geral - Interposições e Processamento', NULL, '341', NULL, 'São Paulo', '***', NULL, NULL, '1969', NULL, 'v.1', 'ex.1', 'p.334', 1, '25.00', 0, '003.', NULL, 1),
(1144, '2022-01-01', 'Wagner Veneziani Costa', 'Exame da Ordem: Civil, processo civil e tributário: preças práticas, perguntas e respostas, prova escrita e oral', '34(81)(079)', '340.07', '85.85506.01.6', 'São Paulo', 'Andelotti Editora', NULL, NULL, '1994', NULL, 'v.1', 'ex.1', 'p.259', 1, '25.00', 0, '003.', NULL, 1),
(1145, '2022-01-01', 'Wagner Veneziani Costa', 'Exame da Ordem: Civil, processo civil e tributário: preças práticas, perguntas e respostas, prova escrita e oral', '34(81)(079)', '340.07', '85.85506.01.6', 'São Paulo', 'Andelotti Editora', NULL, NULL, '1994', NULL, 'v.2', 'ex.1', 'p.259', 1, '25.00', 0, '003.', NULL, 1),
(1146, '2022-01-01', 'Wagner Veneziani Costa', 'Exame da Ordem: Penal - Processo penal e trabalhista: preças práticas, perguntas e respostas, prova escrita e oral', '34(81)(079)', '340.07', '85.85506.01.6', 'São Paulo', NULL, NULL, NULL, '1996', NULL, 'v.3', 'ex.1', 'p.259', 1, '25.00', 0, '003.', NULL, 1),
(1147, '2022-01-01', 'GLEZER, Raquel (Org.) - Museu Paulista', 'Prudente de Moraes: discursos e mensagens', NULL, '981.05', '85.7464.089.1', 'Itu-SP', 'Ottini Editora', NULL, NULL, '2003', '1 ed.', NULL, 'ex.1', 'p.348', 1, '15.00', 0, '003.', NULL, 1),
(1148, '2022-01-01', 'Senado Federal', 'Lei de responsabilidade fiscal ', NULL, '348', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, NULL, NULL, 'v.1', 'ex.1', 'p.79', 1, '15.00', 0, '003.', NULL, 1),
(1149, '2022-01-01', 'Vicente Greco Filho', 'Direito processual civil brasileiro - Volume 1', '347.9(81)/ 374.9', '347', '85.02.00400.x', 'São Paulo', 'Saraiva', NULL, NULL, '1992', NULL, 'v.1', 'ex.1', 'p.226', 1, '15.00', 0, '003.', NULL, 1),
(1150, '2022-01-01', 'Vicente Greco Filho', 'Direito processual civil brasileiro - Volume 3', '347.9(81)', '347', '85.02.00402.6', 'São Paulo', 'Saraiva', NULL, NULL, '1993', NULL, 'v.3', 'ex.1', 'p.369', 1, '15.00', 0, '003.', NULL, 1),
(1151, '2022-01-01', 'Câmara Municipal de Piracicaba', 'Coleção Consolidação das leis do Município de Piracicaba - Volume 1 - Aspectos Históricos', '342.347.352(816.12)', '348', '978.85.62242.007', 'Piracicaba-SP', 'Câmara ', NULL, NULL, '2008', NULL, 'v.1', 'ex.1', 'p.128', 1, NULL, 1, '003.', NULL, 1),
(1152, '2022-01-01', 'Câmara Municipal de Piracicaba', 'Coleção Consolidação das leis do Município de Piracicaba - Volume 2 - Educação e Cultura', '342.347.37:008(816)', '348', '978.85.62242.01.4', 'Piracicaba-SP', 'Câmara ', NULL, NULL, '2008', NULL, 'v.2', 'ex.1', 'p.244', 1, NULL, 1, '003.', NULL, 1),
(1153, '2022-01-01', 'Câmara Municipal de Piracicaba', 'Coleção Consolidação das leis do Município de Piracicaba - Volume 3 - Código de Posturas', '342.347.352(816.12)', '348', '978.85.62242.02.1', 'Piracicaba-SP', 'Câmara ', NULL, NULL, '2008', NULL, 'v.3', 'ex.1', 'p.110', 1, NULL, 1, '003.', NULL, 1),
(1154, '2022-01-01', 'Câmara Municipal de Piracicaba', 'Coleção Consolidação das leis do Município de Piracicaba - Volume 4 - Normas de Edificação/zoneamento', '342.347.352(816.12)', '348', '978.85.62242.03.8', 'Piracicaba-SP', 'Câmara ', NULL, NULL, '2008', NULL, 'v.4', 'ex.1', 'p.163', 1, NULL, 1, '003.', NULL, 1),
(1155, '2022-01-01', 'Ana Maria Navajas', 'Avaliação institucional: uma visão crítica', NULL, '378.81', '85.86022.20.9', 'São Paulo', 'UNIVERSIDADES', NULL, NULL, '1998', NULL, 'v.1', 'ex.1', 'p.80', 1, '10.00', 0, '003.', NULL, 1),
(1156, '2022-01-01', 'Alexandre A. Rocha (Coord.)', 'Agenda Legislativa para o desenvolvimento nacional', NULL, '320', '978.85.7018.353.8', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2007', NULL, 'v.1', 'ex.2', 'p.524', 1, NULL, 1, '003.', NULL, 1),
(1157, '2022-01-01', 'BRASIL', 'Constituição Federal , Código Civil, Código de Processo Civil, Código Penal, Código de processo penal e legislação complementar', '340.136(81)', '348', '85.204.1692.6', 'Brasília-DF', 'Manole', NULL, NULL, '2003', NULL, 'v.1', 'ex.1', 'p.1780', 1, NULL, 1, '003.', NULL, 1),
(1158, '2022-01-01', 'Brasil', 'Novo Código civil brasileiro, Lei 10.406, de 10 de janeiro de 2002 - Estudo compartivo com o Código Civil de 1916, Constituição Federal, Legislação codificada e Extravagante', '347(81)(094.9)', '348', '85.203.2365.0', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '2003', NULL, 'v.1', 'ex.1', 'p.831', 1, NULL, 1, '003.', NULL, 1),
(1159, '2022-01-01', 'Paulo Dourado de Gusmão', 'Dicionário de Direito da família', '347.6(81)(03)', '347', NULL, 'São Paulo', 'Forense Editora', NULL, NULL, '1986', NULL, 'v.1', 'ex.1', 'p.987', 1, '30.00', 0, '003.', NULL, 1),
(1160, '2022-01-01', 'Placido e Silva', 'Vocabulário Jurídico -     Volume - 1 (A a C)', NULL, '340.03', NULL, 'São Paulo', 'Forense Editora', NULL, NULL, '1973', NULL, 'v.1', 'ex.1', 'p.1 - 466', 1, '30.00', 0, '003.', NULL, 1),
(1161, '2022-01-01', 'Placido e Silva', 'Vocabulário Jurídico         Volume - 2 (D a I)', NULL, '340.03', NULL, 'São Paulo', 'Forense Editora', NULL, NULL, '1973', '1 ed.', 'v.2', 'ex.1', 'p.467 - 871', 1, '30.00', 0, '003.', NULL, 1),
(1162, '2022-01-01', 'Placido e Silva', 'Vocabulário Jurídico         Volume -3 (J a P)', NULL, '340.03', NULL, 'São Paulo', 'Forense Editora', NULL, NULL, '1973', '1 ed.', 'v.3', 'ex.1', 'p.872 - 1265', 1, '30.00', 0, '003.', NULL, 1),
(1163, '2022-01-01', 'Placido e Silva', 'Vocabulário Jurídico          Volume - 4 (Q a Z)', NULL, '340.03', NULL, 'São Paulo', 'Forense Editora', NULL, NULL, '1973', '1 ed.', 'v.4', 'ex.1', 'p.1266 - 1672', 1, '30.00', 0, '003.', NULL, 1),
(1164, '2022-01-01', 'Benazir Bhutto', 'Reconciliação: Islamismo, democracia e Ocidente', '327.36', '327.172', '978.85.2200.936.7', 'Rio de Janeiro', 'Agir Editora', NULL, NULL, '2008', '1 ed.', 'v.1', 'ex.1', 'p.317', 1, '30.00', 0, '003.', NULL, 1),
(1165, '2022-01-01', 'Jeffry A. Frieden', 'Capitalismo Global: história econômica e política do século XX', '339', '337', '978.85.378.0067.6', 'Rio de Janeiro', 'Zahar Editora', NULL, NULL, '2008', '1 ed.', 'v.1', 'ex.1', 'p.573', 1, '30.00', 0, '003.', NULL, 1),
(1166, '2022-01-01', 'Michael Lewis', 'Bumerangue', '336.76(73)', '332.620973', '978.85.7542.740.8', 'Rio de Janeiro', 'Sextante', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.224', 1, '30.00', 0, '003.', NULL, 1),
(1167, '2022-01-01', 'KMIGHT, Amy', 'Como começou a guerra fria', '94(100)\"1945/1989\"', '909.825', '978.85.01.07518.5', 'Rio de janeiro', 'Record', NULL, NULL, '2008', '1 ed.', NULL, 'ex.1', 'p.362', 1, '30.00', 0, '003.', NULL, 1),
(1168, '2022-01-01', 'Claude Lévi-Strauss', 'O Homem Nu - Mitologias Volume 4 ', NULL, '398.0420970', '978.85.405.023.5', 'São Paulo', 'Cosac Naify', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.752', 1, '30.00', 0, '003.', NULL, 1),
(1169, '2022-01-01', 'WATSON, James D. ', 'DNA: o segredo da vida', NULL, '572.86', '978.85.359.0716.2', 'São Paulo', 'Companhia das Letras', NULL, NULL, '2005', '1 ed.', NULL, 'ex.1', 'p.470', 1, '30.00', 0, '003.', NULL, 1),
(1170, '2022-01-01', 'Petrônio Braz', 'Direito Municipal na Constituição : Doutrina Prática e Legislação', NULL, '341', NULL, '***', 'Direito Editora', NULL, NULL, '2003', '1 ed.', 'v.1', 'ex.1', 'p.689', 1, '30.00', 0, '003.', NULL, 1),
(1171, '2022-01-01', 'Fran Martins', 'Curso de Direito Comercial: empresa comercial,  emprasários individuais, michoempresas, sociedades comerciais, fundos de comércio', '347.7', '343', '85.309.1168.7', 'Rio de Janeiro', 'Forense Editora', NULL, NULL, '2000', '1 ed.', 'v.1', 'ex.1', 'p.384', 1, '30.00', 0, '003.', NULL, 1),
(1172, '2022-01-01', 'José dos Santos Carvalho Filho', 'Manual de direito administrativo', NULL, '342', '85.7387.632.8', 'Rio de Janeiro', 'Lumen Juris Editora', NULL, NULL, '2005', '1 ed.', 'v.1', 'ex.1', 'p.927', 1, '30.00', 0, '003.', NULL, 1),
(1173, '2022-01-01', 'Jessé Torres Pereira Júnior', 'Da reforma administrativa constitucional', NULL, '342.8106', '85.7147.121.5', 'Rio de Janeiro', 'Renovar', NULL, NULL, '1999', '1 ed.', 'v.1', 'ex.1', 'p.503', 1, '30.00', 0, '003.', NULL, 1),
(1174, '2022-01-01', 'Samuel Monteiro', 'Crimes Fiscais e Abuso de autoridade', NULL, '345', '85.289.0371.0', 'São Paulo', 'Hemus Ltda', NULL, NULL, '1994', '1 ed.', 'v.1', 'ex.1', 'p.806', 1, '30.00', 0, '003.', NULL, 1),
(1175, '2022-01-01', 'Sílvio Nazareno Costa', 'Súmula vinculante e reforma do judiciário', '347.97/.99', '340', '85.309.1435-x', 'Rio de Janeiro', 'Forense Editora', NULL, NULL, '2002', '1 ed.', 'v.1', 'ex.1', 'p.348', 1, '30.00', 0, '003.', NULL, 1),
(1176, '2022-01-01', 'José Antonio Remédio', 'Manual de segurança na jurisprudência: direito material e processual', '342.722(81)(094.9)', '340', '85.02.03767.6', 'São Paulo', 'Saraiva', NULL, NULL, '2003', '1 ed.', 'v.1', 'ex.1', 'p.1772', 1, '30.00', 0, '003.', NULL, 1),
(1177, '2022-01-01', 'Rui Stoco', 'Responsabilidade Civil e sua interpretação jurisprudencial: doutrinas e jurisprudência', '347.51(81)(094.9)', '340', '85.203.1481.3', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '1997', '1 ed.', 'v.1', 'ex.1', 'p.1051', 1, '30.00', 0, '003.', NULL, 1),
(1178, '2022-01-01', 'Iran Siqueira Lima e Geraldo Augusto Sampaio Franco de Lima (Coord.)', 'Curso de mercado financeiro: tópicos especiais', NULL, '332.6', '978.85.224.4520.2', 'São Paulo', 'Atlas S.A.', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.579', 1, '30.00', 0, '003.', NULL, 1),
(1179, '2022-01-01', 'Noam Chomsky', 'Rumo  a uma nova guerra fria', '327(73)', '327.73', '978.85.01.07099.9', 'Rio de Janeiro', 'Record', NULL, NULL, '2007', NULL, 'v.1', 'ex.1', 'p.640', 1, '30.00', 0, '003.', NULL, 1),
(1180, '2022-01-01', 'Tom Chung', 'Negócios com a China: desvendando os segredos da cultura e estratégias da mente chinesa', NULL, '382.09', '85.889.1699.1', 'Osasco-SP', 'Novo Século Editora', NULL, NULL, '2005', NULL, 'v.1', 'ex.1', 'p.396', 1, '30.00', 0, '003.', NULL, 1),
(1181, '2022-01-01', 'LIMA, Maria Regina Sores de (Org.)', 'Agenda sul-americana: mudanças e desafios no início do século XXI', '32(8)/ 338(8)/ 308(8)', '342.05', '978.85.7631.081.5', 'Brasília-DF', 'Fundação Alexandre de Gusmão ', NULL, NULL, '2007', '1 ed.', NULL, 'ex.1', 'p.374', 1, '15.00', 0, '003.', NULL, 1),
(1182, '2022-01-01', 'Mira Kamdar', 'Planeta Índia: a ascensão turbulenta de uma nova potência global', '316.42(540)', '303.48254', '978.85.220.0931.2', 'Rio de Janeiro', 'Agir Editora', NULL, NULL, '2008', '1 ed.', 'v.1', 'ex.1', 'p.389', 1, '15.00', 0, '003.', NULL, 1),
(1183, '2022-01-01', 'Geoffrey Miller', 'Darwin vai às compras: sexo, evolução e consumo', '330.567.2', '339.47', '978.85.7684.422.8', 'São Paulo', 'Best Seller Ltda', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.531', 1, '15.00', 0, '003.', NULL, 1),
(1184, '2022-01-01', 'Loretta Napoleoni', 'Economia Bandida: a nova realidade do capitalismo', '343.46', '364.168', '978.85.7432.105.9', 'Rio de Janeiro', 'DIFEL', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.350', 1, '15.00', 0, '003.', NULL, 1),
(1185, '2022-01-01', 'CALONIUS, Erik', 'O último navio negreiro da América - A conspiração que levou os Estados Unidos à Guerra civil', '94(73)', '973.711', '978.85.01.07925.1', 'Rio de Janeiro', 'Record', NULL, NULL, '2008', '1 ed.', NULL, 'ex.1', 'p.317', 1, '15.00', 0, '003.', NULL, 1),
(1186, '2022-01-01', 'Stephen M. Davis', 'Os novos capitalistas: a influência dos investidores-cidadãos nas decisões das empresas', '330.342.14', '330.122', '978.85.352.3066.6', 'Rio de Janeiro', 'Elsevier', NULL, NULL, '2008', '1 ed.', 'v.1', 'ex.1', 'p.303', 1, '15.00', 0, '003.', NULL, 1),
(1187, '2022-01-01', 'COSTA, Antonio Carlos Gomes da Costa', 'Ser empresário: o pensamento de Noberto Odebrecht', '65.012.2', '658.4', '85.89309.09.6', 'Rio de Janeiro', 'Versal Editora', NULL, NULL, '2004', '1 ed.', NULL, 'ex.1', 'p.349', 1, '15.00', 0, '003.', NULL, 1),
(1188, '2022-01-01', 'TIBA, Içami ', 'Família de alta performance: conceitos conteporâneos na educação', NULL, '158', '978.85.99362.38.9', 'São Paulo', 'Intergrare Editora', NULL, NULL, '2009', '1 ed.', NULL, 'ex.1', 'p.271', 1, '15.00', 0, '003.', NULL, 1),
(1189, '2022-01-01', 'Robert Gilpin', 'O desafio do capitalismo global ', '339', '337', '85.01.06370.3', 'Rio de Janeiro ', 'Record', NULL, NULL, '2004', '1 ed.', NULL, 'ex.1', 'p.494', 1, '15.00', 0, '003.', NULL, 1),
(1190, '2022-01-01', 'David Harvey', 'Para entender O capital', '330.85/ 042773', '330.122', '978.85.7559.322.6', 'São Paulo', 'Boitempo', NULL, NULL, '2013', '1 ed.', NULL, 'ex.1', 'p.335', 1, '15.00', 0, '003.', NULL, 1),
(1191, '2022-01-01', 'RODENBURG, Patsy ', 'O segundo círculo ', '159.947', '158.1', '978.85.7684.279.8', 'Rio de Janeiro', 'Best Seller Ltda', NULL, NULL, '2009', '1 ed.', NULL, 'ex.1', 'p.314', 1, '15.00', 0, '003.', NULL, 1),
(1192, '2022-01-01', 'HANCOCK, Graham ', 'Sobrenatural: Os mistérios que cercam a origem da religião e da arte - encontros com os antigos mestres da humanidade', '2.587', '201.44', '978.85.7701.168.1', 'Rio de Janeiro', 'Nova Era Editora', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p.822', 1, '15.00', 0, '003.', NULL, 1),
(1193, '2022-01-01', 'Mario Curtis Giordani', 'Coleção História: África: anterior aos descobrimentos - Idade Moderna I', NULL, '940', '85.326.0845.0', 'Petrópolis', 'Vozes Editora', NULL, NULL, NULL, '1 ed.', 'v.1', 'ex.1', 'p.183', 1, '15.00', 0, '003.', NULL, 1),
(1194, '2022-01-01', 'Vitor A. Canto', 'Um brinde à economia!: como buscar as verdades sobre investimentos nas conversas do dia-a-dia', '330.322', '332.6', '978.85.352.3002.4', 'Rio de Janeiro', 'Elsevier', NULL, NULL, '2008', '1 ed.', 'v.1', 'ex.1', 'p.281', 1, '15.00', 0, '003.', NULL, 1),
(1195, '2022-01-01', 'Antonio Delfim Netto (Coord.)', 'O Brasil do Século XXI', '338.1(81)', '330.981', '978.85.02.13508.6', 'São Paulo', 'Saraiva', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.448', 1, '15.00', 0, '003.', NULL, 1),
(1196, '2022-01-01', 'Antonio Lacerda', 'Emoções ocultas e estratégias eleitorais', '324', '324.7', '978.85.390.0034.0', 'Rio de Janeiro', 'Objetiva Ltda', NULL, NULL, '2009', '1 ed.', 'v.1', 'ex.1', 'p.311', 1, '15.00', 0, '003.', NULL, 1),
(1197, '2022-01-01', 'Alexandre Elder', 'Aprenda a operar no mercado de ações', '336.7', '332.6', '85.352.1898.0', 'Rio de Janeiro', 'Elsevier', NULL, NULL, '2006', '1 ed.', 'v.1', 'ex.1', 'p.317', 1, '15.00', 0, '003.', NULL, 1),
(1198, '2022-01-01', 'BERNABEU, Guirado Francisco', 'Treinamento de negociação: desenvolvolvendo a competência para negociar', '65.012.2', '658.4', '978.85.62564.26.0', 'Brasília-DF', 'Senac Editora', NULL, NULL, '2013', '1 ed.', NULL, 'ex.1', 'p.396', 1, '15.00', 0, '003.', NULL, 1),
(1199, '2022-01-01', 'Muhammad Yunus', 'Um mundo sem pobreza: a empresa social e o futuro do capitalismo', '334.7', '338.7', '978.85.08.11994.3', 'São Paulo', 'Ática', NULL, NULL, '2008', '1 ed.', 'v.1', 'ex.1', 'p.272', 1, '15.00', 0, '003.', NULL, 1),
(1200, '2022-01-01', 'JACQ, Christian', 'As egípcias: retratos de mulheres do Egito faraônico', '932', '932.01', '85.286.07777.1', 'Rio de Janeiro', 'Bertrand Brasil', NULL, NULL, '2000', '1 ed.', NULL, 'ex.1', 'p.336', 1, '15.00', 0, '003.', NULL, 1),
(1201, '2022-01-01', 'FERRISS, Timothy ', 'Trabalhe 4 horas por semana', NULL, '650.1', '978.85.7665.354.7', 'São Paulo', 'Planeta Brasil', NULL, NULL, '2008', '1 ed.', 'v.1', 'ex.1', 'p.317', 1, '15.00', 0, '003.', NULL, 1),
(1202, '2022-01-01', 'Parag Khanna ', 'O segundo mundo: impérios e influência na nova ordem global', '327', '327.1', '978.85.98078.38.0', 'Rio de Janeiro', 'Intrínseca Editora', NULL, NULL, '´2008', '1 ed.', 'v.1', 'ex.1', 'p.559', 1, '15.00', 0, '003.', NULL, 1),
(1203, '2022-01-01', 'LOPES, Roberto', '1500-1501 - A integra de descobrimento', NULL, '981.03', '978.85.9788565932', 'São Paulo', 'Discovery Publicações', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.141', 1, '15.00', 0, '003.', NULL, 1),
(1204, '2022-01-01', 'Thomas Friedman', 'O mundo é plano: uma breve história do século XXI', NULL, '303.48', '85.7302.741.x', 'Rio de Janeiro', 'Objetiva Ltda', NULL, NULL, '2005', '1 ed.', 'v.1', 'ex.1', 'p.471', 1, '15.00', 0, '003.', NULL, 1),
(1205, '2022-01-01', 'Bronislaw Malinowski', 'Crime e costume na sociedade selvagem', '39(93=082)', '301', '978.85.230.1237.3', 'Brasília-DF', 'UNIVERSIDADES', NULL, NULL, '2008', '1 ed.', 'v.1', 'ex.1', 'p.100', 1, '15.00', 0, '003.', NULL, 1),
(1206, '2022-01-01', 'Antônio da Silva Ferreira', 'Não basta amar: a pedagogia de Dom Bosco em seus escritos', NULL, '370.1', NULL, 'São Paulo', 'Salesiana Editora', NULL, NULL, '2008', '1 ed.', 'v.1', 'ex.1', 'p.127', 1, '15.00', 0, '003.', NULL, 1),
(1207, '2022-01-01', 'Thomas L. Friedman', 'Quente, plano e lotado: os desafios e oportunidades de um novo mundo', '330', '330', '978.85.7302.979.6', 'Rio de Janeiro', 'Objetiva Ltda', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.623', 1, '15.00', 0, '003.', NULL, 1),
(1208, '2022-01-01', 'BENÍTEZ, J.J. ', 'A rebelião de Lúcifer', NULL, '863', '978.85.7665.455.1', 'São Paulo', 'Planeta Brasil', NULL, NULL, '2009', '1 ed.', NULL, 'ex.1', 'p.479', 1, '15.00', 0, '003.', NULL, 1),
(1209, '2022-01-01', 'CITRO, Massimo', 'O código básico do universo: a ciência dos mundos invisíveis na física, na medicina e na espiritualidade', '165', '121', '978.85.316.1254.1', 'São Paulo', 'Cultrix Editora', NULL, NULL, '2014', '1 ed.', NULL, 'ex.1', 'p.286', 1, '15.00', 0, '003.', NULL, 1),
(1210, '2022-01-01', 'PATEL, Ketan J. ', 'O mestre da estratégia: poder, propósito e princípios', '65.012.2', '658.4', '978.85.7684.122.7', 'Rio de Janeiro', 'Best Seller Ltda', NULL, NULL, '2007', '1 ed.', NULL, 'ex.1', 'p.222', 1, '15.00', 0, '003.', NULL, 1),
(1211, '2022-01-01', 'FERREIRA, Gonzaga ', 'Negociação: como usar a inteligência e a racionalidade', NULL, '658.4', '978.85.224.5157.6', 'São Paulo', 'Atlas S.A.', NULL, NULL, '2008', '1 ed.', NULL, 'ex.1', 'p.237', 1, '15.00', 0, '003.', NULL, 1),
(1212, '2022-01-01', 'Secretaria Nacional de Justiça', 'Manual de extradição', NULL, '345', NULL, 'Brasília-DF', 'Secretarias ', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.770', 1, '15.00', 0, '003.', NULL, 1),
(1213, '2022-01-01', 'BRASIL, Senado Federal', 'Dados biográficos: senadores quinquagésima quarta legislatura 2011-2015', NULL, '923.281', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p.436', 1, '15.00', 0, '003.', NULL, 1),
(1214, '2022-01-01', 'Darcy Arruda Miranda', 'Repositório de Jurisprudência do código penal - Volume 2', NULL, '340', NULL, 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '1959', '1 ed.', 'v.2', 'ex.1', 'p.1095', 1, '15.00', 0, '003.', NULL, 1),
(1215, '2022-01-01', 'Darcy Arruda Miranda', 'Repositório de Jurisprudência do código do processo penal - Volume 9', NULL, '340', NULL, 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '1963', '1 ed.', 'v.99', 'ex.1', 'p.452', 1, '15.00', 0, '003.', NULL, 1),
(1216, '2022-01-01', 'Rui Stoco', 'Tratado de responsabilidade civil', '347.51(81)(094.4)', '347', '85.203.2453.3', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '2004', NULL, 'v.1', 'ex.1', 'p.2203', 1, '15.00', 0, '003.', NULL, 1);
INSERT INTO `librarycollection` (`id`, `registrationDate`, `author`, `title`, `cdu`, `cdd`, `isbn`, `local`, `publisher_edition`, `number`, `month`, `year`, `edition`, `volume`, `copyNumber`, `pageNumber`, `typeAcquisitionId`, `price`, `prohibitedSale`, `provider`, `exclusionInfoTerm`, `registeredByUserId`) VALUES
(1217, '2022-01-01', 'Cleiton Leite de Loiola, Josino Ribeiro Neto e Leonardo Airton Pessoa Soares', 'Constituição Federal interpretada', NULL, '342', '978.85.61685.23.2', 'Leme-SP', 'Anhanguera Publicações', NULL, NULL, '2011', NULL, 'v.1', 'ex.1', NULL, 1, '15.00', 0, '003.', NULL, 1),
(1218, '2022-01-01', 'Élcio D\' Angelo', 'Direito Administrativo Municipal e política administrativa', NULL, '342', '978.85.61685.21.8', 'Leme-SP', 'Anhanguera Publicações', NULL, NULL, '2011', NULL, 'v.1', 'ex.1', 'p.752', 1, '15.00', 0, '003.', NULL, 1),
(1219, '2022-01-01', 'Wilson Melo da Silva', 'Da responsabilidade civil automobilistica', '347.518.656.13.08', '347', NULL, 'São Paulo', 'Saraiva', NULL, NULL, '1974', NULL, 'v.1', 'ex.1', 'p.374', 1, '15.00', 0, '003.', NULL, 1),
(1220, '2022-01-01', 'Secretaria de Políticas de Previdência', 'Fundos de pensão: coletânea de normas', NULL, '341.67244', NULL, 'Brasília-DF', 'Secretarias ', NULL, NULL, '2012', NULL, 'v.1', 'ex.1', 'p.624', 1, '15.00', 0, '003.', NULL, 1),
(1221, '2022-01-01', 'José Cretella Júnior', 'Controle jurisdicional do ato administrativo', '35.077.2', '342', NULL, 'Rio de Janeiro', 'Forense Editora', NULL, NULL, '1993', NULL, 'v.1', 'ex.1', 'p.569', 1, '15.00', 0, '003.', NULL, 1),
(1222, '2022-01-01', 'Brasil  - Juarez de Oliveira', 'Código Penal', '343(81)(094.9)', '348', '85.02.00349.6', 'São Paulo', 'Saraiva', NULL, NULL, '1992', '1 ed.', 'v.1', 'ex.1', 'p.362', 1, '15.00', 0, '003.', NULL, 1),
(1223, '2022-01-01', 'Brasil  - Juarez de Oliveira', 'Código Comercial', '347.7(81)(094.4)', '348', '85.02.00332.1', 'São Paulo', 'Saraiva', NULL, NULL, '1992', '1 ed.', 'v.1', 'ex.1', 'p.668', 1, '15.00', 0, '003.', NULL, 1),
(1224, '2022-01-01', 'Samuel Monteiro', 'Dos crimes fazendários: compêndio teorico e prático', NULL, '345', NULL, 'São Paulo', 'Hemus Ltda', NULL, NULL, '1998', '1 ed.', 'v.1', 'ex.1', 'p.800', 1, '15.00', 0, '003.', NULL, 1),
(1225, '2022-01-01', 'SIMÃO FILHO, Adalberto ', 'Frenchising: Apectos jurídicos e contratuais', '347.464.38:38', '658', '85.224.0942.0', 'São Paulo', 'Atlas S.A.', NULL, NULL, '1993', '1 ed.', NULL, 'ex.1', 'p.111', 1, '15.00', 0, '003.', NULL, 1),
(1226, '2022-01-01', 'Ary Azevedo Franco', 'A prescrião extintiva no Código civil brasileiro (Doutrina e jurisprudência)', NULL, '347', NULL, 'Rio de Janeiro', 'Forense Editora', NULL, NULL, '1956', '1 ed.', 'v.1', 'ex.1', 'p.561', 1, '15.00', 0, '003.', NULL, 1),
(1227, '2022-01-01', 'Roque Jacintho', 'Contratos e outros instrumentos', NULL, '341', NULL, 'São Paulo', 'Jurídica Brasileira, Editora ', NULL, NULL, '1997', '1 ed.', 'v.1', 'ex.1', 'p.1406', 1, '15.00', 0, '003.', NULL, 1),
(1228, '2022-01-01', 'SIENKO, Michell Joseph ', 'Química', NULL, '540', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1978', '1 ed.', NULL, 'ex.1', 'p.650', 1, '15.00', 0, '003.', NULL, 1),
(1229, '2022-01-01', 'Ferdinand Pierre Beer', 'Mecânica vetorial para engenheiros', NULL, '531.02462', '85.346.0202.6', 'São Paulo', 'Makron Books Ltda', NULL, NULL, '1991', '1 ed.', NULL, 'ex.1', 'p.793', 1, '15.00', 0, '003.', NULL, 1),
(1230, '2022-01-01', 'ROBERTOLLA, José Luiz de Campos ', 'Coleção de Física: Termologia - Volume 5', NULL, '530', '85.08.01109.1', 'São Paulo', 'Ática', NULL, NULL, '1986', '1 ed.', 'v.5', 'ex.1', 'p.422', 1, '15.00', 0, '003.', NULL, 1),
(1231, '2022-01-01', 'ROBERTOLLA, José Luiz de Campos ', 'Coleção de Física: Eletrostática - Volume 7', NULL, '530', '85.08.01394.9', 'São Paulo', 'Ática', NULL, NULL, '1986', '1 ed.', 'v.7', 'ex.1', 'p.416', 1, '15.00', 0, '003.', NULL, 1),
(1232, '2022-01-01', 'Angelo Eduardo B. Marques', 'Coleção Estude e Use: Eletrônica Analógica: Dispositivos semicondutores: diodos e transistores', NULL, '621.381522', '85.7194.317.6', 'São Paulo', 'Ática', NULL, NULL, '1996', '1 ed.', 'v.1', 'ex.1', 'p.389', 1, '15.00', 0, '003.', NULL, 1),
(1233, '2022-01-01', 'BEER, Ferdinand Pierre ', 'Mecânica vetorial para engenheiros - Volume 1 - Estática', NULL, '620', NULL, 'São Paulo', 'McGraw-hill do Brasil Ltda', NULL, NULL, '1978', '1 ed.', 'v.1', 'ex.1', 'p.348', 1, '15.00', 0, '003.', NULL, 1),
(1234, '2022-01-01', 'BEER, Ferdinand Pierre ', 'Mecânica vetorial para engenheiros - Volume 2 - Dinâmica', NULL, '620', NULL, 'São Paulo', 'McGraw-hill do Brasil Ltda', NULL, NULL, '1978', '1 ed.', 'v.2', 'ex.1', 'p.687', 1, '15.00', 0, '003.', NULL, 1),
(1235, '2022-01-01', 'TODD, David Keith ', 'Hidrologia de Águas subterrâneas', NULL, '551.49', NULL, 'São Paulo', 'Edgard Blücher Ltda', NULL, NULL, '1959', '1 ed.', NULL, 'ex.1', 'p.319', 1, '15.00', 0, '003.', NULL, 1),
(1236, '2022-01-01', 'Moysés Brejon', 'Estrutura e fundamentos do ensino de 1º e 2º grau ', NULL, '370.981', NULL, 'São Paulo', 'Pioneira Editora', NULL, NULL, '1974', '1 ed.', 'v.1', 'ex.1', 'p.260', 1, '20.00', 0, '003.', NULL, 1),
(1237, '2022-01-01', 'Jorge Luiz Bernardi', 'Série Gestão Pública: Gestão de serviços públicos municipais', NULL, '352.160981', '978.85.8212.940.1', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2013', '1 ed.', 'v.1', 'ex.1', 'p.340', 1, '20.00', 0, '003.', NULL, 1),
(1238, '2022-01-01', 'Samira Kauchakje', 'Série Gestão Pública: Gestão Pública de serviços sociais', NULL, '361.250981', '978.85.65704.34.2', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.166', 1, '20.00', 0, '003.', NULL, 1),
(1239, '2022-01-01', 'BERNARDONI, Doralice Lopes ', 'Série Gestão Pública: Planejamento e orçamento na administração pública', NULL, '658.15', '978.85.7838.717.4', 'Curitiba-PR', 'Ibpex', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p.164', 1, '20.00', 0, '003.', NULL, 1),
(1240, '2022-01-01', 'CARVALHO JUNIOR, Moacir Ribeiro de ', 'Série Administração Estratégica: Gestão de projetos: da academia à sociedade', NULL, '658.404', '978.85.8212.153.2', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.296', 1, '20.00', 0, '003.', NULL, 1),
(1241, '2022-01-01', 'SERTEK, Paulo ', 'Série Administração Estratégica: Administração e planejamento estratégico', NULL, '658.404', '978.85.7838.792.1', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2011', '3 ed.', NULL, 'ex.1', 'p.272', 1, '20.00', 0, '003.', NULL, 1),
(1242, '2022-01-01', 'Banco Safra', 'O museu de Arte moderna da Bahia', NULL, '708', NULL, 'Bahia', 'Banco Safra', NULL, NULL, '2008', '1 ed.', NULL, 'ex.1', 'p.259', 1, '25.00', 0, '006.', NULL, 1),
(1243, '2022-01-01', 'IPH - Instituto de Recuperação de Patrimônio Histórico do Estado de SP', 'Coleção de Arte: Raccolta d\'arte', NULL, '708', NULL, '***', '***', NULL, NULL, NULL, '1 ed.', NULL, 'ex.1', 'p.297', 1, '30.00', 0, '006.', NULL, 1),
(1244, '2022-01-01', 'IPH - Instituto de Recuperação de Patrimônio Histórico do Estado de SP', 'Coleção de Arte: Raccolta d\'arte (ESTANTE)', NULL, '708', NULL, '***', '***', NULL, NULL, NULL, '1 ed.', NULL, 'ex.2', 'p.297', 1, '20.00', 0, '006.', NULL, 1),
(1245, '2022-01-01', 'GOMES, Carlos Antonio Moreira  e Marisabel Lessi de Mello (Org.)', 'Fomento do Teatro: 12 anos', NULL, '792', '978.85.62287.02.2', 'São Paulo', 'Secretarias ', NULL, NULL, '2014', '1 ed.', NULL, 'ex.1', 'p.308', 1, '20.00', 0, '006.', NULL, 1),
(1246, '2022-01-01', 'MAIA, Agaciel da Silva', 'O Senado e seus presidentes: Império e República', NULL, '923.281', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2005', '1 ed.', NULL, 'ex.1', 'p.317', 1, '15.00', 0, '003.', NULL, 1),
(1247, '2022-01-01', 'CAMPOS, Vicente Falconi ', 'Gerenciamento pelas Diretrizes ', '658.562', '658', '85.98254.15.0', 'Nova Lima-MG', '***', NULL, NULL, '2004', '1 ed.', 'v.1', 'ex.1', 'p.300', 1, '15.00', 0, '003.', NULL, 1),
(1248, '2022-01-01', 'HITT, Michael A. Hitt', 'Administração Estratégica: Competitividade e globalização', NULL, '658.4012', '85.221.0270.8', 'São Paulo', 'Pioneira Editora', NULL, NULL, '2005', '1 ed.', NULL, 'ex.1', 'p.550', 1, '15.00', 0, '003.', NULL, 1),
(1249, '2022-01-01', 'JEFFREY, Lawrence ', 'Princípios de administração financeira', NULL, '658.15', '85.88639.12.2', 'São Paulo', 'Pearson ', NULL, NULL, '2004', '1 ed.', 'v.1', 'ex.1', 'p.745', 1, '15.00', 0, '003.', NULL, 1),
(1250, '2022-01-01', 'Vários autores', 'As pessoas na organização de SP', NULL, '658.3', '978.85.7312.366.1', 'São Paulo', 'Gente Editora', NULL, NULL, '2002', '1 ed.', 'v.1', 'ex.1', 'p.306', 1, '15.00', 0, '003.', NULL, 1),
(1251, '2022-01-01', 'MISHKIN, Frederic S. ', 'Moedas, Bancos e mercado financeiro', NULL, '658', '85.216.1192.7', 'Rio de Janeiro', 'LTC Editora', NULL, NULL, '2000', '1 ed.', NULL, 'ex.1', 'p.474', 1, '15.00', 0, '003.', NULL, 1),
(1252, '2022-01-01', 'BIERMAN, Scott H. ', 'Teoria dos jogos', NULL, '658.407', '978.85.7605.696.6', 'São Paulo', 'Pearson Prentice Hall', NULL, NULL, '2011', '2 ed.', NULL, 'ex.1', 'p.413', 1, '15.00', 0, '003.', NULL, 1),
(1253, '2022-01-01', 'Fundação Konrad Adenauer', 'Série Relação Brasil- Europa: União européia, Brasil e os desafios da agenda do desenvolvimento - Volume 5', NULL, '363.1', '978.85.7504.197.0', 'Rio de Janeiro', 'Fundação Konrad Adenauer', NULL, NULL, '2016', '1 ed.', 'v.5', 'ex.1', 'p.132', 1, '15.00', 0, '003.', NULL, 1),
(1254, '2022-01-01', 'Fundação Konrad Adenauer', 'Série Relação Brasil- Europa: Integração com democracia: o desafio para os parlamentos regionais - Volume 6', NULL, '327', '978.85.7504.201.4', 'Rio de Janeiro', 'Fundação Konrad Adenauer', NULL, NULL, '2016', '1 ed.', 'v.6', 'ex.1', 'p.228', 1, '0.00', 1, '004.', NULL, 1),
(1255, '2022-01-01', 'Fundação Konrad Adenauer', 'Série Relação Brasil- Europa: Fluxos Migratórios e refugiados na atualidade - Volume 7', NULL, '327.8104', '978.85.7504.211.5', 'Rio de Janeiro', 'Fundação Konrad Adenauer', NULL, NULL, '2017', '1 ed.', 'v.7', 'ex.1', 'p.132', 1, '0.00', 1, '004.', NULL, 1),
(1256, '2022-01-01', 'Fundação Konrad Adenauer', 'Série Relação Brasil- Europa: Novos desafios da política na América do sul e na União europeia', NULL, '320.6', '978.85.7504.220.5', 'Rio de Janeiro', 'Fundação Konrad Adenauer', NULL, NULL, '2018', NULL, 'v.8', 'ex.1', 'p.180', 1, '0.00', 1, '004.', NULL, 1),
(1257, '2022-01-01', 'Fundação Konrad Adenauer', 'Série Relação Brasil- Europa: Novos desafios da política na América do sul e na União europeia', NULL, '320.6', '978.85.7504.220.5', 'Rio de Janeiro', 'Fundação Konrad Adenauer', NULL, NULL, '2018', NULL, 'v.8', 'ex.2', 'p.180', 1, '0.00', 1, '004.', NULL, 1),
(1258, '2022-01-01', 'C. Schlichthorst', 'Coleção O Brasil visto pro estrangeiros - O Rio de Janeiro como ele é (1824-1826): uma vez e nunca mais: contribuições de um diário para a história atual, costumes e especialmente a situação da tropa estrangeira na capital do Brasil', NULL, '918.1541', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2000', NULL, 'v.1', 'ex.1', 'p.326', 1, '0.00', 1, '003.', NULL, 1),
(1259, '2022-01-01', 'BRASIL, Senado Federal', 'Bibliotenca Acadêmica Luiz Viana Filho: 180 Anos de História Viva: 1826 - 2006', NULL, '923.281', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2006', '1 ed.', NULL, 'ex.1', 'p.210', 1, '30.00', 0, '003.', NULL, 1),
(1260, '2022-01-01', 'ALFONSO, José Luiz Hernández (Org.)', 'Retratos da Brasilidade', NULL, '730.074', '978.85.98864.58.7', 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '2014', '1 ed.', NULL, 'ex.1', 'p.67', 1, '25.00', 0, '003.', NULL, 1),
(1261, '2022-01-01', 'WEHLING, Arno (org.)', 'O império em Brasília: 190 anos da Assemableia Constituinte de 1923', NULL, '923.281', '978.85.98864.56.3', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2013', '1 ed.', NULL, 'ex.1', 'p.183', 1, '25.00', 0, '003.', NULL, 1),
(1262, '2022-01-01', 'MOTA, Carlos Guilherme ', 'Brasil revisitado: palavra e imagens', NULL, '981', NULL, 'São Paulo', 'Rios Editora', NULL, NULL, '1989', '1 ed.', NULL, 'ex.1', 'p.199', 1, '25.00', 0, '003.', NULL, 1),
(1263, '2022-01-01', 'Silvia Esteves (Coord.)', 'Coleção Sistematização - Equações de Co-responsabilidade - Volume 1', NULL, '323', NULL, 'São Paulo', 'Rios Editora', NULL, NULL, '2017', NULL, 'v.1', 'ex.1', 'p.108', 1, '25.00', 0, '003.', NULL, 1),
(1264, '2022-01-01', 'Silvia Esteves (Coord.)', 'Coleção Sistematização - Alianças e parcerias com escolas públicas - Volume 2', NULL, '323', NULL, 'São Paulo', 'Rios Editora', NULL, NULL, '2017', NULL, 'v.2', 'ex.1', 'p.72', 1, '25.00', 0, '003.', NULL, 1),
(1265, '2022-01-01', 'Silvia Esteves (Coord.)', 'Coleção Sistematização - Comunidades com espaços formativos - Volume 3', NULL, '323', NULL, 'São Paulo', 'Rios Editora', NULL, NULL, '2017', NULL, 'v.3', 'ex.1', 'p.56', 1, '25.00', 0, '003.', NULL, 1),
(1266, '2022-01-01', 'Silvia Esteves (Coord.)', 'Coleção Sistematização - Preparação para o mundo do trabalho- Volume 4', NULL, '323', NULL, 'São Paulo', 'Rios Editora', NULL, NULL, '2017', NULL, 'v.4', 'ex.1', 'p.68', 1, '25.00', 0, '003.', NULL, 1),
(1267, '2022-01-01', 'VELO, Lucio V', 'O Mundo dos paraísos fiscais financeiros', NULL, '657', '85.204.0921.0', 'São Paulo', 'Manole', NULL, NULL, NULL, NULL, 'v.1', 'ex.1', 'p.168', 1, '25.00', 0, '003.', NULL, 1),
(1268, '2022-01-01', 'Câmara de Comércio e Indústria Brasil- Alemanha', '100 Anos - O futuro é a nossa tradição (1916-2016)', NULL, '928', '978.85.85577.42.1', 'São Paulo', '***', NULL, NULL, '2016', '1 ed.', NULL, 'ex.1', 'p.224', 1, '25.00', 0, '003.', NULL, 1),
(1269, '2022-01-01', 'PONTES, José Alfredo Vidigal ', '1968, do sonho ao pesadelo', NULL, '070.484', NULL, 'São Paulo', 'Jornal o Estado de São Paulo', NULL, NULL, '1968', '1 ed.', NULL, 'ex.2', 'p.63', 1, '25.00', 0, '003.', NULL, 1),
(1270, '2022-01-01', 'Michel Temer ', 'Elementos de direito constitucional (16ª Edição)', NULL, '342', '85.7420.169.3', 'São Paulo', 'Malheiros', NULL, NULL, '2000', NULL, 'v.1', 'ex.1', 'p.222', 1, '25.00', 0, '003.', NULL, 1),
(1271, '2022-01-01', 'Vicente Greco Filho', 'Direito processual civil brasileiro - Volume 2', '347.9(81)/ 374.9', '347', '85.02.00401.8', 'São Paulo', 'Saraiva', NULL, NULL, '1992', NULL, 'v.2', 'ex.1', 'p.420', 1, '25.00', 0, '003.', NULL, 1),
(1272, '2022-01-01', 'IBAM', 'Caderno do IBAM 3 - Elaboração do Plano plurianul', NULL, '336', NULL, 'Rio de Janeiro', 'IBAM', NULL, NULL, '2001', '1 ed.', 'v.3', 'ex.1', 'p.64', 1, '10.00', 0, '003.', NULL, 1),
(1273, '2022-01-01', 'Irene Batista Muakad', 'O infanticífio: Análise da doutrina médico-legal e da prática judiciária', '343.622', '369', '85.87739.28.x', 'São Paulo', 'Mackenzie', NULL, NULL, '2002', '1 ed.', 'v.1', 'ex.1', 'p.198', 1, '25.00', 0, '003.', NULL, 1),
(1274, '2022-01-01', 'TOURAINE, Alain ', 'Crítica da modernidade ', NULL, '149.9', '85.326.1164.8', 'Petrópolis', 'Vozes Editora', NULL, NULL, '1994', '1 ed.', NULL, 'ex.1', 'p.431', 1, '25.00', 0, '003.', NULL, 1),
(1275, '2022-01-01', 'Theobaldo Miranda Santos', 'Curso de Psicologia e Pedagogia - Noções de Metodologia do Ensino Primário - Volume 10', NULL, '370.1', NULL, 'São Paulo', 'Companhia das Letras', NULL, NULL, '1967', '1 ed.', 'v.10', 'ex.1', 'p.251', 1, '25.00', 0, '003.', NULL, 1),
(1276, '2022-01-01', 'CORBISIER, Roland ', 'Introdução à Filosofia - Tomo I', '101.1', '107', NULL, 'Rio de Janeiro', 'Civilização Brasileira', NULL, NULL, '1986', '1 ed.', NULL, 'ex.1', 'p.243', 1, '25.00', 0, '003.', NULL, 1),
(1277, '2022-01-01', 'Emir Farhat', 'Educação, a nova ideologia', NULL, '370.1', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1975', '1 ed.', 'v.1', 'ex.1', 'p.326', 1, '25.00', 0, '003.', NULL, 1),
(1278, '2022-01-01', 'PRETI, Dino ', 'Sociolinguística: os níveis de fala, um estudo sociolinguístico do diálogo literário', NULL, '401', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1977', '1 ed.', NULL, 'ex.1', 'p.170', 1, '25.00', 0, '003.', NULL, 1),
(1279, '2022-01-01', 'BORBA, Francisco da Borba', 'Introdução aos estudos lingüístico', NULL, '410', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1979', '1 ed.', NULL, 'ex.1', 'p.316', 1, '25.00', 0, '003.', NULL, 1),
(1280, '2022-01-01', 'Luiz Pereira e Marialice M. Faracchi', 'Educação e sociedade', NULL, '370.193', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1979', '1 ed.', 'v.1', 'ex.1', 'p.449', 1, '25.00', 0, '003.', NULL, 1),
(1281, '2022-01-01', 'Lúcia Marques Pinheiro e Maria do Carmo Marques Pinheiro', 'Prática na formação e no aperfeiçoamento do magistério de primeiro grau', NULL, '370.1', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1977', '1 ed.', 'v.1', 'ex.1', 'p.406', 1, '25.00', 0, '003.', NULL, 1),
(1282, '2022-01-01', 'BARROS, Roque Spencer Maciel de ', 'A evolução do Pensamento de Pereira Barreto', NULL, '100', NULL, 'São Paulo', 'Grijalbo Ltda', NULL, NULL, '1967', '1 ed.', 'v.1', 'ex.1', 'p.271', 1, '25.00', 0, '003.', NULL, 1),
(1283, '2022-01-01', 'BROWN, Dee Alexander', 'Coleção L&PM Pocket (338) - Enterrem meu na curva do rio', '94 (738)', '978', '978.85.254.1293.5', 'Porto Alegre', 'L&PM Editora', 'n. 338', NULL, '2010', '1 ed.', NULL, 'ex.1', 'p.464', 1, '25.00', 0, '003.', NULL, 1),
(1284, '2022-01-01', 'H. C. Wells', 'Coleção L&PM Pocket (913) - Uma breve história do mundo', '94 (100)', '909', '978.85.254.2099.2', 'Porto Alegre', 'L&PM Editora', 'n.916', NULL, '2011', '1 ed.', NULL, 'ex.1', 'p.384', 1, '25.00', 0, '003.', NULL, 1),
(1285, '2022-01-01', 'Sílvio César Arouck Gemaque', 'Série Monografias do CEJ - Volume 12 - A necessidade influência do processo penal internacional no processo penal brasileiro', NULL, '341.4', '978.85.85572.92.1', 'Brasília-DF', 'Centro de Estudos Jurídicos', NULL, NULL, '2011', '1 ed.', 'v.12', 'ex.1', 'p.230', 1, '25.00', 0, '003.', NULL, 1),
(1286, '2022-01-01', 'MIOTELLO, Valdemir  (Org.)', 'Jogos de Linguagem ', NULL, '410', NULL, 'São Carlos -SP', 'Compacta Editora', NULL, NULL, '2005', '1 ed.', NULL, 'ex.1', 'p.186', 1, '25.00', 0, '003.', NULL, 1),
(1287, '2022-01-01', 'José Norival Braga', 'Tecnologia da Dinâmica de grupo: uma nova didática', NULL, '384', NULL, 'São Paulo', 'Loyola', NULL, NULL, NULL, '1 ed.', 'v.1', 'ex.1', 'p.119', 1, '25.00', 0, '003.', NULL, 1),
(1288, '2022-01-01', 'Eliana Marques Zanata', 'Cadernos de Docência na Educação Básica', NULL, '370.1', '978.85.7983.398.4', 'São Paulo', 'Cultura Acadêmica', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.188', 1, '25.00', 0, '003.', NULL, 1),
(1289, '2022-01-01', 'Jorge Werthein', 'Meios de Comunicação: realidade e mito', NULL, '301.16098', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1979', '1 ed.', 'v.1', 'ex.1', 'p.277', 1, '25.00', 0, '003.', NULL, 1),
(1290, '2022-01-01', 'MCGARVEY, Patrick J. ', 'Cia: o Mito e a loucura', NULL, '808', NULL, 'São Paulo', 'ArteNova ', NULL, NULL, '1972', '1 ed.', NULL, 'ex.1', 'p.208', 1, '25.00', 0, '003.', NULL, 1),
(1291, '2022-01-01', 'PICON, Gaëtan ', 'Introdução a uma estética da literatura - Volume 1 - O escritor e sua sombra', NULL, '469', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1970', '1 ed.', NULL, 'ex.1', 'p.245', 1, '25.00', 0, '003.', NULL, 1),
(1292, '2022-01-01', 'Alfred de Musset', 'Coleção Mestres Pensadores: A confissão de um filho do século', NULL, '808', NULL, 'São Paulo', 'Escala Editora', NULL, NULL, NULL, '1 ed.', 'v.1', 'ex.1', 'p.173', 1, '25.00', 0, '003.', NULL, 1),
(1293, '2022-01-01', 'PAZZAGLINI FILHO, Marino', 'Crimes de responsabilidade dos prefeitos', '343.53:336.126.2(81)', '657', '978.85.224.5569.4', 'São Paulo', 'Atlas S.A.', NULL, NULL, '2009', '1 ed.', NULL, 'ex.1', 'p.138', 1, '25.00', 0, '003.', NULL, 1),
(1294, '2022-01-01', 'Basílio de Oliveira', 'Concubinato: novos rumos: direitos e deveres dos conviventes na união estável', NULL, '346.81016', '85.353.0045.7', 'Rio de Janeiro', 'Freitas Bastos', NULL, NULL, '1997', '1 ed.', 'v.1', 'ex.1', 'p.398', 1, '25.00', 0, '003.', NULL, 1),
(1295, '2022-01-01', 'José Cretella Júnior', 'Prática do processo administrativo', '35.077.3(81)', '342', '85.203.1631.x', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '1998', '1 ed.', 'v.1', 'ex.1', 'p.207', 1, '25.00', 0, '003.', NULL, 1),
(1296, '2022-01-01', 'Derly Barreto e Silva Junior', 'Controle dos Atos parlamentares pelo poder judiciário', NULL, '340', '85.7420.511.7', 'São Paulo', 'Malheiros', NULL, NULL, '2003', '1 ed.', 'v.1', 'ex.1', 'p.207', 1, '25.00', 0, '003.', NULL, 1),
(1297, '2022-01-01', 'José Fábio Rodrigues Maciel e Renan Aguiar', 'Coleção roteiros jurídicos: História do direito', '34(091)', '340.1', '978.85.02.07672.3', 'São Paulo', 'Saraiva', NULL, NULL, '2009', '1 ed.', 'v.1', 'ex.1', 'p.172', 1, '25.00', 0, '003.', NULL, 1),
(1298, '2022-01-01', 'Marcelo Galante', 'Para aprender direito - Volume 4 - Direito Constitucional', '342(81)(049.1)', '342', '978.85.88749.81.8', 'São Paulo', 'Barros, Fischer e Associaldos ', NULL, NULL, '2005', '1 ed.', 'v.1', 'ex.1', 'p.256', 1, '25.00', 0, '003.', NULL, 1),
(1299, '2022-01-01', 'Rodulf Ihering Von', 'A luta pelo Direito', '340.11/ 340.12', '340.1', '85.7436.006.6', 'Rio de Janeiro', 'Forense Editora', NULL, NULL, '1999', '1 ed.', 'v.1', 'ex.1', 'p.88', 1, '25.00', 0, '003.', NULL, 1),
(1300, '2022-01-01', 'Rodolfo de Camargo Mancuso', 'Ação civil pública: em defesa do meio ambiente, patrimônio cultural e dos consumidores', '347.922(81)(094.56)', '347', '85.203.1386.8', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '1996', '1 ed.', 'v.1', 'ex.1', 'p.310', 1, '25.00', 0, '003.', NULL, 1),
(1301, '2022-01-01', 'Ronaldo Brêtas de Carvalho Dias', 'Fraude no processo civil', NULL, '345.810263', '85.7308.249.6', 'Belo Horizonte', 'Del Rey', NULL, NULL, '1998', NULL, 'v.1', 'ex.1', 'p.152', 1, '25.00', 0, '003.', NULL, 1),
(1302, '2022-01-01', 'Paulo Cezar Pinheiro Carneiro', 'A atuação do ministério público na área cível - Temas Diversos', NULL, '353.4', NULL, 'Rio de Janeiro', 'Lumen Juris Editora', NULL, NULL, '1996', NULL, 'v.1', 'ex.1', 'p.392', 1, '25.00', 0, '003.', NULL, 1),
(1303, '2022-01-01', 'João Roberto Parizatto', 'Dos embargos de terceiro: Doutrina, Jurisprudência, prática forense', NULL, '349', NULL, 'Leme-SP', 'Led - Editora de Direito', NULL, NULL, '1997', NULL, 'v.1', 'ex.1', 'p.315', 1, '25.00', 0, '003.', NULL, 1),
(1304, '2022-01-01', 'Luiz Rodrigues Wambier', 'Liquidação de sentença', '347.952', '345.07', NULL, 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '1997', NULL, 'v.1', 'ex.1', 'p.341', 1, '25.00', 0, '003.', NULL, 1),
(1305, '2022-01-01', 'Berenice Soubhie Nogueira Magri', 'Coleção estudos de direito de processo Eutico Tullio Liebman - Volume 41: Ação anulatória: art 486 do CPC', '347.922.6(81)', '347', '85.203.1719.7', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '1999', NULL, 'v.1', 'ex.1', 'p.262', 1, '25.00', 0, '003.', NULL, 1),
(1306, '2022-01-01', 'José Eduardo Carreira Alvim', 'Tutela específica das obrigações de fazer e não fazer', '347.922:347.412', '341.46', NULL, 'Belo Horizonte', 'Del Rey', NULL, NULL, '1997', '1 ed.', 'v.1', 'ex.1', 'p.245', 1, '25.00', 0, '003.', NULL, 1),
(1307, '2022-01-01', 'Athos Gusmão Carneiro', 'Audiência de instrução e julgamento e audiências preliminares', NULL, '345.05', NULL, 'Rio de Janeiro', 'Forense Editora', NULL, NULL, '1995', '1 ed.', 'v.1', 'ex.1', 'p.376', 1, '25.00', 0, '003.', NULL, 1),
(1308, '2022-01-01', 'Cláudio Antonio Soares Levada', 'Liquidação de danos morais', '347.513', '347', '85.85789.01.8', 'Campinhas-SP', 'Copola Livros', NULL, NULL, '1995', '1 ed.', 'v.1', 'ex.1', 'p.145', 1, '25.00', 0, '003.', NULL, 1),
(1309, '2022-01-01', 'Brasil', 'Constituição da República Federativa do Brasil', '342.4(81)\"1988\"', '348', '978.85.02.08938.9', 'São Paulo', 'Saraiva', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.432', 1, '25.00', 0, '003.', NULL, 1),
(1310, '2022-01-01', 'Brasil', 'Estatuto do Idoso (2011)', '3.053.9(81)(094)', '348', '978.85.736.5882.8', 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.121', 1, '0.00', 1, '003.', NULL, 1),
(1311, '2022-01-01', 'Câmara Municipal de Itapevi', 'Lei Organica do Município de Itapevi-SP', NULL, '348', NULL, 'Itapevi-SP', 'Câmara Muncipal de Itapevi', NULL, NULL, '2018', '1 ed.', 'v.1', 'ex.1', 'p.53', 3, '0.00', 1, '**', NULL, 1),
(1312, '2022-01-01', 'Câmara Municipal de Itapevi', 'Lei Organica do Município de Itapevi-SP', NULL, '348', NULL, 'Itapevi-SP', 'Câmara Muncipal de Itapevi', NULL, NULL, '2018', '1 ed.', 'v.1', 'ex.2', 'p.53', 3, '0.00', 1, '**', NULL, 1),
(1313, '2022-01-01', 'Câmara Municipal de Itapevi', 'Lei Organica do Município de Itapevi-SP', NULL, '348', NULL, 'Itapevi-SP', 'Câmara Muncipal de Itapevi', NULL, NULL, '2018', '1 ed.', 'v.1', 'ex.3', 'p.53', 3, '0.00', 1, '**', NULL, 1),
(1314, '2022-01-01', 'Câmara Municipal de Itapevi', 'Lei Organica do Município de Itapevi-SP', NULL, '348', NULL, 'Itapevi-SP', 'Câmara Muncipal de Itapevi', NULL, NULL, '2018', '1 ed.', 'v.1', 'ex.4', 'p.53', 3, '0.00', 1, '**', NULL, 1),
(1315, '2022-01-01', 'Câmara Municipal de Itapevi', 'Lei Organica do Município de Itapevi-SP', NULL, '348', NULL, 'Itapevi-SP', 'Câmara Muncipal de Itapevi', NULL, NULL, '2018', '1 ed.', 'v.1', 'ex.5', 'p.53', 3, '0.00', 1, '**', NULL, 1),
(1316, '2022-01-01', 'Senado Federal', 'Administração Pública - Normas  e regulamentos', NULL, '348', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.291', 1, '15.00', 0, '003.', NULL, 1),
(1317, '2022-01-01', 'Senado Federal', 'Administração Pública - Normas  e regulamentos', NULL, '348', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.291', 1, '15.00', 0, '003.', NULL, 1),
(1318, '2022-01-01', 'SELEME, Robson ', 'Automação da produção: abordadem gerencial', NULL, '658.514', '8587053', 'Curitiba-PR', 'Ibpex', NULL, NULL, '2006', '1 ed.', 'v.1', 'ex.1', 'p.215', 1, '15.00', 0, '003.', NULL, 1),
(1319, '2022-01-01', 'BEZERRA, Cicero Aparecido', 'Série Administração da Produção: Técnicas de planejamento, programação e controle de produção e introdução à programação linear', NULL, '658.5', '978.85.8212.988.3', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2014', '1 ed.', NULL, 'ex.1', 'p.229', 1, '20.00', 0, '003.', NULL, 1),
(1320, '2022-01-01', 'SELEME, Robson ', 'Série Administração da Produção: Controle da qualidade: as ferramentas essenciais', NULL, '658.4013', '978.85.65704.85.4', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.181', 1, '20.00', 0, '003.', NULL, 1),
(1321, '2022-01-01', 'COSTA JÚNIOR, Eudes Luiz ', 'Série Administração da Produção: Gestão em processos produtivos', NULL, '658.5', '978.85.8212.243.3', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.151', 1, '20.00', 0, '003.', NULL, 1),
(1322, '2022-01-01', 'SELEME, Robson ', 'Métodos e tempos: racionalizando a produção de bens e serviços', NULL, '658.5', '978.85.7838.318.3', 'Curitiba-PR', 'Ibpex', NULL, NULL, '2009', '1 ed.', NULL, 'ex.1', 'p.160', 1, '20.00', 0, '003.', NULL, 1),
(1323, '2022-01-01', 'Waverli Maia Matarazzo (Org.)', 'Cadernos Didáticos Metodista: Desafios da sustentabilidadae: ecologia, políticas e tecnologias', NULL, '363.7', '978.85.7814.161.5', 'São Bernardo do Campo -SP', 'Universidades', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.184', 1, '20.00', 0, '003.', NULL, 1),
(1324, '2022-01-01', 'Waverli Maia Matarazzo (Org.)', 'Cadernos Didáticos Metodista: Ferramentas para a gestão ambiental: conhecendo, medindo e prevendo os impactos ambientais e educando para a sustentabilidade', NULL, '301.31', '978.85.7814.191.2', 'São Bernardo do Campo -SP', 'Universidades', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.204', 1, '20.00', 0, '003.', NULL, 1),
(1325, '2022-01-01', 'Carlos Magno Mendes', 'Bacharelado em Adminstração Pública: Introdução à economia', '330', '351', '978.85.61608.72.9', 'Brasília-DF', 'Universidades', NULL, NULL, '2009', '1 ed.', 'v.1', 'ex.1', 'p.170', 1, '20.00', 0, '003.', NULL, 1),
(1326, '2022-01-01', 'CAMARGO, Denise de ', 'Bacharelado em Adminstração Pública: Psicologia organizacional', '65', '351', '978.85.61608.76.7', 'Brasília-DF', 'Capes', NULL, NULL, '2009', '1 ed.', 'v.1', 'ex.1', 'p.126', 1, '22.10', 0, '002.', NULL, 1),
(1327, '2022-01-01', 'Denise de Camargo', 'Bacharelado em Adminstração Pública: Psicologia organizacional', '65', '351', '978.85.61608.76.7', 'Brasília-DF', 'Capes', NULL, NULL, '2009', NULL, 'v.1', 'ex.2', 'p.126', 1, '10.00', 0, '003.', NULL, 1),
(1328, '2022-01-01', 'ASSMANN, Selvino José ', 'Bacharelado em Adminstração Pública: Filosofia e Ética', '174', '351', '978.85.61608.74.3', 'Brasília-DF', 'Capes', NULL, NULL, '2009', NULL, 'v.1', 'ex.1', 'p.166', 1, '22.10', 0, '002.', NULL, 1),
(1329, '2022-01-01', 'Selvino José Assmann', 'Bacharelado em Adminstração Pública: Filosofia e Ética', '174', '351', '978.85.61608.74.3', 'Brasília-DF', 'Capes', NULL, NULL, '2009', NULL, 'v.1', 'ex.2', 'p.166', 1, '10.00', 0, '003.', NULL, 1),
(1330, '2022-01-01', 'Ricardo Corrêa Coelho', 'Bacharelado em Adminstração Pública: Ciência Política', '32', '351', '978.85.7988.007.0', 'Brasília-DF', 'Capes', NULL, NULL, '2010', NULL, 'v.1', 'ex.1', 'p.159', 1, '10.00', 0, '003.', NULL, 1),
(1331, '2022-01-01', 'HEINECK, Luiz Fernado Mahlmann ', 'Bacharelado em Adminstração Pública: Material complementar: macroeconomia', '330.101.541', '351', '978.85.7988.008.7', 'Brasília-DF', 'Capes', NULL, NULL, '2010', NULL, 'v.1', 'ex.1', 'p.178', 1, '22.10', 0, '002.', NULL, 1),
(1332, '2022-01-01', 'Luiz Fernado Mahlmann Heineck', 'Bacharelado em Adminstração Pública: Material complementar: macroeconomia', '330.101.541', '351', '978.85.7988.008.7', 'Brasília-DF', 'Capes', NULL, NULL, '2010', NULL, 'v.1', 'ex.2', 'p.178', 1, '10.00', 0, '003.', NULL, 1),
(1333, '2022-01-01', 'BORGES, Fernando Tadeu de Miranda e CHADAREVIAN, Pedro Caldas', 'Bacharelado em Adminstração Pública: Economia brasileira', '338(81)', '351', '978.85.61608.79.7', 'Brasília-DF', 'Capes', NULL, NULL, '2010', NULL, 'v.1', 'ex.1', 'p.140', 1, '22.10', 0, '002.', NULL, 1),
(1334, '2022-01-01', 'Fernando Tadeu de Miranda Borges  e Pedro Caldas Chadarevian', 'Bacharelado em Adminstração Pública: Economia brasileira', '338(81)', '351', '978.85.61608.79.7', 'Brasília-DF', 'Capes', NULL, NULL, '2010', NULL, 'v.1', 'ex.2', 'p.140', 1, '10.00', 0, '003.', NULL, 1),
(1335, '2022-01-01', 'JUNQUILHO, Gelson Silva ', 'Bacharelado em Adminstração Pública: Teoria da administração pública', '65.01', '351', '978.85.7988.026.1', 'Brasília-DF', 'Capes', NULL, NULL, '2010', NULL, 'v.1', 'ex.1', 'p.182', 1, '22.10', 0, '002.', NULL, 1),
(1336, '2022-01-01', 'Gelson Silva Junquilho', 'Bacharelado em Adminstração Pública: Teoria da administração pública', '65.01', '351', '978.85.7988.026.1', 'Brasília-DF', 'Capes', NULL, NULL, '2010', NULL, 'v.1', 'ex.2', 'p.182', 1, '10.00', 0, '003.', NULL, 1),
(1337, '2022-01-01', 'SILVA, Golias ', 'Bacharelado em Adminstração Pública: Sociologia organizacional', '65', '351', '978.85.7988.086.5', 'Brasília-DF', 'Capes', NULL, NULL, '2010', NULL, 'v.1', 'ex.1', 'p.152', 1, '22.10', 0, '002.', NULL, 1),
(1338, '2022-01-01', 'TAVARESL, Marcelo ', 'Bacharelado em Adminstração Pública: Estatística aplicada à administração', '519.2:65', '351', '978.85.7988.099.5', 'Brasília-DF', 'Capes', NULL, NULL, '2011', NULL, 'v.1', 'ex.1', 'p.222', 1, '22.10', 0, '002.', NULL, 1),
(1339, '2022-01-01', 'OLIVO, Luiz Carlos Cancelier de', 'Bacharelado em Adminstração Pública: Direito administrativo', '341.3', '351', '978.85.7988.092.6', 'Brasília-DF', 'Capes', NULL, NULL, '2010', NULL, 'v.1', 'ex.1', 'p.162', 1, '22.10', 0, '002.', NULL, 1),
(1340, '2022-01-01', 'Jader Cristiano Magalhães Albuquerque', 'Bacharelado em Adminstração Pública: Sistemas de informação e comunicação no setor púlblico', '659.2', '351', '978.85.7988.098.8', 'Brasília-DF', 'Capes', NULL, NULL, '2011', NULL, 'v.1', 'ex.1', 'p.150', 1, '22.10', 0, '002.', NULL, 1),
(1341, '2022-01-01', 'João Rogério Sanson', 'Bacharelado em Adminstração Pública: Teoria das finanças públicas', '336.1/5', '351', '978.85.7988.100.8', 'Brasília-DF', 'Capes', NULL, NULL, '2011', NULL, 'v.1', 'ex.1', 'p.132', 1, '22.10', 0, '003.', NULL, 1),
(1342, '2022-01-01', 'Maria Ceci Araujo Misoczky', 'Bacharelado em Adminstração Pública: Planejamento e Programa ção na Administração pública', '65.01', '351', '978.85.7988.127.5', 'Brasília-DF', 'Capes', NULL, NULL, '2011', NULL, 'v.1', 'ex.1', 'p.184', 1, '22.10', 0, '003.', NULL, 1),
(1343, '2022-01-01', 'Rogério de Alvarenga Rosa', 'Bacharelado em Adminstração Pública: Gestão de operações e logística I', '658.78', '351', '978.85.7988.088.9', 'Brasília-DF', 'Capes', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.160', 1, '22.10', 0, '003.', NULL, 1),
(1344, '2022-01-01', 'Marurício Fernandes Pereira', 'Bacharelado em Adminstração Pública: Administração Estratégica', '658.011.2', '351', '978.85.7988.133.6', 'Brasília-DF', 'Capes', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.168', 1, '22.10', 0, '003.', NULL, 1),
(1345, '2022-01-01', 'Ivan Antonio Pinheiro', 'Bacharelado em Adminstração Pública: Gestão da Regulação', '35', '351', '978.85.7988.163.3', 'Brasília-DF', 'Capes', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.96', 1, '22.10', 0, '003.', NULL, 1),
(1346, '2022-01-01', 'Maria Paula Gomes dos Santos', 'Bacharelado em Adminstração Pública: Políticas públicas e sociedade', '35', '351', '978.85.7988.165.7', 'Brasília-DF', 'Capes', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.100', 1, '22.10', 0, '003.', NULL, 1),
(1347, '2022-01-01', 'Altamiro Damian Préve', 'Bacharelado em Adminstração Pública: Organização, processo e tomada de decisão', '65.01', '351', '978.85.7988.096.4', 'Brasília-DF', 'Capes', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.186', 1, '22.10', 0, '003.', NULL, 1),
(1348, '2022-01-01', 'CORRIGAN, Paul ', 'Shakespeare na Administração de negócios: Lições de liderança para gerentes e executivos que ambicionam atuar como verdadeiros líderes no mundo de hoje', NULL, '650', '85.346.1164.5', 'São Paulo', 'Makron Books Ltda', NULL, NULL, '2000', '1 ed.', NULL, 'ex.1', 'p.211', 1, '20.00', 0, '003.', NULL, 1),
(1349, '2022-01-01', 'WANKE, Peter F. ', 'Coleção Coppead de Administração: Logística e transporte de cargas no Brasil: produção e eficiência no século XXI', NULL, '658.4', '978.85.224.5930.8', 'São Paulo', 'Atlas S.A.', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p.179', 1, '20.00', 0, '003.', NULL, 1),
(1350, '2022-01-01', 'Brasil', 'Intercâmbio comercial do agronegócio: principais mercados de destino - Edição de 2010', '339.56', '327', '978.85.7991.038.8', 'Brasília-DF', 'Ministérios', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.443', 1, '20.00', 0, '003.', NULL, 1),
(1351, '2022-01-01', 'Brasil', 'Intercâmbio comercial do agronegócio: principais mercados de destino - Edição de 2011', '339.56', '327', '978.85.7991.057.9', 'Brasília-DF', 'Ministérios', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.459', 1, '20.00', 0, '003.', NULL, 1),
(1352, '2022-01-01', 'BOSSIDY, Larry ', 'Execução: a disciplina para atingir resultados', '658', '658', '85.352.1538.7', 'Rio de Janeiro', 'Elsevier', NULL, NULL, '2005', '1 ed.', NULL, 'ex.1', 'p.261', 1, '20.00', 0, '003.', NULL, 1),
(1353, '2022-01-01', 'CHIAVENATO, Idalberto ', 'Administração nos novos tempos', '1(038)', '658', '85.352.0428.8', 'Rio de Janeiro', 'Campus Editora', NULL, NULL, '1999', '1 ed.', NULL, 'ex.1', 'p.710', 1, '20.00', 0, '003.', NULL, 1),
(1354, '2022-01-01', 'CHIAVENATO, Idalberto ', 'Administração Geral e pública', '35(81)(079)', '658', '978.85.204.3245.7', 'Barueri-SP', 'Manole', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.522', 1, '20.00', 0, '003.', NULL, 1),
(1355, '2022-01-01', 'Brasil', 'Brasil Direitos Humanos, 2008: a Realidade do país aos 60 anos da Declaração Universal', NULL, '341.481', '978.85.60877.03.4', 'Brasília-DF', 'Secretarias ', NULL, NULL, '2008', NULL, 'v.1', 'ex.1', 'p.285', 1, '20.00', 0, '003.', NULL, 1),
(1356, '2022-01-01', 'Senado Federal', 'O senado nos trilhos da História: Reforma adminsitrativa do Senado Federal, Análise crítica e propostas alternativas0', NULL, '320', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, NULL, '1 ed.', 'v.1', 'ex.1', 'p.122', 1, '20.00', 0, '003.', NULL, 1),
(1357, '2022-01-01', 'CARVALHAL, Eugenio do ', 'Série Gerenciamento de projetos: Negociação e administração de conflitos', NULL, '658.3145', '978.85.225.0558.6', 'Rio de Janeiro', 'FGV Editora', NULL, NULL, '2006', '1 ed.', NULL, 'ex.1', 'p.184', 1, '22.10', 0, '003.', NULL, 1),
(1358, '2022-01-01', 'SALLES JÚNIOR, Carlos Alberto Corrêa', 'Série Gerenciamento de projetos: Gerenciamento de riscos em projetos', NULL, '658.155', '978.85.225.0814.3', 'Rio de Janeiro', 'FGV Editora', NULL, NULL, '2010', '2 ed.', NULL, 'ex.1', 'p.176', 1, '22.10', 0, '003.', NULL, 1),
(1359, '2022-01-01', 'VALLE, André Bittencourt do ', 'Série Gerenciamento de projetos: Fundamentos do gerenciamento de projetos', NULL, '658.404', '978.85.225.0612.5', 'Rio de Janeiro', 'FGV Editora', NULL, NULL, '2007', '1 ed.', NULL, 'ex.1', 'p.172', 1, '22.10', 0, '003.', NULL, 1),
(1360, '2022-01-01', 'MACÊDO, Ivanildo Izaias de ', 'Série Gestão Empresarial: Aspectos comportamentais da gestão de pessoas', NULL, '658.3', '978.85.225.0607.1', 'Rio de Janeiro', 'FGV Editora', NULL, NULL, '2007', '1 ed.', NULL, 'ex.1', 'p.152', 1, '22.10', 0, '003.', NULL, 1),
(1361, '2022-01-01', 'ROCHA-PINTO, Sandra Regina da ', 'Série Gestão Empresarial: Dimensões funcionais da gestão de pessoas', NULL, '658.3', '85.225.0410.5', 'Rio de Janeiro', 'FGV Editora', NULL, NULL, '2007', '1 ed.', NULL, 'ex.1', 'p.145', 1, '22.10', 0, '003.', NULL, 1),
(1362, '2022-01-01', 'ABREU FILHO, José Carlos Franco de ', 'Série Gestão Empresarial: Finanças corporativas', NULL, '658.15', '978.85.225.0675.0', 'Rio de Janeiro', 'FGV Editora', NULL, NULL, '2007', '1 ed.', NULL, 'ex.1', 'p.151', 1, '22.10', 0, '003.', NULL, 1),
(1363, '2022-01-01', 'SARNEY, José', 'Senado - 185 anos', NULL, '923.281', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p.93', 1, '25.00', 0, '003.', NULL, 1),
(1364, '2022-01-01', 'Fernando Rodrigues Martins', 'Controle de patrimônio público', '351.711(81)', '342', '85.203.1865.7', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '2000', '1 ed.', NULL, 'ex.1', 'p.168', 1, '25.00', 0, '003.', NULL, 1),
(1365, '2022-01-01', 'ELLIS, Albert', 'Liderança executiva: uma proposta racional', NULL, '658.4', NULL, 'Rio de Janeiro', 'Record', NULL, NULL, '1972', '1 ed.', NULL, 'ex.1', 'p.200', 1, '25.00', 0, '003.', NULL, 1),
(1366, '2022-01-01', 'REINA, Eduardo', 'Cativeiro sem fim: as histórias dos bebês, crianças e adolescnetes sequestrados pela ditadura militar no Brasil', '94(81).088', '981.063', '978.85.7939.575.6', 'São Paulo', 'Alameda Editora', NULL, NULL, '2019', '1 ed.', NULL, 'ex.1', 'p.302', 1, '25.00', 0, '003.', NULL, 1),
(1367, '2022-01-01', 'SALDANHA, Fernando ', 'Introdução a planos de continuidade e contingência operacional', NULL, '658.4', NULL, 'Rio de Janeiro', 'Papel & virtural', NULL, NULL, '2000', '1 ed.', NULL, 'ex.1', 'p.277', 1, '25.00', 0, '003.', NULL, 1),
(1368, '2022-01-01', 'CHOWDHURY, Subir ', 'Administração no Século XXI: o modo de gerenciar hoje e no futuro', NULL, '658.4', '85.346.1435.0', 'São Paulo', 'Pearson ', NULL, NULL, '2003', '1 ed.', NULL, 'ex.1', 'p.292', 1, '25.00', 0, '003.', NULL, 1),
(1369, '2022-01-01', 'MAXIMIANO, Antonio Cesar Amaru ', 'Teoria geral da administração: da escola científica à competitividade em economia globalizada', NULL, '658.001', '85.224.1610.9', 'São Paulo', 'Atlas S.A.', NULL, NULL, '1997', '1 ed.', NULL, 'ex.1', 'p.371', 1, '25.00', 0, '003.', NULL, 1),
(1370, '2022-01-01', 'MONTANA, Patrick J. ', 'Administração', NULL, '658', '85.02.02353.5', 'São Paulo', 'Saraiva', NULL, NULL, '2000', '1 ed.', NULL, 'ex.1', 'p.475', 1, '25.00', 0, '003.', NULL, 1),
(1371, '2022-01-01', 'ARAÚJO, Luiz César Gonçalves de', 'Teoria Geral da administração: orientações para escolha de um caminho profissional', NULL, '658', '978.85.224.6025.0', 'São Paulo', 'Atlas S.A.', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p.305', 1, '25.00', 0, '003.', NULL, 1),
(1372, '2022-01-01', 'DRUCKER, Peter Ferdinand', 'Desafios gerenciais para o século XXI', NULL, '658.00905', '85221', 'São Paulo', 'Pioneira Editora', NULL, NULL, '1999', '1 ed.', NULL, 'ex.1', 'p.168', 1, '25.00', 0, '003.', NULL, 1),
(1373, '2022-01-01', 'José Carlos de Moraes Salles', 'A desapropriação à luz da doutrina e da jurisprudência', '347.234.1(81)', '340', '85.203.1754.5', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '2000', '1 ed.', 'v.1', 'ex.1', 'p.1152', 1, '25.00', 0, '003.', NULL, 1),
(1374, '2022-01-01', 'Vicente Marotta Rangel (Org.)', 'Direito e relações internacionais', '341', '327', '85.203.1425.2', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '1997', '1 ed.', 'v.1', 'ex.1', 'p.799', 1, '25.00', 0, '003.', NULL, 1),
(1375, '2022-01-01', 'CAMPOS, Luiz Fernando Rodrigues ', ' Logística: teia de relações - Série Logística Organizacional: ', NULL, '658.78', '978.85.8212.606.6', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2013', '20 ed.', NULL, 'ex.1', 'p.160', 1, '25.00', 0, '003.', NULL, 1),
(1376, '2022-01-01', 'RUSSO, Clovis Pires ', 'Logística: Armazenagem, controle e distribuição -Série Logística Organizacional', NULL, '658.78', '978.85.65704.96.0', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2013', '1 ed.', NULL, 'ex.1', 'p.244', 1, '25.00', 0, '003.', NULL, 1),
(1377, '2022-01-01', 'LOTS, Erika Gisele', 'Gestão de Talentos', NULL, '658.3', '978.85.8212.568.7', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.277', 1, '25.00', 0, '003.', NULL, 1),
(1378, '2022-01-01', 'SERTEK, Paulo ', 'Empreendedorismo', NULL, '658.421', '978.85.65704.70.0', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.237', 1, '25.00', 0, '003.', NULL, 1),
(1379, '2022-01-01', 'SOARES, Maria Alice ', 'Elaboração de projetos: da introdução à conclusão', NULL, '658.404', '978.85.8212.287.7', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.172', 1, '25.00', 0, '003.', NULL, 1),
(1380, '2022-01-01', 'ALECASTRO, Mario Sergio Cunha', 'Ética empesarial na prática: liderança, gestão e responsabilidade corporativa', NULL, '174.4', '978.85.7838.633.7', 'Curitiba-PR', 'Ibpex', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p.179', 1, '25.00', 0, '003.', NULL, 1),
(1381, '2022-01-01', 'SOLEME, Robson ', 'Série Gestão Comercial: Projeto de produto: planejamento, desenvolvimento e gestão', NULL, '658.575', '978.85.8212.786.5', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2013', '1 ed.', NULL, 'ex.1', 'p.242', 1, '25.00', 0, '003.', NULL, 1),
(1382, '2022-01-01', 'castanheira, Nelson Pereira ', 'Métodos quantitativos', NULL, '519.5', '978.85.7838.791.4', 'Curitiba-PR', 'Ibpex', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p.242', 1, '25.00', 0, '003.', NULL, 1),
(1383, '2022-01-01', 'SCHIER, rlos Ubiratan da Costa ', 'Gestão de custos', NULL, '658.15', '978.85.7838.802.7', 'Curitiba-PR', 'Ibpex', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p.249', 1, '25.00', 0, '003.', NULL, 1),
(1384, '2022-01-01', 'Rodrigo Berté', 'Série Desenvolvimento Sustentável: Gestão socioambiental no Brasil', NULL, '304.20981', '978.85.65704.36.6', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2013', '1 ed.', NULL, 'ex.1', 'p.270', 1, '25.00', 0, '003.', NULL, 1),
(1385, '2022-01-01', 'Cleverson Luiz Pereira', 'Mercado de Capitais', NULL, '332.6', '978.85.8212.817.6', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2013', '1 ed.', NULL, 'ex.1', 'p.190', 1, '25.00', 0, '003.', NULL, 1),
(1386, '2022-01-01', 'Egon Walter Wildauer', 'Plano de negócios: elementos constitutivos e processo de elaboração - Série Plano de Negócios:', NULL, '658.401', '978.85.7838.913.0', 'Curitiba-PR', 'Ibpex', NULL, NULL, '2011', '2ed.', NULL, 'ex.1', 'p.332', 1, '25.00', 0, '003.', NULL, 1),
(1387, '2022-01-01', 'LOURES, Rodrigo Costa da Rocha', 'Inovação em ambientes organizacionais: teorias, reflexões e práticas', NULL, '658.406', '85.87053.26.4', 'Curitiba-PR', 'Ibpex', NULL, NULL, '2006', '1 ed.', NULL, 'ex.1', 'p.200', 1, '25.00', 0, '003.', NULL, 1),
(1388, '2022-01-01', 'Wilson Bussada', 'Vademecum Jurisprudência: Alimentos -  Interpretados pelos Tribunais', NULL, '348', NULL, 'São Paulo', 'Jurídica Brasileira, Editora ', NULL, NULL, '1996', '1 ed.', 'v.1', 'ex.1', 'p.1522', 1, '25.00', 0, '003.', NULL, 1),
(1389, '2022-01-01', 'Ýara Muller', 'Dos processos criminais - métodos Processuais', NULL, '341.46', NULL, 'Rio de Janeiro', 'Record', NULL, NULL, '1957', NULL, 'v.1', 'ex.1', '700', 1, '25.00', 0, '003.', NULL, 1),
(1390, '2022-01-01', 'SABBAG, Paulo Yazigi ', 'Série Pós- Graduação em Administração de Empresas -Gerenciamento de projetos e empreendedorismo', '658.012.2', '658.404', '978.85.02.08347.9', 'São Paulo', 'Saraiva', NULL, NULL, '2009', NULL, 'v.1', 'ex.1', 'p.210', 1, '25.00', 0, '003.', NULL, 1),
(1391, '2022-01-01', 'ARAÚJO JÚNIOR, Ney Pereira de ', 'Apresentações empresariais além da oratória: técnicas para se comununicar claramente e obter sucesso empresarial - Série Pós- Graduação em Administração de Empresas ', '005.57', '658.452', '978.85.352.5758.8', 'Rio de Janeiro', 'Elsevier', NULL, NULL, '2012', '1 ed.', NULL, 'ex.2', 'p.208', 1, '25.00', 0, '003.', NULL, 1),
(1392, '2022-01-01', 'Florestan Fernandes', 'Comunidade e sociedade: leituras sobre problemas conceituais, metodológicos e de aplicações', NULL, '301', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1973', '1 ed.', 'v.1', 'ex.1', 'p.579', 1, '25.00', 0, '003.', NULL, 1),
(1393, '2022-01-01', 'Fábio Medina Osório', 'Improbidade administrativa', '342.9', '342', NULL, 'Porto Alegre', 'Síntese Ltda', NULL, NULL, '1998', '1 ed.', 'v.1', 'ex.1', 'p.424', 1, '25.00', 0, '003.', NULL, 1),
(1394, '2022-01-01', 'Maria Helena Diniz', 'Código civil anotado - Atualizado em 2002', '347(81)(094.4)', '347', '85.02.03776.5', 'São Paulo', 'Saraiva', NULL, NULL, '2002', '1 ed.', 'v.1', 'ex.1', 'p.1526', 1, '25.00', 0, '003.', NULL, 1),
(1395, '2022-01-01', 'Senado Federal', 'Modernidadae do Senado Federal - Presidência de José Sarney', NULL, '320.981', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.270', 1, '25.00', 0, '003.', NULL, 1),
(1396, '2022-01-01', 'NOVAES, Teresa Cristina de', 'Série perfil parlamentar: Bertha Lutz - nº73', '328(81)(042)', '923.2', '978.85.402.0559.8', 'Brasília-DF', 'Câmara dos Deputados', 'n.73', NULL, '2018', '1 ed.', NULL, 'ex.1', 'p.249', 1, '25.00', 0, '003.', NULL, 1),
(1397, '2022-01-01', 'Museu Paulista - Maria Medeiros de Carvalho Mendo (Org.)', 'Catálogo do Gabinete de Prudente de Moraes - Volume 1 - Biblioteca', NULL, '016.981.61', '85.89364.02.x', 'São Paulo', 'Pólo Editora', NULL, NULL, '2003', '1 ed.', 'v.1', 'ex.1', 'p.380', 1, '25.00', 0, '003.', NULL, 1),
(1398, '2022-01-01', 'Humberto Theodoro Júnior', 'Curso de Direito processual civil Teoria Geral do direito processual civil e processo de conhecimento - Volume 1', '347.9/ 347.9(81)', '347', '85.309.0480.X', 'Rio de Janeiro', 'Forense Editora', NULL, NULL, '1997', '1 ed.', 'v.1', 'ex.1', 'p.713', 1, '25.00', 0, '003.', NULL, 1),
(1399, '2022-01-01', 'Petrônio Bráz', 'Manual do Acessor jurídico do município', NULL, '342.81', '978.85.87484.89.5', 'Campinas-SP', 'Servanda Editora', NULL, NULL, '2009', '1 ed.', 'v.1', 'ex.1', 'p.1344', 1, '25.00', 0, '003.', NULL, 1),
(1400, '2022-01-01', 'Vilson Rodrigues Alves', 'Responsabilidade Civil dos estabelecimentos Bancários', NULL, '347', NULL, 'Campinas-SP', 'Bookseller Editora', NULL, NULL, '1996', '1 ed.', 'v.1', 'ex.1', 'p.1712', 1, '25.00', 0, '003.', NULL, 1),
(1401, '2022-01-01', 'BARBOSA, Marcos Antonio ', 'Iniciação  a pesquisa operacional no ambiente de gestão', NULL, '658.4', '978.85.8212.915.9', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2014', '1 ed.', NULL, 'ex.1', 'p.171', 1, '25.00', 0, '003.', NULL, 1),
(1402, '2022-01-01', 'Enrico Altavilla', 'Psicologia jurídica - Processo Psicológico e a verdade judicial - Volume 1', NULL, '340.1', NULL, 'Coimbra - Portugal', '***', NULL, NULL, '1981', '1 ed.', 'v.1', 'ex.1', 'p.485', 1, '25.00', 0, '003.', NULL, 1),
(1403, '2022-01-01', 'Enrico Altavilla', 'Psicologia jurídica - Processo Personagens do Processo penal - Volume 2', NULL, '340.1', NULL, 'Coimbra - Portugal', '***', NULL, NULL, '1981', '1 ed.', 'v.2', 'ex.1', 'p.743', 1, '25.00', 0, '003.', NULL, 1),
(1404, '2022-01-01', 'Modesto Carvalhosa', 'Comentários à lei de sociedades anônimas - Volume 1', '347.725(81)(094.56)', '342.225', '85.02.03503.7', 'São Paulo', 'Saraiva', NULL, NULL, '2002', '1 ed.', 'v.1', 'ex.1', 'p.856', 1, '25.00', 0, '003.', NULL, 1),
(1405, '2022-01-01', 'Tales Oscar Castelo Branco', 'Da prisão em flagrante: doutrina, legislação, jurisprudência, postulação em casos concretos', '343.125(81)/ 343.125.5(81)/ 343.125.5(81)(049.4)', '342.225', NULL, 'São Paulo', 'Saraiva', NULL, NULL, '1984', '1 ed.', 'v.1', 'ex.1', 'p.550', 1, '25.00', 0, '003.', NULL, 1),
(1406, '2022-01-01', 'Marcus Cláudio Acquaviva', 'Vademecum da Legislação pátria 2000', '34(81)/ 34(81)(094)', '340.07', '85.86271.74.8', 'São Paulo', 'Jurídica Brasileira, Editora ', NULL, NULL, '2000', '1 ed.', 'v.1', 'ex.1', 'p.2365', 1, '25.00', 0, '003.', NULL, 1),
(1407, '2022-01-01', 'MARTINS, Gilberto de Andrade', 'Guia para elaboração de monografias e trabalhos de conclusão de curso', NULL, '001.4', '85.224.2625.2', 'São Paulo', 'Atlas S.A.', NULL, NULL, '2000', '1 ed.', NULL, 'ex.1', 'p.108', 1, '20.00', 0, '003.', NULL, 1),
(1408, '2022-01-01', 'Brasil', 'Coleção Leis e estatutos Brasileiros: Novo Código civil: exposição de motivos e textos sancionados', '347(81)(094.4)', '348', '85.7060.316.9', 'São Paulo', 'Imprensa Oficial', NULL, NULL, '2004', '1 ed.', 'v.1', 'ex.1', 'p.355', 1, '0.00', 1, '003.', NULL, 1),
(1409, '2022-01-01', 'BNDES', 'Disposições Aplicáveis aos contratos do BNDES (Atualizada em 2008)', NULL, '348', NULL, '***', '***', NULL, NULL, '1998', '1 ed.', 'v.1', 'ex.1', 'p.38', 1, '0.00', 1, '003.', NULL, 1);
INSERT INTO `librarycollection` (`id`, `registrationDate`, `author`, `title`, `cdu`, `cdd`, `isbn`, `local`, `publisher_edition`, `number`, `month`, `year`, `edition`, `volume`, `copyNumber`, `pageNumber`, `typeAcquisitionId`, `price`, `prohibitedSale`, `provider`, `exclusionInfoTerm`, `registeredByUserId`) VALUES
(1410, '2022-01-01', 'BNDES', 'Legislação Básica do Sistema BNDES (1999)', NULL, '348', NULL, '***', '***', NULL, NULL, '1999', '1 ed.', 'v.1', 'ex.1', 'p.96', 1, '0.00', 1, '003.', NULL, 1),
(1411, '2022-01-01', 'Almir Antônio Khair', 'Gestão Fiscal Responsável: simpes municipal: Guia de Orientações para as prefeituras', NULL, '351.3', NULL, '***', 'Instituto Brasileiro de Administração Municipal - IBAM', NULL, NULL, '2001', '1 ed.', 'v.1', 'ex.3', 'p.207', 1, '20.00', 0, '003.', NULL, 1),
(1412, '2022-01-01', 'ABARBANEL, Albert ', 'Enciclopédia do Comportamento Sexual - Volume 1', NULL, '155.3', NULL, 'Rio de Janeiro', 'Civilização Brasileira', NULL, NULL, '1967', '1 ed.', NULL, 'ex.1', 'p.1 - 518', 1, '20.00', 0, '003.', NULL, 1),
(1413, '2022-01-01', 'Nacime Mansur e Reinaldo Ayer de Oliveira', 'O médico e a justiça', NULL, '346.03', NULL, 'São Paulo', 'Conselho Regional de Medicina de SP', NULL, NULL, '2006', '1 ed.', 'v.2', 'ex.1', 'p.519 - 1103', 1, '20.00', 0, '003.', NULL, 1),
(1414, '2022-01-01', 'Senado Federal', 'Coleção Sessões Temáricas: Reforma Política, Financiamento da Saúde, Pacto Federativo', NULL, '320', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2013', NULL, 'v.1', 'ex.1', 'p.230', 1, '20.00', 0, '003.', NULL, 1),
(1415, '2022-01-01', 'Senado Federal', 'Coleção Sessões Temáricas - Volume 2 : Reforma Política, Terceirização, Petrobras', NULL, '320', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2015', NULL, 'v.1', 'ex.1', 'p.347', 1, '20.00', 0, '003.', NULL, 1),
(1416, '2022-01-01', 'Octaciano da Costa Nogueira Filho', 'Edições Unilegis de Ciência Política - Vocabulário da Política - Volume 5', NULL, '300.3', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2010', NULL, 'v.1', 'ex.1', 'p.462', 1, '20.00', 0, '003.', NULL, 1),
(1417, '2022-01-01', 'VALLE, Maria Lúcia Elias ', 'Não erre mais: língua portuguesa para emprasas', NULL, '469', '978.85.8212.781.0', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2013', '1 ed.', 'v.1', 'ex.1', 'p.417', 1, '20.00', 0, '003.', NULL, 1),
(1418, '2022-01-01', 'BARRTE, Richard ', 'Libertando a alma da empresa: como transformar a organização numa entidade viva', NULL, '658.3', '85.316.0654.3', 'São Paulo', 'Cultrix Editora', NULL, NULL, '1998', '1 ed.', NULL, 'ex.2', 'p.192', 1, '20.00', 0, '003.', NULL, 1),
(1419, '2022-01-01', 'KEPNER, Charles Higgins ', 'O novo administrador racional', NULL, '658.4', NULL, 'São Paulo', 'McGraw-hill do Brasil Ltda', NULL, NULL, '1991', '1 ed.', NULL, 'ex.1', 'p.215', 1, '20.00', 0, '003.', NULL, 1),
(1420, '2022-01-01', 'CRUZ, Tadeu', 'Reengenharia na prática: metodologia do projeto com formulários', NULL, '658.406', '85.224.1191.3', 'São Paulo', 'Atlas S.A.', NULL, NULL, '1995', '1 ed.', NULL, 'ex.1', 'p.142', 1, '20.00', 0, '003.', NULL, 1),
(1421, '2022-01-01', 'MARIOTTI, Humberto ', 'Organizações de aprendizagem: educação continuada e a empresa do futuro', NULL, '658', '85.224.1368.1', 'São Paulo', 'Atlas S.A.', NULL, NULL, '1995', '1 ed.', NULL, 'ex.1', 'p.176', 1, '20.00', 0, '003.', NULL, 1),
(1422, '2022-01-01', 'Peppers e Rogers Group Marketing', 'CRM Series Marketing 1 to 1: um guia executivo para entender e implantar estratégias de customer relationship management', NULL, '658.8', '85.346.1336.2', 'São Paulo', 'Makron Books Ltda', NULL, NULL, '2001', '2 ed.', NULL, 'ex.1', 'p.102', 1, '20.00', 0, '003.', NULL, 1),
(1423, '2022-01-01', 'CALDAS, Miguel P. ', 'Demissão: causas, efeitos e alternativas para empresas e indivíduo', NULL, '658.313', '85.224.2482.9', 'São Paulo', 'Atlas S.A.', NULL, NULL, '2000', '1 ed.', NULL, 'ex.1', 'p.297', 1, '20.00', 0, '003.', NULL, 1),
(1424, '2022-01-01', 'SAVIANI, José Roberto ', 'O analista de negócios e da informação: o perfil moderno de um profissional que utiliza informática para alavancar os negócios empresariais', NULL, '658.403', '85.224.1504.8', 'São Paulo', 'Atlas S.A.', NULL, NULL, '1996', '1 ed.', NULL, 'ex.1', 'p.100', 1, '20.00', 0, '003.', NULL, 1),
(1425, '2022-01-01', 'Daniel Castro Gomes da Costa (Org.)', 'Tópicos avançados de direito processual eleitoral: de acordo com a Lei 13.165/15 e com o Novo código de processo civil', NULL, '342.07', '978.85.8238.354.4', 'Belo Horizonte', 'Arraes Editores', NULL, NULL, '2018', '1 ed.', 'v.1', 'ex.1', 'p.553', 1, '20.00', 0, '003.', NULL, 1),
(1426, '2022-01-01', 'Unesp - Pró-reitoria de Extensção Universitária', 'Leituras do Brasil', NULL, 'B869.93', '85.7139.367.2', 'São Paulo', 'Universidades', NULL, NULL, '2001', '1 ed.', 'v.1', 'ex.1', 'p.213', 1, '20.00', 0, '003.', NULL, 1),
(1427, '2022-01-01', 'Alesp', 'Quadro comparativo das Constituições do Estado de São Paulo', '342.4(816.1)', '342', NULL, 'São Paulo', 'Imprensa Oficial', NULL, NULL, '2002', NULL, 'v.1', 'ex.1', 'p.413', 1, '20.00', 0, '003.', NULL, 1),
(1428, '2022-01-01', 'Alesp', 'Quadro comparativo das Constituições do Estado de São Paulo', '342.4(816.1)', '342', NULL, 'São Paulo', 'Imprensa Oficial', NULL, NULL, '2002', NULL, 'v.1', 'ex.2', 'p.413', 1, '20.00', 0, '003.', NULL, 1),
(1429, '2022-01-01', 'Fernando G. Tenório, Luiz Gustavo Medeiros Barbosa (Org.)', 'O setor turístico versus a exploração sexual na infância e na adolescência', NULL, '345', '978.85.225.0704.7', 'Rio de Janeiro', 'FGV Editora', NULL, NULL, '2008', NULL, 'v.1', 'ex.1', 'p.424', 1, '20.00', 0, '003.', NULL, 1),
(1430, '2022-01-01', 'Lourenço Mário Prunes', 'Investigação de paternidade', NULL, '347', NULL, 'São Paulo', 'Sugestões Literárias ', NULL, NULL, '1976', NULL, 'v.1', 'ex.1', 'p.257', 1, '20.00', 0, '003.', NULL, 1),
(1431, '2022-01-01', 'Délio Magalhães', 'Causas de exclusão de crime', '343.227/ 343(81)/ 343.223', '345', NULL, 'São Paulo', 'Saraiva', NULL, NULL, '1975', NULL, 'v.1', 'ex.1', 'p.319', 1, '20.00', 0, '003.', NULL, 1),
(1432, '2022-01-01', 'José Salgado Martins', 'Direito penal: introdução e parte geral', '343', '345', NULL, 'São Paulo', 'Saraiva', NULL, NULL, '1974', NULL, 'v.1', 'ex.1', 'p.459', 1, '20.00', 0, '003.', NULL, 1),
(1433, '2022-01-01', 'Zaiden Geraige Neto', 'Comentário ao Código civil Brasileiro - Volume 12 - Da propriedade, da superfície e das servidões', '347(81)(094.46)', '347', '85.309.1984.4.x', 'Rio de Janeiro', 'Forense Editora', NULL, NULL, '2004', NULL, 'v.12', 'ex.1', 'p.369', 1, '20.00', 0, '003.', NULL, 1),
(1434, '2022-01-01', 'Maria Helena Diniz', 'Lei de locações de imóveis urbanos comentada', '347.453(81)(094.56)', '333', '85.02.02181.8', 'São Paulo', 'Saraiva', NULL, NULL, '1997', NULL, 'v.1', 'ex.1', 'p.523', 1, '0.00', 1, '003.', NULL, 1),
(1435, '2022-01-01', 'FERNANDES, Francisco ', 'Dicionário brasileiro globo', NULL, '008', '85.250.0298.4', 'São Paulo', 'Globo', NULL, NULL, '1995', NULL, 'v.1', 'ex.1', NULL, 1, '23.13', 0, '001.', NULL, 1),
(1436, '2022-01-01', 'Luiz Fernando Otero Cysne', 'A bíblia do som', NULL, '384', NULL, 'São Paulo', '***', NULL, NULL, '2006', NULL, 'v.1', 'ex.1', NULL, 1, '30.00', 0, '003.', NULL, 1),
(1437, '2022-01-01', 'Brasil', 'Código Civil Brasileiro e legislaçõe correlatas - 2012', NULL, '348', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2012', NULL, 'v.1', 'ex.1', 'p.543', 1, '15.00', 0, '003.', NULL, 1),
(1438, '2022-01-01', 'Stanley L.Brue', 'História do Pensamento econômico', NULL, '330.09', NULL, 'São Paulo', 'Thompson Learning', NULL, NULL, '2006', NULL, 'v.1', 'ex.1', 'p.553', 1, '15.00', 0, '003.', NULL, 1),
(1439, '2022-01-01', 'MOLLER, Claus ', 'O lado humano da qualidade: maximizando a qualidade de produtos e serviços através do desenvolvimento das pessoas', NULL, '658.312', NULL, 'São Paulo', 'Pioneira Editora', NULL, NULL, '1996', '10 ed.', NULL, 'ex.1', 'p.185', 1, '15.00', 0, '003.', NULL, 1),
(1440, '2022-01-01', 'Paul Blulstein', 'Desventuras das nações mais favorecidas', '327', '327', '978.85.7631.298.7', 'Brasília-DF', 'Fundação Alexandre de Gusmão ', NULL, NULL, '2010', NULL, 'v.1', 'ex.1', 'p.500', 1, '15.00', 0, '003.', NULL, 1),
(1441, '2022-01-01', 'Darlan Barroso', 'Coleção Prática Forense: Manual de redação jurídica e língua portuguesa - Volume 8', '340.113.2', '348.06', '978.85.203.3373.0', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '2013', NULL, 'v.8', 'ex.1', 'p.348', 1, '15.00', 0, '003.', NULL, 1),
(1442, '2022-01-01', 'Adelaide Carraro', 'O estudante', NULL, '304.2', '978.85.260.1102.1', 'São Paulo', 'Global Editora', NULL, NULL, '2006', NULL, 'v.1', 'ex.1', 'p.121', 1, '15.00', 0, '003.', NULL, 1),
(1443, '2022-01-01', 'HELN, Herman F. ', 'Peopleware: como trabalhar o fator humano nas implementações de sistemas integrados de informação (ERP)', NULL, '658.406', '85.7312.211.0', 'São Paulo', 'Gente Editora', NULL, NULL, '1999', '1 ed.', NULL, 'ex.1', 'p.168', 1, '15.00', 0, '003.', NULL, 1),
(1444, '2022-01-01', 'George Torquato Firmesa', 'Brasileiros no exterior', '314.74/ 314.743(81)', '327', '978.85.7631.088.4', 'Brasília-DF', 'Fundação Alexandre de Gusmão ', NULL, NULL, '2007', '1 ed.', 'v.1', 'ex.1', 'p.378', 1, '15.00', 0, '003.', NULL, 1),
(1445, '2022-01-01', 'CEREJA, Willian Roberto ', 'Português: linguagens - Volume Único', NULL, '496.07', '85.357.0377.2', 'São Paulo', 'Atual', NULL, NULL, '2003', '1 ed.', NULL, 'ex.1', 'p.512', 1, '15.00', 0, '003.', NULL, 1),
(1446, '2022-01-01', 'AMARAL, Emília ', 'Novas Palavras: português - Volume Único', NULL, '469.07', '85.322.5184.6', 'São Paulo', 'FTD Editora', NULL, NULL, '2003', '1 ed.', NULL, 'ex.1', 'p.624', 1, '15.00', 0, '003.', NULL, 1),
(1447, '2022-01-01', 'GARVIS, Tracy', 'Na ilha', '821.111(73)-3', '813', '978.85.8057.402.9', 'Rio de Janeiro', 'Intrínseca Editora', NULL, NULL, '2013', '1 ed.', NULL, 'ex.1', 'p.288', 1, '15.00', 0, '003.', NULL, 1),
(1448, '2022-01-01', 'Flávia Lima e Alves (Org.)', 'Patrimônio imaterial: disposições constitucionais: normas correlatas: bens imateriais resitrados', NULL, '341.349', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.84', 1, '15.00', 0, '003.', NULL, 1),
(1449, '2022-01-01', 'Senado Federal', 'Segurança Nacional: legislação e doutrina', NULL, '341.26', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2006', '1 ed.', 'v.1', 'ex.1', 'p.142', 1, '15.00', 0, '003.', NULL, 1),
(1450, '2022-01-01', 'Marta Cristina Wachowicz', 'Segurança, saúde e ergonomia', NULL, '363.11', '978.85.8212.634.9', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.2', 'p.246', 1, '15.00', 0, '003.', NULL, 1),
(1451, '2022-01-01', 'NIELSEN NETO, Henrique ', 'Filosofia básica', NULL, '100', NULL, 'São Paulo', 'Atual', NULL, NULL, '1985', '1 ed.', NULL, 'ex.1', 'p.311', 1, '15.00', 0, '003.', NULL, 1),
(1452, '2022-01-01', 'Claire Selltiz, Marie Jahora, Morton ', 'Metodos de Pesquisas nas relações sociais', NULL, '378.103', NULL, 'São Paulo', 'Universidades', NULL, NULL, '1971', '1 ed.', 'v.1', 'ex.1', 'p.687', 1, '15.00', 0, '003.', NULL, 1),
(1453, '2022-01-01', 'Senado Federal', 'Microempreendedor Individual (MEI): Primeiro degrau da atividade empresarial legalizada', NULL, '346.07', '978.85.7018.445.0', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2013', '1 ed.', 'v.1', 'ex.1', 'p.170', 1, '15.00', 0, '003.', NULL, 1),
(1454, '2022-01-01', 'Senado Federal', 'Resolução nº 43, de 2001, Atualizado em 2010.', NULL, '348', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.190', 1, '15.00', 0, '003.', NULL, 1),
(1455, '2022-01-01', 'Senado Federal', 'Tráfico de pessoas', NULL, '341.272', '978.85.7018.513.6', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2013', '1 ed.', 'v.1', 'ex.1', 'p.174', 1, '15.00', 0, '003.', NULL, 1),
(1456, '2022-01-01', 'Juvêncio Vasconcelos Viana', 'Execução fiscal contra a fazenda pública', '347.9:336.126.34', '347', '85.86208.56.6', 'São Paulo', 'Dialética Editora', NULL, NULL, '1998', '1 ed.', 'v.1', 'ex.1', 'p.175', 1, '15.00', 0, '003.', NULL, 1),
(1457, '2022-01-01', 'Marco Maciel', 'Reformas e governabilidade', NULL, '320.981', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2004', '1 ed.', 'v.1', 'ex.1', 'p.132', 1, '15.00', 0, '003.', NULL, 1),
(1458, '2022-01-01', 'Vários autores (Bruno Souza da Silva, Roberto Eduardo Lamari, outros)', 'Série Cidadania e Política: Três poderes e sociedade no Brasil - Volume 4', NULL, '320', '978.85.89739.07.8', 'São Paulo', 'Fundação Konrad Adenauer', NULL, NULL, NULL, '1 ed.', 'v.4', 'ex.1', 'p.134', 1, '15.00', 0, '003.', NULL, 1),
(1459, '2022-01-01', 'SEMLER, Ricarado ', 'Virando a própria mesa: uma história de sucesso emepresarial Made in Brazil', '65', '658', '85.325.1348.4', 'Rio de Janeiro', 'Rocco Ltda', NULL, NULL, '2002', '1 ed.', NULL, 'ex.1', 'p.235', 1, '15.00', 0, '003.', NULL, 1),
(1460, '2022-01-01', 'AURELI, Willy ', 'Biu Marrandu - os donos da chuva', NULL, '981', NULL, 'São Paulo', 'Leia Edições', NULL, NULL, '1963', '1 ed.', NULL, 'ex.1', '237', 1, '15.00', 0, '003.', NULL, 1),
(1461, '2022-01-01', 'NEWMAN, Mildred ', 'Seja você mesmo seu melhor amigo: um diálogo com dois psicanalistas ', '159.962', '158.1', NULL, 'Rio de Janeiro', 'J. Olympio Editora', NULL, NULL, '1974', '1 ed.', NULL, 'ex.1', 'p.56', 1, '15.00', 0, '003.', NULL, 1),
(1462, '2022-01-01', 'Vários autores', 'Série Poemas da Cidade - Antologia Paulistana - Volulme 3', NULL, 'B869.91', NULL, 'São Paulo', 'Ilapalma Editora', NULL, NULL, '1970', '1 ed.', 'v.3', 'ex.1', 'p.125', 1, '15.00', 0, '003.', NULL, 1),
(1463, '2022-01-01', 'GAMA, Basílio da ', 'Coleção Nossos Clássicos: O Uruguai - Volume 77', NULL, 'B869.91', NULL, 'Rio de Janeiro', 'Agir Editora', NULL, NULL, '1972', '1 ed.', 'v.77', 'ex.1', 'p.124', 1, '15.00', 0, '003.', NULL, 1),
(1464, '2022-01-01', 'HARADA, Susumo ', ' ', NULL, '707', NULL, 'Osasco-SP', '***', NULL, NULL, '2002', '1 ed.', 'v.1', 'ex.1', 'p.43', 1, '15.00', 0, '003.', NULL, 1),
(1465, '2022-01-01', 'Thomas H. Cook', 'Folhas caídas', '821.111(73).3', '813', '978.85.286.1496.1', 'Rio de Janeiro', 'Bertrand Brasil', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p.294', 1, '15.00', 0, '003.', NULL, 1),
(1466, '2022-01-01', 'João Camilo de Oliveira Torres', 'Coleção João Camilo de Oliveira Torres -  O presidencialismo no Brasil - Nº 6', '304:338(81)(091)', '320', '978.85.402.0690.8', 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '2018', '1 ed.', 'v.6', 'ex.1', 'p.248', 1, '15.00', 0, '003.', NULL, 1),
(1467, '2022-01-01', 'João Camilo de Oliveira Torres', 'Coleção João Camilo de Oliveira Torres -  O presidencialismo no Brasil - Nº 7', '342.38(81)', '320', '978.85.402.0703.5', 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '2018', '1 ed.', 'v.7', 'ex.1', 'p.298', 1, '15.00', 0, '003.', NULL, 1),
(1468, '2022-01-01', 'SCHARMER, Claus Otto ', 'Teoria U: como liderar pela percepção e realização do futuro emergente', '658.011.4', '658.404', '978.85.352.3881.5', 'Rio de Janeiro', 'Elsevier', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p.403', 1, '15.00', 0, '003.', NULL, 1),
(1469, '2022-01-01', 'MAGRETTA, Joan ', 'O que é gerenciar e administrar ', '65', '658', '85.352.0932.8', 'Rio de Janeiro', 'Campus Editora', NULL, NULL, '2002', '1 ed.', NULL, 'ex.1', 'p.227', 1, '15.00', 0, '003.', NULL, 1),
(1470, '2022-01-01', 'Tribunal de Justiça de São Paulo', 'Repasses públicos ao Terceiro setor', NULL, '348', NULL, 'São Paulo', 'Imprensa Oficial', NULL, NULL, '2004', '1 ed.', 'v.1', 'ex.1', 'p.88', 1, '15.00', 0, '003.', NULL, 1),
(1471, '2022-01-01', 'TJORNAGHI, Newton ', 'Princípios de Administração - \"Staff\" Alta Administração - Volume 3', NULL, '658', NULL, 'Rio de Janeiro', 'Guanabara koogan', NULL, NULL, '1968', '1 ed.', 'v.3', 'ex.1', 'p.54', 1, '15.00', 0, '003.', NULL, 1),
(1472, '2022-01-01', 'TJORNAGHI, Newton ', 'Princípios de Administração - \"Staff\" Alta Administração - Volume 4', NULL, '658', NULL, 'Rio de Janeiro', 'Guanabara koogan', NULL, NULL, '1968', '1 ed.', 'v.4', 'ex.1', 'p.30', 1, '15.00', 0, '003.', NULL, 1),
(1473, '2022-01-01', 'QUEIRÓS, Eça de ', 'Coleção Grandes Obras - Os maias Vol.II - Nº 9', NULL, 'B869.93', '85.755.838.8', 'Rio de Janeiro', 'Escala Editora', NULL, NULL, NULL, '1 ed.', 'v.9', 'ex.1', 'p.283', 1, '15.00', 0, '003.', NULL, 1),
(1474, '2022-01-01', 'ALENCAR, José de ', 'Coleção Grandes mestres da literatura brasileira: Guerra dos Mascates (Parte 1) - Volume 16', NULL, 'B869.93', '85.7556.677.6', 'Rio de Janeiro', 'Escala Editora', NULL, NULL, NULL, '2 ed.', 'v.16', 'ex.1', 'p.210', 1, '15.00', 0, '003.', NULL, 1),
(1475, '2022-01-01', 'Yussef Said Cahali', 'Divórcio e separação - Tomo I', '347.627.2(81)', '346.015', '85.203.1314.0', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '1986', NULL, 'v.1', 'ex.1', 'p.707 -1547', 1, '15.00', 0, '003.', NULL, 1),
(1476, '2022-01-01', 'Yussef Said Cahali', 'Divórcio e separação ', '347.627.2(81)', '346.015', '85.203.0520.2', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '1986', NULL, 'v.1', 'ex.1', 'p.707', 1, '15.00', 0, '003.', NULL, 1),
(1477, '2022-01-01', 'Yussef Said Cahali', 'Divórcio e separação - Tomo II', '347.627.2(81)', '346.015', '85.203.0520.2', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '1995', NULL, 'v.1', 'ex.1', 'p.707 -1547', 1, '15.00', 0, '003.', NULL, 1),
(1478, '2022-01-01', 'Caio Mário da Silva Pereira', 'Lesão nos contratos', '347.44', '346.02', '85.309.0388.9', 'Rio de Janeiro', 'Forense Editora', NULL, NULL, '1994', NULL, 'v.1', 'ex.1', 'p.227', 1, '15.00', 0, '003.', NULL, 1),
(1479, '2022-01-01', 'Marcia Barr (Org)', 'Cuidadores da primeira infânca: por uma formação de qualidade', NULL, '370.15', '978.85.7018.882.3', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2017', NULL, 'v.1', 'ex.1', 'p.193', 1, '15.00', 0, '003.', NULL, 1),
(1480, '2022-01-01', 'Brasil', ' Série Legislação - nº 270 - Lei de responsabilidade fiscal  - Atualizada em 2018', '336.1/ 5(81)(094)', '348', '978.85.402.0696.0', 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '2018', '1 ed.', 'v.1', 'ex.1', 'p.124', 1, '15.00', 0, '003.', NULL, 1),
(1481, '2022-01-01', 'A. Siqueira Montalvão', 'Erro médico - Teoria, Legislação e jurisprudência-  Reparação do dano material estético e moral - Volume 1', NULL, '346.03', NULL, 'Campinas-SP', 'Julex livros', NULL, NULL, '1998', '1 ed.', 'v.1', 'ex.1', 'p.1 - 396', 1, '15.00', 0, '003.', NULL, 1),
(1482, '2022-01-01', 'A. Siqueira Montalvão', 'Erro médico - Teoria, Legislação e jurisprudência-  Reparação do dano material estético e moral - Volume 2', NULL, '346.03', NULL, 'Campinas-SP', 'Julex livros', NULL, NULL, '1998', '1 ed.', 'v.2', 'ex.1', 'p.403 - 797', 1, '15.00', 0, '003.', NULL, 1),
(1483, '2022-01-01', 'MARTIN, Roger L. ', 'O vírus da responsabilidade ', NULL, '658.4092', '85.89876.46.2', 'São Paulo', 'A Girafa Editora', NULL, NULL, '2004', '1 ed.', NULL, 'ex.1', 'p.317', 1, '15.00', 0, '003.', NULL, 1),
(1484, '2022-01-01', 'GOLDKORN, Roberto B.O.', 'Feng Shui: prosperidade e harmonia no trabalho', '133', '133.333', '85.352.0515.2', 'Rio de Janeiro', 'Campus Editora', NULL, NULL, '1999', '1 ed.', NULL, 'ex.1', 'p.174', 1, '15.00', 0, '003.', NULL, 1),
(1485, '2022-01-01', 'CUNHA, Sólon de Almeida ', 'Da perticipação dos trabalhadores nos lucros ou resultados da empresa', '34.658.234.2(81)(094.56)', '658.4', '85.02.01964.3', 'São Paulo', 'Saraiva', NULL, NULL, '1997', '1 ed.', NULL, 'ex.1', 'p.149', 1, '15.00', 0, '003.', NULL, 1),
(1486, '2022-01-01', 'MILLET, Evandro Barreira ', 'Qualidade em serviços: princípios para a gestão contemporânea das organizações', '658.56', '658.562', '85.00.00321.9', 'Rio de Janeiro', 'Ediouro Editora', NULL, NULL, '1997', '1 ed.', NULL, 'ex.1', 'p.206', 1, '15.00', 0, '003.', NULL, 1),
(1487, '2022-01-01', 'BERNHOEFT, Renato ', 'Trabalhar e desfrutar: equilibrio entre vida pessoal e profissional', NULL, '158.1', '85.213.0670.9', 'São Paulo', 'Nobel Editora', NULL, NULL, '1991', '1 ed.', NULL, 'ex.1', 'p.126', 1, '15.00', 0, '003.', NULL, 1),
(1488, '2022-01-01', 'Voltaire Missel Michel', 'Responsabilidade do prefeito municipal', '352.075.31:347.51', '351', '85.7348.056.4', 'Porto Alegre', 'Livraria do Advogado', NULL, NULL, '1998', '1 ed.', 'v.1', 'ex.1', 'p.110', 1, '15.00', 0, '003.', NULL, 1),
(1489, '2022-01-01', 'José Cretella Júnior', 'Direito administrativo para concursos públicos', '35(81)(079.1)', '342', '85.203.1722.7', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '1999', '1 ed.', 'v.1', 'ex.1', 'p.192', 1, '15.00', 0, '003.', NULL, 1),
(1490, '2022-01-01', 'John Brooks', 'Aventuras empresariais', '338(73)', '338.0973', '978.85.68905.01.2', 'Rio de Janeiro', 'Best Business Editora', NULL, NULL, '2016', '1 ed.', 'v.1', 'ex.1', 'p.574', 1, '15.00', 0, '003.', NULL, 1),
(1491, '2022-01-01', 'VIEIRA, Antônio', 'Sermões: Padre Antônio Vieira', NULL, 'B869.5', '85.87328.32.8', 'São Paulo', 'Hedra Editora', NULL, NULL, '2000', '3 ed.', NULL, 'ex.1', 'p.661', 1, '15.00', 0, '003.', NULL, 1),
(1492, '2022-01-01', 'Brasil - Senado Federal', 'Código de precesso civil e legislações correlatas', NULL, '348', '978.85.7018.468.9', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2013', '1 ed.', 'v.1', 'ex.1', 'p.256', 1, '15.00', 0, '003.', NULL, 1),
(1493, '2022-01-01', 'Pinheiro de Souza', 'Lições de teoria geral do estado', NULL, '320', NULL, 'São Paulo', '***', NULL, NULL, '1992', '1 ed.', 'v.1', 'ex.1', 'p.193', 1, '15.00', 0, '003.', NULL, 1),
(1494, '2022-01-01', 'BAZILLI, Roberto Ribeiro ', 'Contratos Administrativos', NULL, '657', NULL, 'São Paulo', 'Malheiros', NULL, NULL, '1996', '1 ed.', NULL, 'ex.1', 'p.162', 1, '15.00', 0, '003.', NULL, 1),
(1495, '2022-01-01', 'Senado Federal', 'Gestão orçamentária pública - Coletânia de Legislações', NULL, '341.383', '978.85.7018.859.5', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2017', '1 ed.', 'v.1', 'ex.1', 'p.148', 1, '15.00', 0, '003.', NULL, 1),
(1496, '2022-01-01', 'Antonio Luiz de Toledo Pinto (ORG.)', 'Constituição da República Federativa do Brasil: promulgada em 5 de outrubro de 1988', '342.4(81)\"1998\'', '348', '85.02.02294.6', 'São Paulo', 'Saraiva', NULL, NULL, '1999', '1 ed.', 'v.1', 'ex.1', 'p.267', 1, '15.00', 0, '003.', NULL, 1),
(1497, '2022-01-01', 'FRIEDMAN, Irving Sigmund ', 'Inflação: um desastre mundial', NULL, '657', NULL, 'São Paulo', 'Melhoramentos Editora', NULL, NULL, '1979', '1 ed.', NULL, 'ex.1', 'p.227', 1, '15.00', 0, '003.', NULL, 1),
(1498, '2022-01-01', 'RODRIGUES, Nelson ', 'A mentira', '821.134.3(81).3', 'B869.93', '978.85.220.1123.0', 'Rio de Janeiro', 'Agir Editora', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p.169', 1, '15.00', 0, '003.', NULL, 1),
(1499, '2022-01-01', 'SHINYASHKI, Roberto T. ', 'O sucesso é ser feliz', NULL, '158.1', '85.7312.134.3', 'São Paulo', 'Gente Editora', NULL, NULL, '1997', '1 ed.', NULL, 'ex.1', 'p.198', 1, '15.00', 0, '003.', NULL, 1),
(1500, '2022-01-01', 'Conselho Regional de Contabilidade do Estado de São Paulo', 'Os princípios Fundamentais de Contabilidadae, as normas brasileiras de contabilidade e o código de ética profissional do contabilista', '657.1(083.74)(081)', '657', NULL, 'São Paulo', '***', NULL, NULL, '1999', '1 ed.', NULL, 'ex.1', 'p.212', 1, '15.00', 0, '003.', NULL, 1),
(1501, '2022-01-01', 'ORRICO JUNIOR, Hugo ', 'Pirataria de Softwere', '343.52:004.42', '005', NULL, 'São Paulo', 'MM Livros Editora e Distribuidora', NULL, NULL, '2004', '1 ed.', NULL, 'ex.1', 'p.230', 1, '15.00', 0, '003.', NULL, 1),
(1502, '2022-01-01', 'PENHA, Cícero Domingues', 'Empresa-Rede - Uma nova forma de gestão', NULL, '658.4', NULL, 'Uberlândia-MG', 'Algar Grupo', NULL, NULL, '1996', '1 ed.', NULL, 'ex.1', 'p.186', 1, '15.00', 0, '003.', NULL, 1),
(1503, '2022-01-01', 'DINSMORE, Paul Campbell', 'Poder  e influência gerencial - além da autoridade formal', NULL, '658.4', NULL, 'Rio de Janeiro', 'COP Editora', NULL, NULL, '1989', '1 ed.', NULL, 'ex.1', 'p.144', 1, '15.00', 0, '003.', NULL, 1),
(1504, '2022-01-01', 'COELHO, Teixeira', 'Coleção Primeiros Passos - O que é indústria cultural', NULL, '080', '85.11.01008.4', 'São Paulo', 'Brasilienses', NULL, NULL, '1989', '1 ed.', NULL, 'ex.1', 'p.109', 1, '15.00', 0, '003.', NULL, 1),
(1505, '2022-01-01', 'Gelindo Pedro Sommavilla', 'Estatuto dos funcionários civis da União', NULL, '348', NULL, 'São Paulo', 'EUD Editora', NULL, NULL, '1976', '1 ed.', 'v.1', 'ex.1', 'p.144', 1, '15.00', 0, '003.', NULL, 1),
(1506, '2022-01-01', 'David Aspy', 'Novas técnicas para humanizar a educação', NULL, '370.1', NULL, 'São Paulo', 'Cultrix Editora', NULL, NULL, NULL, '1 ed.', 'v.1', 'ex.1', 'p.157', 1, '15.00', 0, '003.', NULL, 1),
(1507, '2022-01-01', 'TUFANO, Douglas ', 'Estudos de língua e literatura', NULL, ' 469.07', NULL, 'São Paulo', 'Moderna Editora', NULL, NULL, '1982', '1 ed.', NULL, 'ex.1', 'p.242', 1, '15.00', 0, '003.', NULL, 1),
(1508, '2022-01-01', 'MAGNOLI, Orlando ', 'Coleção história - Nº 21 - Famiglia: de um homem que já não se usa mais, o imigrante', NULL, 'B869.935', NULL, 'São Paulo', 'Secretarias ', NULL, NULL, '1981', '1 ed.', 'v.21', 'ex.1', 'p.192', 1, '15.00', 0, '003.', NULL, 1),
(1509, '2022-01-01', 'BARBEIRO, Heródoto ', 'Você na telinha', NULL, '070.4', '85.7413.116.4', 'São Paulo', 'Futura Editora', NULL, NULL, '2002', '1 ed.', NULL, 'ex.1', 'p.143', 1, '15.00', 0, '003.', NULL, 1),
(1510, '2022-01-01', 'FLYNN, Gillian', 'Garota exemplar', '821.111(73).3', '813', '978.85.8057.290.2', 'Rio de Janeiro', 'Intrínseca Editora', NULL, NULL, '2013', '1 ed.', NULL, 'ex.1', 'p.448', 1, '15.00', 0, '003.', NULL, 1),
(1511, '2022-01-01', 'Víctor Bazán e Claudio Nash', 'Justicia Constitucional y derechos fundamentales - Aportes de Argentina, Bolicia, Brasil, Chile, Perú, Uruguay y Venezuela - Nº 1', NULL, '341', '978.9974.8232.2.8', 'São Paulo', 'Fundação Konrad Adenauer', NULL, NULL, '2010', NULL, 'v.1', 'ex.1', 'p.108', 1, '0.00', 1, '004.', NULL, 1),
(1512, '2022-01-01', 'Wladimir Valler', 'A Reparação do Dano Moral no direito brasileiro', NULL, '347', NULL, 'São Paulo', 'E.V. Editora', NULL, NULL, '1995', NULL, 'v.1', 'ex.1', 'p.323', 1, '15.00', 0, '003.', NULL, 1),
(1513, '2022-01-01', 'Wladimir Valler', 'A Reparação do Dano Moral no direito brasileiro', NULL, '347', NULL, 'São Paulo', 'E.V. Editora', NULL, NULL, '1996', NULL, 'v.1', 'ex.2', 'p.323', 1, '15.00', 0, '003.', NULL, 1),
(1514, '2022-01-01', 'Fundação Prefeito Faria Lima - CEPAM - Centro de Estudos e Pesquisas de Administração Muncipal', 'O processo e a técnica legislativa municipal', '342.52', '320', NULL, 'São Paulo', 'Universidades', NULL, NULL, NULL, NULL, 'v.1', 'ex.1', 'p.110', 1, '15.00', 0, '003.', NULL, 1),
(1515, '2022-01-01', 'Rui Stoco', 'Abuso do direito e má-fé processual', '347.141:347.9', '347', '85.203.2175.5', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '2002', NULL, 'v.1', 'ex.1', 'p.275', 1, '15.00', 0, '003.', NULL, 1),
(1516, '2022-01-01', 'Nelson Nery Junior e Teresa Arruda Alvin Wambier', 'Série Aspectos polémicos e atuais dos recursos cíveis - Volulme 9 : Aspectos polêmicos e atuais dos recursos cíveis e assuntos afins', '347.955(81)(094)', '347', '85.203.2852.0', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '2006', NULL, 'v.9', 'ex.1', 'p.670', 1, '15.00', 0, '003.', NULL, 1),
(1517, '2022-01-01', 'Nelson Nery Junior e Teresa Arruda Alvin Wambier', 'Série Aspectos polémicos e atuais dos recursos cíveis - Volulme 10 : Aspectos polêmicos e atuais dos recursos cíveis e assuntos afins', '347.955(81)(094)', '347', '85.203.2862.8', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '2006', NULL, 'v.10', 'ex.1', 'p.606', 1, '15.00', 0, '003.', NULL, 1),
(1518, '2022-01-01', 'Nelson Nery Junior e Teresa Arruda Alvin Wambier', 'Série Aspectos polémicos e atuais dos recursos cíveis - Volulme 11 : Aspectos polêmicos e atuais dos recursos cíveis e assuntos afins', '347.955(81)(094)', '347', '978.85.203.3098.2', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '2006', '1 ed.', 'v.11', 'ex.1', 'p.448', 1, '15.00', 0, '003.', NULL, 1),
(1519, '2022-01-01', 'REIS, Otelo ', 'Coleção gramática viva: Breviário da Conjugação de verbos', NULL, '415', NULL, 'Rio de Janeiro', 'Ministérios', NULL, NULL, '1978', '1 ed.', NULL, 'ex.1', 'p.159', 1, '15.00', 0, '003.', NULL, 1),
(1520, '2022-01-01', 'Cesar Montenegro', 'Dicionário de prática processual civil - Volume I (A - I)', '347.9(81)(03)', '341.46', '85.02.01667.9', 'São Paulo', 'Saraiva', NULL, NULL, '1996', '1 ed.', 'v.1', 'ex.1', 'p.684', 1, '15.00', 0, '003.', NULL, 1),
(1521, '2022-01-01', 'Cesar Montenegro', 'Dicionário de prática processual civil - Volume II (J -V)', '347.9(81)(03)', '341.46', '85.02.01668.7', 'São Paulo', 'Saraiva', NULL, NULL, '1996', '1 ed.', 'v.2', NULL, 'p.1392', 1, '15.00', 0, '003.', NULL, 1),
(1522, '2022-01-01', 'José Cretella Júnior', 'Divionário do precesso civil', '341.913.95(81)(03)', '341.46', '85.7436.041.4', 'Rio de Janeiro', 'Forense Editora', NULL, NULL, '1999', '1 ed.', 'v.1', 'ex.1', 'p.471', 1, '15.00', 0, '003.', NULL, 1),
(1523, '2022-01-01', 'ALMEIDA, Amador Paes de', 'Teoria e prática dos títulos de crédito', '347.457', '657', '978.85.02.07812.3', 'São Paulo', 'Saraiva', NULL, NULL, '2009', '1 ed.', NULL, 'ex.1', '606', 1, '15.00', 0, '003.', NULL, 1),
(1524, '2022-01-01', 'Arnaldo Marmitt', 'Perdas e Danos', NULL, '347', '85.321.0069.4', 'Rio de Janeiro', 'AIDE Editora', NULL, NULL, '1997', '1 ed.', 'v.1', 'ex.1', 'p.478', 1, '15.00', 0, '003.', NULL, 1),
(1525, '2022-01-01', 'Octávio Henrique Dias Garcia Côrtes', 'A política externa do governo Sarney: o início da reformulação de diretrizes para a inserção internacional do Brasil sob o signo da democracia', '327(81)', '327', '978.85.7631.273.4', 'Brasília-DF', 'FUNAG / CHDD', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.372', 1, '15.00', 0, '003.', NULL, 1),
(1526, '2022-01-01', 'Eduardo S. Pimenta', 'Código de direitos autorais e acordos internacionais', '341.78:341.382', '342.28', '85.85486.17.1', 'São Paulo', 'Lejus Editora', NULL, NULL, '1998', '1 ed.', 'v.1', 'ex.1', 'p.650', 1, '15.00', 0, '003.', NULL, 1),
(1527, '2022-01-01', 'Maria Clara R.M. Prado', 'A real história do real', '338.24(81)', '332.4150981', '85.01.06930.2', 'Rio de Janeiro', 'Record', NULL, NULL, '2005', '1 ed.', 'v.1', 'ex.1', 'p.573', 1, '15.00', 0, '003.', NULL, 1),
(1528, '2022-01-01', 'FRANCO, Simon ', 'Criando o próprio futuro: O mercado de trabalho na era da competitividade total', NULL, '658', '85.08.06430.6', 'São Paulo', 'Ática', NULL, NULL, '1999', '1 ed.', NULL, 'ex.1', 'p.181', 1, '15.00', 0, '003.', NULL, 1),
(1529, '2022-01-01', 'MUSSARK, Eugenio ', 'Metacompetência: uma nova visão do trabalho e da realização pessoal', NULL, '658.3125', '85.7312.401.6', 'São Paulo', 'Gente Editora', NULL, NULL, '2003', '1 ed.', NULL, 'ex.1', 'p.204', 1, '15.00', 0, '003.', NULL, 1),
(1530, '2022-01-01', 'Raymond Saner', 'O negociador experiente: estratégia, táticas, motivação, comportamento, liderança', NULL, '302.3', '85.7359.278.8', 'São Paulo', 'SENAC Editora', NULL, NULL, '2002', '1 ed.', 'v.1', 'ex.1', 'p.296', 1, '15.00', 0, '003.', NULL, 1),
(1531, '2022-01-01', 'BNDES, um banco de idéias: 50 anos refletindo o Brasil', 'Organizadores Dulce Corrêa Monteiro Filha (Org.)', NULL, '332.209981', '85.87545.03.5', 'Rio de Janeiro', 'BNDES', NULL, NULL, '2002', '1 ed.', 'v.1', 'ex.1', 'p.432', 1, '15.00', 0, '003.', NULL, 1),
(1532, '2022-01-01', 'Crodowaldo Pavan (Org.)', 'Uma estratégia latino-americana para a Amazônia - Volume 3', NULL, '337.18', '85.85373.15.6', 'São Paulo', 'Memorial Editora', NULL, NULL, '1996', '1 ed.', 'v.1', 'ex.1', 'p.332', 1, '15.00', 0, '003.', NULL, 1),
(1533, '2022-01-01', 'RAMOS, Admir', 'Moderno Curso de Oratória', NULL, '400', NULL, 'São Paulo', 'CIA Brasil Editora', NULL, NULL, NULL, '1 ed.', NULL, 'ex.1', '1187', 1, '15.00', 0, '003.', NULL, 1),
(1534, '2022-01-01', 'Caio Mário da Silva Pereira', 'Responsabilidade civil de acordo coma Constituiçaõ de 1988', '347.51/ 347.5', '347', '85.309.0136.3', 'Rio de Janeiro', 'Forense Editora', NULL, NULL, '1998', '1 ed.', 'v.1', 'ex.1', 'p.350', 1, '15.00', 0, '003.', NULL, 1),
(1535, '2022-01-01', 'Kiyoshi Harada', 'Direito Municipal: IPTU, ISS, ITBI, Taxas de serviço, taxas de política, contribuições de melhoria, legislação complementar', '352(816.11)', '341', '85.224.2959.6', 'São Paulo', 'Atlas S.A.', NULL, NULL, '2001', '1 ed.', 'v.1', 'ex.1', 'p.282', 1, '15.00', 0, '003.', NULL, 1),
(1536, '2022-01-01', 'Otto Klineberg', 'As diferenças raciais', NULL, '305.8', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1966', '1 ed.', 'v.1', 'ex.1', 'p.315', 1, '15.00', 0, '003.', NULL, 1),
(1537, '2022-01-01', 'Luci Helena Silva Martins', 'Reflexões sobre um acontecimento social na área fabril: a experiência autogestionária da Makerli', NULL, '362.85', '978.85.7494.150.9', 'São Paulo', 'Iglu Editora', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.319', 1, '15.00', 0, '003.', NULL, 1),
(1538, '2022-01-01', 'Luiz Eduardo Fonseca de Carvalho Gonçalves', 'As relações Brasil- CEPAL', '327.3(81)', '327', '978.85.7631.325.0', 'Brasília-DF', 'Fundação Alexandre de Gusmão ', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.120', 1, '15.00', 0, '003.', NULL, 1),
(1539, '2022-01-01', 'Valdemar P. da Luz', 'Manual prático das petições judiciais', '347(083.2) / 343.2 (083.2) / 34:331(083.2)', '347', '85.241.0331.0', 'Porto Alegre', 'Sagra Editora', NULL, NULL, '1998', '1 ed.', 'v.1', 'ex.1', 'p.230', 1, '15.00', 0, '003.', NULL, 1),
(1540, '2022-01-01', 'Diogenes Gasparini', 'O estatuto da cidade', '34:711.4(81)(094)', '348', '^85.86314-', 'São Paulo', 'NDJ Editora', NULL, NULL, '2002', '1 ed.', 'v.1', 'ex.1', 'p.247', 1, '15.00', 0, '003.', NULL, 1),
(1541, '2022-01-01', 'Gustavo Saad Diniz', 'Sociedades dependentes de autoridadae', '347.7', '346.07', '85.7647.037.3', 'São Paulo', 'IOB Thomson', NULL, NULL, '2004', '1 ed.', 'v.1', 'ex.1', 'p.191', 1, '15.00', 0, '003.', NULL, 1),
(1542, '2022-01-01', 'Guilherme Bonfim Dei Vegni-Neri', 'Avaliação de glebas, loteamentos, distritos industriais', '347.235(81)(094)', '333', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1979', '1 ed.', 'v.1', 'ex.1', 'p.75', 1, '15.00', 0, '003.', NULL, 1),
(1543, '2022-01-01', 'MULTIPLIC, Banco - Prêmio Pense Grande', 'Coletânia de Biografias e histórias de dez grandes empresários e respectiva emprasas - Prêmio Pense Grande ', NULL, '926.609', NULL, 'São Paulo', 'Multiplic', NULL, NULL, '1989', '1 ed.', NULL, 'ex.1', 'p.176', 1, '15.00', 0, '003.', NULL, 1),
(1544, '2022-01-01', 'KAPLAN, Robert S. ', 'A estratégia em ação: balanced scorecard', '65.012.2', '658.404', '85.352.0149.1', 'Rio de Janeiro', 'Campus Editora', NULL, NULL, '1997', '1 ed.', NULL, 'ex.1', 'p.344', 1, '15.00', 0, '003.', NULL, 1),
(1545, '2022-01-01', 'GOUILLART, Francis J. ', 'Transformando a organização', NULL, '658.404', '85.346.0458.4', 'São Paulo', 'Makron Books Ltda', NULL, NULL, '1995', '1 ed.', NULL, 'ex.1', 'p.412', 1, '15.00', 0, '003.', NULL, 1),
(1546, '2022-01-01', 'Arnaldo Rizzardo', 'Comentários ao Código de trânsito Brasileiro', '351.81(81)(094.56)', '348', '85.203.1921.1', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '2000', '1 ed.', 'v.1', 'ex.1', 'p.902', 1, '15.00', 0, '003.', NULL, 1),
(1547, '2022-01-01', 'Hely Lopes Meirelles', 'Direito de Construir', NULL, '346.07', NULL, 'São Paulo', 'Malheiros', NULL, NULL, '1996', '2 ed.', 'v.1', 'ex.1', 'p.510', 1, '15.00', 0, '003.', NULL, 1),
(1548, '2022-01-01', 'Aline de Miranda Valverde Terra', 'Direito civil constitucional', '342(81)', '342', '978.85.970.0354.3', 'São Paulo', 'Atlas S.A.', NULL, NULL, '2016', NULL, 'v.1', 'ex.1', 'p.242', 1, '15.00', 0, '003.', NULL, 1),
(1549, '2022-01-01', 'Câmara dos Deputados - Congresso Nacional', 'Série Textos Básicos - Regimeto Interno da Câmara dos Deputados - Nº 74', '342.532(81)\"1989\"', '348', '978.85.402.0122.4', 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '2013', NULL, 'v.74', 'ex.1', 'p.415', 1, '15.00', 0, '003.', NULL, 1),
(1550, '2022-01-01', 'Fran Martins', 'Curso de direito constitucional', '347(81)/ 342.2', '346.07', '978.85.309.6757.4', 'Rio de Janeiro', 'Forense Editora', NULL, NULL, '2016', NULL, 'v.1', 'ex.1', 'p.491', 1, '15.00', 0, '003.', NULL, 1),
(1551, '2022-01-01', 'Egberto Maia Luz', 'Direito Adminsitrativo disciplinar', '35.077', '342', NULL, 'São Paulo', 'Bushatsky Editora', NULL, NULL, '1977', NULL, 'v.1', 'ex.1', 'p.356', 1, '15.00', 0, '003.', NULL, 1),
(1552, '2022-01-01', 'John Keane', 'Vida e morte da democracia', NULL, '321.8', '978.85.62938.00.9', 'São Paulo', 'Ediçõe 70', NULL, NULL, '2010', NULL, 'v.1', 'ex.1', 'p.850', 1, '15.00', 0, '003.', NULL, 1),
(1553, '2022-01-01', 'SCHUBERT, Pedro ', 'Orçamento empresarial integrado', '658.15', '658.154', '85.216.0392.4', 'Rio de Janeiro', 'Livos técnicos e científicos', NULL, NULL, '1985', '1 ed.', NULL, 'ex.1', 'p.434', 1, '15.00', 0, '003.', NULL, 1),
(1554, '2022-01-01', 'Carlos Mallorquin', 'Cerso Furtado: um retrato intelectual', NULL, '338.981', '85.7587.040.8', 'São Paulo', 'Xamã Editora', NULL, NULL, '2005', '1 ed.', 'v.1', 'ex.1', 'p.368', 1, '15.00', 0, '003.', NULL, 1),
(1555, '2022-01-01', 'Wilbur Schramm', 'Comunicação de massa e desenvolvimento -  o papel da informação nos países em crescimento', NULL, '301', NULL, 'Rio de Janeiro', 'Bloch Editora', NULL, NULL, '1970', '1 ed.', 'v.1', 'ex.1', 'p.439', 1, '15.00', 0, '003.', NULL, 1),
(1556, '2022-01-01', 'Rui Portanova', 'Princípios do Processo civil', '347.9/ 347.97', '347', '978.85.7348.548.6', 'Porto Alegre', 'Livraria do Advogado', NULL, NULL, '2008', '1 ed.', 'v.1', 'ex.1', 'p.308', 1, '15.00', 0, '003.', NULL, 1),
(1557, '2022-01-01', 'Nelson Plaia', 'Técnicas da contestação', '347.924(81)', '341', '85.02.02323.3', 'São Paulo', 'Saraiva', NULL, NULL, '1997', '1 ed.', 'v.1', 'ex.1', 'p.350', 1, '15.00', 0, '003.', NULL, 1),
(1558, '2022-01-01', 'Luiz Mendes Antas', 'Dicionário de TérmosTécinicos - IngLê - Português - Volume 2', NULL, '008', NULL, 'São Paulo', 'Traço Editora', NULL, NULL, '1980', '1 ed.', 'v.2', 'ex.1', 'p.948', 1, '10.99', 0, '004.', NULL, 1),
(1559, '2022-01-01', 'Cezar Roberto Bitencourt', 'Tratado de Direito penal - Volume 4', '343', '345', '978.85.02.15159.8', 'São Paulo', 'Saraiva', NULL, NULL, '2012', '1 ed.', 'v.4', 'ex.1', 'p.573', 1, '20.00', 0, '003.', NULL, 1),
(1560, '2022-01-01', 'Roberto Senise Lisboa', 'Contatos difusos e coletivos: consumidor, meio ambiente, trabalho, agrário, locação ', '347.44', '347', '85.203.2989.6', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '2007', '1 ed.', 'v.1', 'ex.1', 'p.637', 1, '20.00', 0, '003.', NULL, 1),
(1561, '2022-01-01', 'Gilmar Ferreira Mendes', 'Jurisdição Constitucional', '340.131.5', '342', '85.02.04423.0', 'São Paulo', 'Saraiva', NULL, NULL, '2004', '1 ed.', 'v.1', 'ex.1', 'p.395', 1, '20.00', 0, '003.', NULL, 1),
(1562, '2022-01-01', 'BEZERRA, Juarez C. ', 'O desenvolvimento dos recursos humanos na busca da perpetuação da qualidade', NULL, '658.4', NULL, '***', '***', NULL, NULL, '1992', '1 ed.', NULL, 'ex.1', 'p.22', 1, '20.00', 0, '003.', NULL, 1),
(1563, '2022-01-01', 'Hugo de Brito Machado', 'Curso de direito tributário', NULL, '343', '85.392.0080.5', 'São Paulo', 'Malheiros', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.560', 1, '20.00', 0, '003.', NULL, 1),
(1564, '2022-01-01', 'Yussef Said Cahali', 'Responsabilidade civil do estado', NULL, '347', NULL, 'São Paulo', 'Malheiros', NULL, NULL, '1995', '1 ed.', 'v.1', 'ex.1', 'p.679', 1, '20.00', 0, '003.', NULL, 1),
(1565, '2022-01-01', 'Nelson Nery Costa', 'Processo Administrativo e suas espécies', '35.077.3', '342', '85.309.0472.9', 'Rio de Janeiro', 'Forense Editora', NULL, NULL, '1997', '1 ed.', 'v.1', 'ex.1', 'p.224', 1, '20.00', 0, '003.', NULL, 1),
(1566, '2022-01-01', 'Amílcar Castro', 'Do procedimento de execução', '347.91/ .95(81)(094.46)', '347', '85.309.0606.3', 'Rio de Janeiro', 'Forense Editora', NULL, NULL, '1999', '1 ed.', 'v.1', 'ex.1', 'p.324', 1, '20.00', 0, '003.', NULL, 1),
(1567, '2022-01-01', 'Gianpaolo Poggio Smanio', 'Séries Fundamentos Jurídicos - Interesses difusos e coletivos: Estatudo da criança e do adolescente, consumidor, meio ambiente, improbidade administrativa, ação civil pública e inquérito civil', '347.44', '340.1', '85.224.2410.1', 'São Paulo', 'Atlas S.A.', NULL, NULL, '1999', '1 ed.', 'v.1', 'ex.1', 'p.117', 1, '20.00', 0, '003.', NULL, 1),
(1568, '2022-01-01', 'Caio Márcio da Silva Pereira', 'Condomínio e Incorporações', '347.238(81)', '333', '978.85.309.6088.9', 'Rio de Janeiro', 'Forense Editora', NULL, NULL, '2016', '1 ed.', 'v.1', 'ex.1', 'p.482', 1, '20.00', 0, '003.', NULL, 1),
(1569, '2022-01-01', 'Hilário Tarloni', 'Estudo do Problema Brasileiro', NULL, '320.981', NULL, 'São Paulo', 'Pioneira Editora', NULL, NULL, '1976', '1 ed.', 'v.1', 'ex.1', 'p.300', 1, '20.00', 0, '003.', NULL, 1),
(1570, '2022-01-01', 'Rita Dias Nolasco', 'Execução de pré-executividadae', '347.952', '347', '85.86456.52.7', 'São Paulo', 'Método Editora', NULL, NULL, '2003', '1 ed.', 'v.1', 'ex.1', 'p.397', 1, '20.00', 0, '003.', NULL, 1),
(1571, '2022-01-01', 'Yussef Said Cahali', 'Dano Moral', '347.426.4/6', '347', '85.203.1643.3', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '1998', '1 ed.', 'v.1', 'ex.1', 'p.720', 1, '20.00', 0, '003.', NULL, 1),
(1572, '2022-01-01', 'Fernando Luiz Ximenes Rocha', 'Controle de constitucinalidade das leis municipais', '342.352(81)', '341', '85.224.3029.2', 'São Paulo', 'Atlas S.A.', NULL, NULL, '2001', '1 ed.', 'v.1', 'ex.1', 'p.287', 1, '20.00', 0, '003.', NULL, 1),
(1573, '2022-01-01', 'Euclides Benedito de Oliveira', 'Inventários e Partilhas: direito das sucessões: teoria e prática', '347.65(81)', '346.05', '85.7456.152.5', 'São Paulo', 'Universidades', NULL, NULL, '2003', '1 ed.', 'v.1', 'ex.1', 'p.679', 1, '20.00', 0, '003.', NULL, 1),
(1574, '2022-01-01', 'TRACY, Brian ', 'Estratégias Avançadas de vendas', NULL, '658.85', '978.85.428.0046.3', 'Barueri-SP', 'Novo Século Editora', NULL, NULL, '2014', '1 ed.', NULL, 'ex.1', 'p.461', 1, '20.00', 0, '003.', NULL, 1),
(1575, '2022-01-01', 'IACOCCA, Lee /  NOVAK, William', 'Iacocca - Uma biografia ', NULL, '922', NULL, 'São Paulo', 'Cultura Editora', NULL, NULL, '1984', '1 ed.', NULL, 'ex.1', 'p.399', 1, '20.00', 0, '003.', NULL, 1),
(1576, '2022-01-01', 'SERSON, José ', 'Curso de Rotinas trabalhistas', NULL, '658', NULL, 'São Paulo', 'LTR Editora', NULL, NULL, '1977', '1 ed.', 'v.1', 'ex.1', 'p.445', 1, '20.00', 0, '003.', NULL, 1),
(1577, '2022-01-01', 'DUARTE, João Ribeiro Mathias ', 'Desenvolvimento do procedimento licitatório: convite, tomada de preços, concorrência, doutrina, jurisprudência, prática', '34.351.712', '657.833', '85.7139.539.X', 'São Paulo', 'Universidades', NULL, NULL, '2004', '1 ed.', NULL, 'ex.1', 'p.303', 1, '20.00', 0, '003.', NULL, 1),
(1578, '2022-01-01', 'Senado Federal', 'Direitos sociais para todos: Estatuto da Criança e do adolescente, Estatuto do Idoso, Estatuto da pessoa com deficiência', NULL, '348', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2018', '1 ed.', 'v.1', 'ex.1', 'p.404', 1, '20.00', 0, '003.', NULL, 1),
(1579, '2022-01-01', 'E. Magalhães Noronha', 'Direto Penal - Dos crimes contra a pessoa. Dos crimes contra o patrimônio - Volume 2', NULL, '345', NULL, 'São Paulo', 'Saraiva', NULL, NULL, '1983', '1 ed.', 'v.1', 'ex.1', 'p.557', 1, '20.00', 0, '003.', NULL, 1),
(1580, '2022-01-01', 'PINEDO, Victor', 'Tsunami: construindo organizações capazes de prosperar em maremotos', NULL, '658.4063', '85.7312.372.9', 'São Paulo', 'Gente Editora', NULL, NULL, '2002', '1 ed.', NULL, 'ex.1', 'p.239', 1, '20.00', 0, '003.', NULL, 1),
(1581, '2022-01-01', 'NEPOMUCENO, F. ', 'Planejamento dos históricos contabeis', NULL, '667', NULL, 'São Paulo', 'SEI - Sociedade  Editora Ipanema', NULL, NULL, '1972', '1 ed.', 'v.1', 'ex.1', 'p.110', 1, '20.00', 0, '003.', NULL, 1),
(1582, '2022-01-01', 'ROSA, Josimar Santos ', 'Relações de consumo: a defesa dos interesses de consumidores e fornecedores', '347.381.8', '346.07', '85.224.1335.5', 'São Paulo', 'Atlas S.A.', NULL, NULL, '1995', '1 ed.', 'v.1', 'ex.1', 'p.139', 1, '20.00', 0, '003.', NULL, 1),
(1583, '2022-01-01', 'HOMEM, Homero ', 'Série Vagalume: Menino de asas', NULL, '028.5', NULL, 'São Paulo', 'Atlas S.A.', NULL, NULL, '1980', '1 ed.', NULL, 'ex.1', 'p.79', 1, '16.00', 0, '003.', NULL, 1),
(1584, '2022-01-01', 'LIMA, Aristides Fraga ', 'Série Vagalume: A serra dos dois meninos', NULL, '028.5', NULL, 'São Paulo', 'Atlas S.A.', NULL, NULL, '1980', '1 ed.', NULL, 'ex.1', 'p.110', 1, '16.00', 0, '004.', NULL, 1),
(1585, '2022-01-01', 'DUPRÉ, Maria José ', 'Série Vagalume: Éramos seis', NULL, '028.5', NULL, 'São Paulo', 'Atlas S.A.', NULL, NULL, '1980', '1 ed.', NULL, 'ex.1', 'p.192', 1, '16.00', 0, '004.', NULL, 1),
(1586, '2022-01-01', 'ALENCAR, José de ', 'Série Bom Livro - Os classicos da Literatura: Iracema', NULL, 'B869.93', NULL, 'São Paulo', 'Atlas S.A.', NULL, NULL, '1981', NULL, NULL, 'ex.1', 'p.91', 1, '10.00', 0, '003.', NULL, 1),
(1587, '2022-01-01', 'ALENCAR, José de ', 'Série Bom Livro - Os classicos da Literatura: O Guarani', NULL, 'B869.9334', NULL, 'São Paulo', 'Atlas S.A.', NULL, NULL, '1979', '8 ed.', NULL, 'ex.1', 'p.220', 1, '10.00', 0, '003.', NULL, 1),
(1588, '2022-01-01', 'POMPÉIA, Raul ', 'Série Bom Livro - Os classicos da Literatura: O ateneu', NULL, 'B869.9346', NULL, 'São Paulo', 'Atlas S.A.', NULL, NULL, '1979', '6 ed.', NULL, 'ex.1', 'p.150', 1, '10.00', 0, '003.', NULL, 1),
(1589, '2022-01-01', 'ASSIS, Machado de', 'Série Bom Livro - Os classicos da Literatura: Esaú e Jacó', NULL, 'B869.9341', NULL, 'São Paulo', 'Atlas S.A.', NULL, NULL, '1975', '1 ed.', NULL, 'ex.1', 'p.159', 1, '10.00', 0, '003.', NULL, 1),
(1590, '2022-01-01', 'ASSIS, Machado de', 'Série Bom Livro - Os classicos da Literatura: Memorial de Aires', NULL, 'B869.9341', NULL, 'São Paulo', 'Atlas S.A.', NULL, NULL, '1976', '3 ed.', NULL, 'ex.1', 'p.131', 1, '10.00', 0, '003.', NULL, 1),
(1591, '2022-01-01', 'ASSIS, Machado de', 'Série Bom Livro - Os classicos da Literatura: A mão e a luva', NULL, 'B869.9341', NULL, 'São Paulo', 'Atlas S.A.', NULL, NULL, '1979', '6 ed.', NULL, 'ex.1', 'p.97', 1, '10.00', 0, '003.', NULL, 1),
(1592, '2022-01-01', 'ALENCAR, José de ', 'Série Bom Livro - Os classicos da Literatura: O tronco do Ipê', NULL, 'B869.9334', NULL, 'São Paulo', 'Atlas S.A.', NULL, NULL, '1971', '2 ed.', NULL, 'ex.1', 'p.151', 1, '10.00', 0, '003.', NULL, 1),
(1593, '2022-01-01', 'TÁVORA, Franklin ', 'Série Bom Livro - Os classicos da Literatura: O Cabeleira', NULL, 'B869.9342', NULL, 'São Paulo', 'Atlas S.A.', NULL, NULL, '1981', '3 ed.', NULL, 'ex.1', 'p.143', 1, '10.00', 0, '003.', NULL, 1),
(1594, '2022-01-01', 'Roberto Brocanelli Corona (Org.)', '20 Anos da Constituição da República Ferativa do Brasil', NULL, '341.2481', '978.85.7818.019.5', 'Franca-SP', 'Universidades', NULL, NULL, '2008', '1 ed.', NULL, 'ex.1', 'p.304', 1, '15.00', 0, '003.', NULL, 1),
(1595, '2022-01-01', 'Ministério da Saúde', 'Série E. Legislação e Saúde: Estatuto do Idoso', NULL, '348', '85.334.1059.x', 'Brasília-DF', 'Ministérios', NULL, NULL, '2008', '1 ed.', NULL, 'ex.1', 'p.70', 1, '15.00', 0, '003.', NULL, 1),
(1596, '2022-01-01', 'Sídia Maria Porto Lima', 'Prestação de contas e financiamento de campanhas eleitorais', '342.8', '324.7', '85.362.1100.8', 'Curitiba-PR', 'Jurua', NULL, NULL, '2006', '1 ed.', 'v.1', 'ex.1', 'p.232', 1, '15.00', 0, '003.', NULL, 1),
(1597, '2022-01-01', 'GOLDMAN, H.M. ', 'A arte de vender', NULL, '659', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1971', '1 ed.', NULL, 'ex.1', 'p.253', 1, '15.00', 0, '003.', NULL, 1),
(1598, '2022-01-01', 'Carlos Roberto Gonçalves', 'Coleção Sinopses Jurídicas: Direito Civil', '347', '347', '85.02.02272.5', 'São Paulo', 'Saraiva', NULL, NULL, '1997', '1 ed.', 'v.1', 'ex.1', 'p.179', 1, '15.00', 0, '003.', NULL, 1),
(1599, '2022-01-01', 'RODRIGUES, Roberto Barbosa ', 'O juiz e a ética', '347.962.1(81)', '170', '978.85.7494.122.6', 'São Paulo', 'Iglu Editora', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p.219', 1, '15.00', 0, '003.', NULL, 1),
(1600, '2022-01-01', 'Norberto de Almeida Carride', 'Revelia no direito processual civil', NULL, '347', '85.85789.60.3', 'Campinas-SP', 'Copala Editora', NULL, NULL, '2000', '1 ed.', 'v.1', 'ex.1', 'p.271', 1, '15.00', 0, '003.', NULL, 1),
(1601, '2022-01-01', 'ACQUAVIVA, Marcus Cláudio', 'Notas introdutória à ética jurídica', '34.17', '170', '978.85.361.0923.7', 'São Paulo', 'LTR Editora', NULL, NULL, '2007', '1 ed.', NULL, 'ex.1', 'p.269', 1, '15.00', 0, '003.', NULL, 1),
(1602, '2022-01-01', 'BROWN, Clarles T. ', 'Introdução à eloquência - Volume 2', NULL, '808.04', NULL, 'Rio de Janeiro', 'Cultura Editora', NULL, NULL, '1961', '1 ed.', 'v.2', 'ex.1', 'p.610', 1, '15.00', 0, '003.', NULL, 1),
(1603, '2022-01-01', 'BUSCAGLIA, Leo F. ', 'Amor: um livro maravilhoso sobre a maior experiência da vida', '177.6', '158.2', '85.01.02258.6', 'Rio de Janeiro', 'Nova Era Editora', NULL, NULL, '2000', '1 ed.', NULL, 'ex.1', 'p.160', 1, '15.00', 0, '003.', NULL, 1),
(1604, '2022-01-01', 'COELHO, Paulo', 'O Aleph', '929.133', '100', '978.85.7542.577.0', 'Rio de Janeiro', 'Sextante', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p.255', 1, '15.00', 0, '003.', NULL, 1),
(1605, '2022-01-01', 'Odonel Urbano Gonçalves', 'Recursos no processo do trabalho', '347.955.331', '341.46', '85.7322.233.6', 'São Paulo', 'LTR Editora', NULL, NULL, '1997', '1 ed.', 'v.1', 'ex.1', 'p.184', 1, '15.00', 0, '003.', NULL, 1),
(1606, '2022-01-01', 'Assembleia Legislativa do São Paulo ', 'Temas do direito constitucional estadual e questões sobre o pacto federativo', NULL, '342', NULL, 'São Paulo', 'Assembleias Legislativas', NULL, NULL, '2004', '1 ed.', NULL, 'ex.1', 'p.206', 1, '15.00', 0, '003.', NULL, 1),
(1607, '2022-01-01', 'Hely Lopes Meirelles', 'Mandato de Segurança', NULL, '342', '85.7420.296.7', 'São Paulo', 'Malheiros', NULL, NULL, '2001', '1 ed.', NULL, 'ex.1', 'p.521', 1, '15.00', 0, '003.', NULL, 1);
INSERT INTO `librarycollection` (`id`, `registrationDate`, `author`, `title`, `cdu`, `cdd`, `isbn`, `local`, `publisher_edition`, `number`, `month`, `year`, `edition`, `volume`, `copyNumber`, `pageNumber`, `typeAcquisitionId`, `price`, `prohibitedSale`, `provider`, `exclusionInfoTerm`, `registeredByUserId`) VALUES
(1608, '2022-01-01', 'L. A. Costa Pinto', 'Retratos do Brasil: Sociologia e Desenvolvimento - Volume 20', NULL, '301', NULL, 'Rio de Janeiro', 'Civilização Brasileira', NULL, NULL, '1965', '1 ed.', NULL, 'ex.1', 'p.318', 1, '15.00', 0, '003.', NULL, 1),
(1609, '2022-01-01', 'Vários Autores', 'Trabalhos de Conclusão de Curso - Instituto Mauá de Tecnologia - 2009', NULL, '378.103', NULL, 'Mauá-SP', 'Universidades', NULL, NULL, '2009', '1 ed.', NULL, 'ex.1', 'p.207', 1, '15.00', 0, '003.', NULL, 1),
(1610, '2022-01-01', 'PAIXÃO, Marcia Valéria ', 'Série Marketing Ponto a ponto: Pesquisa e planejamentode marketing e propaganda', NULL, '658.8', '978.85.7838.801.0', 'Curitiba-PR', 'Ibpex', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p.175', 1, '15.00', 0, '003.', NULL, 1),
(1611, '2022-01-01', 'Maria Antônia de Souza', 'Educação de Jovens e Adultos', NULL, '374.981', '978.85.7838.637.5', 'Curitiba-PR', 'Ibpex', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p.198', 1, '15.00', 0, '003.', NULL, 1),
(1612, '2022-01-01', 'Valdemar P. da Luz', 'Manual Prático das contestações judiciais', '347.924(81)/ 347.924(81)(094.9', '340', '85.241.0327.2', 'Porto Alegre', 'Sagra Editora', NULL, NULL, '1998', '1 ed.', NULL, 'ex.1', 'p.157', 1, '15.00', 0, '003.', NULL, 1),
(1613, '2022-01-01', 'Edmir Netto de Araújo', 'Mandato de Segurança e autoridade coatora', '342.722(81)', '342', NULL, 'São Paulo', 'LTR Editora', NULL, NULL, '2000', '1 ed.', NULL, 'ex.1', 'p.176', 1, '15.00', 0, '003.', NULL, 1),
(1614, '2022-01-01', 'Fernando César Ferreira de Souza', 'Apelação Cívil', '347.956.6', '347.03', NULL, 'Curitiba-PR', 'Jurua', NULL, NULL, '1995', '1 ed.', NULL, 'ex.1', 'p.160', 1, '15.00', 0, '003.', NULL, 1),
(1615, '2022-01-01', 'LEMBO, Claudio', 'O jogo da coragem - Testemunho de um Liberal', NULL, '922', NULL, 'São Paulo', 'Cultura Editora', NULL, NULL, '1979', '1 ed.', NULL, 'ex.1', 'p.214', 1, '15.00', 0, '003.', NULL, 1),
(1616, '2022-01-01', 'ALMEIDA, Manuel Antônio de ', 'Série Literatura para o Vestibular: Memória de um sargento de milicias', NULL, '813', NULL, 'São Paulo', 'Escala Editora', NULL, NULL, NULL, '1 ed.', NULL, 'ex.1', 'p.160', 1, '15.00', 0, '003.', NULL, 1),
(1617, '2022-01-01', 'NEITZCHE, Friedrich ', 'Coleção Mestres Pensadores: Vontade de Potência - Parte 2', NULL, '100', NULL, 'São Paulo', 'Escala Editora', NULL, NULL, NULL, '1 ed.', NULL, 'ex.1', 'p.349', 1, '15.00', 0, '003.', NULL, 1),
(1618, '2022-01-01', 'SANTOS, Teobaldo Miranda ', 'Lendas e mitos do Brasil', NULL, '028.5', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1980', '1 ed.', NULL, 'ex.1', 'p.152', 1, '23.94', 0, '004.', NULL, 1),
(1619, '2022-01-01', 'Secretaria de Ciência e Tecnologia', 'Retrato Falado da alternância: Sustentando o desenvolvimento rural através da Educação', NULL, '323', NULL, 'São Paulo', 'Imprensa Oficial', NULL, NULL, NULL, '1 ed.', NULL, 'ex.1', 'p.180', 1, '15.00', 0, '003.', NULL, 1),
(1620, '2022-01-01', 'Vários Autores', 'Bárbaros diversos: coletânea de poesias', NULL, 'B869', '85.86905.03.8', 'São Paulo', 'Editora Rio-pretense', NULL, NULL, '1999', '1 ed.', NULL, 'ex.1', 'p.156', 1, '15.00', 0, '003.', NULL, 1),
(1621, '2022-01-01', 'SOUZA, Márcio', 'O brasileiro voador: um romance mais-leve-que-o-ar', NULL, '813', NULL, 'São Paulo', 'Círculo do Livro', NULL, NULL, '1990', '1 ed.', NULL, 'ex.1', 'p.228', 1, '15.00', 0, '003.', NULL, 1),
(1622, '2022-01-01', 'MOTTA, Leda Tenório da ', 'Catedral em obras: Ensaios de Literatura', NULL, 'B869', '85.85219.83.1', 'São Paulo', 'Iluminuras Editora', NULL, NULL, '1995', '1 ed.', NULL, 'ex.1', 'p.143', 1, '15.00', 0, '003.', NULL, 1),
(1623, '2022-01-01', 'Cesare Baccaria', 'Dos delitos e das penas', NULL, '345', NULL, 'São Paulo', 'Quartier  Latin', NULL, NULL, '2005', '1 ed.', 'v.1', 'ex.1', 'p.126', 1, '15.00', 0, '003.', NULL, 1),
(1624, '2022-01-01', 'ROTH, Geneen ', 'Mulheres, comida, Deus', NULL, '616.85260651', '978.85.63066.18.3', 'São Paulo', 'Lua de Papel', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p.190', 1, '15.00', 0, '003.', NULL, 1),
(1625, '2022-01-01', 'Tito Costa', 'Recursos em matéria eleitoral', '347.955:342.8(81)', '342.07', '85.203.2615.3', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '2004', '1 ed.', 'v.1', 'ex.1', 'p.245', 1, '15.00', 0, '003.', NULL, 1),
(1626, '2022-01-01', 'ANDRADE, Mário de ', 'Coleção de Mão em mão: São Paulo! Comoção de minha vida...', '821.134.3(81)', 'B869.91', '978.85.401.0086.2', 'São Paulo', 'UNIVERSIDADES', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.176', 1, '15.00', 0, '003.', NULL, 1),
(1627, '2022-01-01', 'Vários autores', 'Coleção de Mão em mão: Histórias de Horror', '821.134.2(82)3', 'B869.93', '978.85.393.0259.8', 'São Paulo', 'UNIVERSIDADES', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.152', 1, '15.00', 0, '003.', NULL, 1),
(1628, '2022-01-01', 'Carlos Alberto Bíttar', 'Os direitos da personalidade', '347.1', '347', '85.218.0143.2', 'São Paulo', 'Forense Editora', NULL, NULL, '1995', '1 ed.', NULL, 'ex.1', 'p.143', 1, '15.00', 0, '003.', NULL, 1),
(1629, '2022-01-01', 'ALMEIDA, Sérgio ', 'Como o monge atenderia o cliente', NULL, '658.812', '85.8561.95.4', 'Salvador-BA', 'Casa de qualidade', NULL, NULL, '2007', '1 ed.', NULL, 'ex.1', 'p.135', 1, '15.00', 0, '003.', NULL, 1),
(1630, '2022-01-01', 'FELIPPE, Donaldo J. ', 'Dicionário Jurídico de Bolso', NULL, '008', NULL, 'São Paulo', 'Julex livros', NULL, NULL, '1992', '1 ed.', NULL, 'ex.1', 'p.261', 1, '20.00', 0, '004.', NULL, 1),
(1631, '2022-01-01', 'ANDRÉ Gulherme Polito', 'Melhoramentos: minidicionário de sinônimos e antônimos', NULL, '008', '85.06.01988.5', 'São Paulo', 'Melhoramentos Editora', NULL, NULL, '1994', '1 ed.', NULL, 'ex.1', 'p.669', 1, '61.00', 0, '004.', NULL, 1),
(1632, '2022-01-01', 'FLAVIAN, Eugenia / FERNANDEZ, Gretel Eres ', 'Minidicionário de Espanhol - Português', NULL, '860', '85.08.04633.2', 'São Paulo', 'SPIGE', NULL, NULL, '1997', '1 ed.', NULL, 'ex.1', 'p.678', 1, '20.00', 0, '003.', NULL, 1),
(1633, '2022-01-01', 'GAILLARD, Philippe ', 'O jornalismo', NULL, '070.4', NULL, 'Lisboa', 'Europa Editora', NULL, NULL, '1974', '1 ed.', NULL, 'ex.1', 'p.118', 1, '20.00', 0, '003.', NULL, 1),
(1634, '2022-01-01', 'RITTNER, Mauricio ', 'Compreensão de Cinema', NULL, '778.5', NULL, 'São Paulo', 'São Paulo Editora', NULL, NULL, '1965', '1 ed.', NULL, 'ex.1', 'p.149', 1, '20.00', 0, '003.', NULL, 1),
(1635, '2022-01-01', 'Senado Federal', 'Energia: Normas Gerais e regulamentos ', NULL, '341.3444', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2009', '1 ed.', 'v.1', 'ex.1', 'p.194', 1, '20.00', 0, '003.', NULL, 1),
(1636, '2022-01-01', 'Luciana Corrêa do Lago', 'Desigualdades e segregação na metrópole: o Rio de Janeiro', '316.334.56(815.31)', '307.76', '85.7106.195.5', 'Rio de Janeiro', 'Revan Editora', NULL, NULL, '2000', '1 ed.', 'v.1', 'ex.1', 'p.240', 1, '20.00', 0, '003.', NULL, 1),
(1637, '2022-01-01', 'Senado Federal', 'Estatuto do Estrangeiro: regulamentação e legislação correlata', NULL, '342.32', '978.85.7018.511.2', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2013', '1 ed.', 'v.1', 'ex.1', 'p.104', 1, '20.00', 0, '003.', NULL, 1),
(1638, '2022-01-01', 'Senado Federal', 'Estatuto do Estrangeiro: regulamentação e legislação correlata', NULL, '342.32', '978.85.7018.511.2', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2013', '1 ed.', 'v.1', 'ex.2', 'p.104', 1, '20.00', 0, '003.', NULL, 1),
(1639, '2022-01-01', 'HOLANDA, Sérgio Baurque de', 'Coleção Brasiliana: Visão do Paraíso - Volume 333', NULL, '329', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1985', '1 ed.', 'v.333', 'ex.1', 'p.360', 1, '20.00', 0, '003.', NULL, 1),
(1640, '2022-01-01', 'ORTIGOZA, Silvia Aparecida Guarnieri ', 'No \"clima\" do consumo: implicação do consumo nas mudanças climáticas globais', NULL, '577.27', '978.85.61203.03.0', 'Rio Claro', 'Divisa Gráfica e Editora', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p.88', 1, '20.00', 0, '003.', NULL, 1),
(1641, '2022-01-01', 'Miguel Kfouri Neto', 'Responsabilidade Civil do Médico', '347.56.61', '344.041', '85.203.1644.1', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '1998', '1 ed.', 'v.1', 'ex.1', 'p.690', 1, '20.00', 0, '003.', NULL, 1),
(1642, '2022-01-01', 'Cláudia Aparecida Simardi', 'Proteção processual do posse ', '347.251', '347', '85.203.156.4', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '1997', '1 ed.', 'v.1', 'ex.1', 'p.274', 1, '20.00', 0, '003.', NULL, 1),
(1643, '2022-01-01', 'Wolgran Junqueira Ferreira', 'Responsabilidade dos Prefeitos e vereadores: Decreto-Lei nº 201/67 : Comentários, legislação, jurisprudência, de acordo com a Constituição Federal de 1988', '352.075.1(81)(094.56)', '340', '85.7283.161.4', 'Bauru-SP', 'Edipro', NULL, NULL, '1996', '1 ed.', 'v.1', 'ex.1', 'p.259', 1, '20.00', 0, '003.', NULL, 1),
(1644, '2022-01-01', 'Regina Maria Macedo Nery Ferrati', 'Efeitos da declaração de inconstitucionalidade ', '340.131.5', '342', '85.203.1715.4', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '1999', '1 ed.', 'v.1', 'ex.1', 'p.286', 1, '20.00', 0, '003.', NULL, 1),
(1645, '2022-01-01', 'Amilcare Carletti', 'Dos Aliementos: A lei', NULL, '347', NULL, 'São Paulo', 'Leud Editora', NULL, NULL, '1993', '1 ed.', 'v.1', 'ex.1', 'p.258', 1, '20.00', 0, '003.', NULL, 1),
(1646, '2022-01-01', 'Cleo Fante', 'Fenômeno bulling: como previnir a violência nas escolas e educar para a paz', NULL, '370.15', '985.85.87795.69.4', 'Campinas-SP', 'Verus Editora', NULL, NULL, '2005', '1 ed.', 'v.1', 'ex.1', 'p.224', 1, '20.00', 0, '003.', NULL, 1),
(1647, '2022-01-01', 'Helio Oliveira Portocarrero de Castro (Coord.)', 'Introdução ao mercado de capitais', NULL, '332.6', NULL, 'Rio de Janeiro', 'Instituto Brasileiro de Mercados e Capitais - IBMEC', NULL, NULL, '1979', '1 ed.', 'v.1', 'ex.1', 'p.192', 1, '20.00', 0, '003.', NULL, 1),
(1648, '2022-01-01', 'Brasil', 'Constituição da República Federativa do Brasil: promulgada em 5 de outrubro de 1988', '342.4(81)\"1988\"', '348', '82.02.04257.2', 'São Paulo', 'Saraiva', NULL, NULL, '2003', '1 ed.', NULL, 'ex.1', 'p.364', 1, '20.00', 0, '003.', NULL, 1),
(1649, '2022-01-01', 'Maria Amélia de Figueiredo Pereira Alvarenga (Org.)', 'Os novos parâmetros da responsabilidade civil e as relações sociais', NULL, '347', NULL, 'Franca-SP', 'Universidades', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.264', 1, '20.00', 0, '003.', NULL, 1),
(1650, '2022-01-01', 'ALVAREZ, Rodrigo', 'Aparecida: a biografia da santa que perdeu a cabeça, ficou negra, foi roubada e cobiçada pelos políticos e conquistou o Brasil', '929.2', '282', '978.85.250.5753.2', 'São Paulo', 'Globo', NULL, NULL, '2014', '1 ed.', NULL, 'ex.1', 'p.237', 1, '20.00', 0, '003.', NULL, 1),
(1651, '2022-01-01', 'José Nilo de Castro', 'Julgamento das contas municipais', '352(81)', '341.3160981', '85.7308.613.0', 'Belo Horizonte', 'Del Rey', NULL, NULL, '2003', '1 ed.', 'v.1', 'ex.1', 'p.158', 1, '20.00', 0, '003.', NULL, 1),
(1652, '2022-01-01', 'HARADA, Kiyoshi ', 'Sistemas tributários dos municípios de São Paulo', '34:336.2(816.11)(094.56)', '657', '85.203.1082.6', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '1993', '1 ed.', NULL, 'ex.1', 'p.261', 1, '20.00', 0, '003.', NULL, 1),
(1653, '2022-01-01', 'Joaquim Aguiar', 'A situação jurídica da Mulher Casada', NULL, '346.015', NULL, 'São Paulo', '***', NULL, NULL, '1969', '1 ed.', 'v.1', 'ex.1', 'p.40', 1, '20.00', 0, '003.', NULL, 1),
(1654, '2022-01-01', 'RIBEIRO, Lair ', 'Comunicação Global: a mágica da influência', '06.054', '070.4', '85.85363.64.9', 'Rio de Janeiro', 'Objetiva Ltda', NULL, NULL, '1993', '1 ed.', NULL, 'ex.1', 'p.131', 1, '20.00', 0, '003.', NULL, 1),
(1655, '2022-01-01', 'Karl Mannheim e W.A.C. Stewart', 'Introdução à sociologia da educação', NULL, '370.1', NULL, 'São Paulo', 'Cultrix Editora', NULL, NULL, NULL, NULL, 'v.1', 'ex.1', 'p.202', 1, '20.00', 0, '003.', NULL, 1),
(1656, '2022-01-01', 'Victor de Andrade ', 'Mosaico - memórias, Crõnicas e história', NULL, 'B869.93', NULL, 'Franca-SP', 'Ribeirão Gráfica e Editora', NULL, NULL, '2010', NULL, 'v.1', 'ex.1', 'p.364', 1, '20.00', 0, '003.', NULL, 1),
(1657, '2022-01-01', 'Agostinho Alvim', 'Aspectos da Locação predial', NULL, '333', NULL, 'São Paulo', 'Jurídica Brasileira, Editora ', NULL, NULL, '1966', NULL, 'v.1', 'ex.1', 'p.313', 1, '20.00', 0, '003.', NULL, 1),
(1658, '2022-01-01', 'LAGO, André Aranha Corrêia do', 'Estocolmo, Rio, Joanesburgo - o Brasil e as três conferências Ambientais das Nações Unidas', '504', '910', '85.7631.040.6', 'Brasília-DF', 'Instituto Rio Branco ', NULL, NULL, '2007', '1 ed.', NULL, 'ex.1', 'p.276', 1, '20.00', 0, '003.', NULL, 1),
(1659, '2022-01-01', 'Ana Célia Castro (Org.)', 'Desenvolvimento em debate: painéis do desenvolvimento brasileiro I - Volume 2', NULL, '338.981', '85.7478.091.x', 'Rio de Janeiro', 'BNDES', NULL, NULL, '2002', NULL, 'v.2', 'ex.1', 'p.400', 1, '20.00', 0, '003.', NULL, 1),
(1660, '2022-01-01', 'RATTNER, Henrique ', 'Planejamento urbano e regional', NULL, '711', NULL, 'São Paulo', 'Nacional', NULL, NULL, '1978', '1 ed.', NULL, 'ex.1', 'p.161', 1, '20.00', 0, '003.', NULL, 1),
(1661, '2022-01-01', 'Roberto Thomas Arruda', 'O direito de Alimentos: doutrina, jurisprudência e processo', NULL, '346.017', NULL, 'São Paulo', 'Universitária Livraria e Editora', NULL, NULL, '1986', '1 ed.', 'v.1', 'ex.1', 'p.265', 1, '20.00', 0, '003.', NULL, 1),
(1662, '2022-01-01', 'São Paulo', 'Coleção de Leis e Estatutos Brasileiros: Licitações, concessões e premissões na administração pública', '351.712.032.3.(81)', '348', '85.7060.315.0', 'São Paulo', 'Imprensa Oficial', NULL, NULL, '2004', '1 ed.', 'v.1', 'ex.1', 'p.141', 1, NULL, 1, '003.', NULL, 1),
(1663, '2022-01-01', 'São Paulo', 'Coleção de Leis e Estatutos Brasileiros: Estatuto da Criança e do Adolescente', '34:347.157(81)(094)', '348', '85.7060.314.2', 'São Paulo', 'Imprensa Oficial', NULL, NULL, '2004', '1 ed.', 'v.1', 'ex.1', 'p.223', 1, NULL, 1, '003.', NULL, 1),
(1664, '2022-01-01', 'Nadia Gaiofatto Gonçalves', 'Série Fundamentos da Educação: Constituição histórica da educação no Brasil', NULL, '370', '978.85.8212.127.6', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.186', 1, '20.00', 0, '003.', NULL, 1),
(1665, '2022-01-01', 'Alessandro de Melo', 'Série Fundamentos da Educação: Fundamentos socioculturais da educação', NULL, '370', '978.85.8212.230.3', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.278', 1, '20.00', 0, '003.', NULL, 1),
(1666, '2022-01-01', 'LUIZARI, Kátia', 'Comunicação empresarial eficaz: como falar e escrever bem', NULL, '658.452', '978.85.65704.61.8', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.211', 1, '20.00', 0, '003.', NULL, 1),
(1667, '2022-01-01', 'BONA, Nivea Canalli ', 'Publicidade e Propaganda: da agência à campanha', NULL, '658.8', '978.85.8212.023.1', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.293', 1, '20.00', 0, '003.', NULL, 1),
(1668, '2022-01-01', 'Hélio Borghi', 'Da Renúncia e da ausência no direito sucessório', NULL, '346', NULL, 'São Paulo', 'Universitária Livraria e Editora', NULL, NULL, '1997', '1 ed.', 'v.1', 'ex.1', 'p.358', 1, '20.00', 0, '003.', NULL, 1),
(1669, '2022-01-01', 'LEVI, Guido Carlos', 'Recusa de Vacinas - causas e consequências', NULL, '616.120', NULL, 'São Paulo', 'Segmento Editora', NULL, NULL, '2013', '1 ed.', NULL, 'ex.1', 'p.72', 1, '20.00', 0, '003.', NULL, 1),
(1670, '2022-01-01', 'Eduardo Fares Boeges', 'Código Nacinal de Trânsito didático e ilustrado', NULL, '348', NULL, 'São Paulo', 'Parma Editora', NULL, NULL, '1985', '1 ed.', 'v.1', 'ex.1', 'p.159', 1, '20.00', 0, '003.', NULL, 1),
(1671, '2022-01-01', 'José Luís Bizelli (Org.)', 'Gestão em momentos de crise: Programa Unesp para o Desenvolvimento sustentável de São Luiz do Paraitinga', '502(815.6)', '363.7098161', '978.85.7983.163.8', 'São Paulo', 'Cultura Acadêmica', NULL, NULL, '2011', '1 ed.', 'v.1', 'ex.1', 'p.208', 1, '20.00', 0, '003.', NULL, 1),
(1672, '2022-01-01', 'Edmir Netto de Araújo', 'Do negócio jurídico administrativo', '35.077', '346', '85.203.1006.0', 'São Paulo', 'Revista dos Tribunais', NULL, NULL, '1992', '1 ed.', 'v.1', 'ex.1', 'p.223', 1, '20.00', 0, '003.', NULL, 1),
(1673, '2022-01-01', 'Ronaldo Porto Macedo Junior', 'Coleção Teoria e história do direito: Ensaios de direito privado e social: contratos, meio ambiente e tutela coletiva', '34(04)', '340.1', '978.85.02.21479.8', 'São Paulo', 'Saraiva', NULL, NULL, '2015', '1 ed.', 'v.1', 'ex.1', 'p.292', 1, '20.00', 0, '003.', NULL, 1),
(1674, '2022-01-01', 'MORAES, Antonio Carlos Rodrigues de ', 'Combatendo o inimigo: como evitar o stress e gerenciar seu tempo', NULL, '158.7', '85.7312.249.8', 'São Paulo', 'Gente Editora', NULL, NULL, '1999', '1 ed.', NULL, 'ex.1', 'p.183', 1, '20.00', 0, '003.', NULL, 1),
(1675, '2022-01-01', 'Sylvio do Amaral', 'Falsidade documental', '343.522', '345.026.3', '85.86833.16.9', 'São Paulo', 'Millennium Editora', NULL, NULL, '2000', NULL, 'v.1', 'ex.1', 'p.288', 1, '20.00', 0, '003.', NULL, 1),
(1676, '2022-01-01', 'Assembleia Legislativa de SP', 'O parlamento e a sociedade', NULL, '351', NULL, 'São Paulo', 'Assembleias Legislativas', NULL, NULL, '2002', NULL, 'v.1', 'ex.1', 'p.142', 1, '20.00', 0, '003.', NULL, 1),
(1677, '2022-01-01', 'Brasil', 'Legislação sobre Direitos Aurorais', NULL, '342.28', '978.85.7018.299.9', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2010', NULL, 'v.1', 'ex.1', 'p.207', 1, '20.00', 0, '003.', NULL, 1),
(1678, '2022-01-01', 'Senado Federal', 'Lei de Diretrizes', NULL, '379.81', '85.7018.221.x', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2006', NULL, 'v.1', 'ex.1', 'p.177', 1, '20.00', 0, '003.', NULL, 1),
(1679, '2022-01-01', 'Alesp / ILP', 'Curso de Iniciação Política: A conquista da democracia; Política e organização social; Política, eleição e partidos; Ética e Política; Política e futuro', NULL, '320', NULL, 'São Paulo', 'ILP', NULL, NULL, '2003', NULL, 'v.1', 'ex.1', 'p.148', 1, NULL, 1, '004.', NULL, 1),
(1680, '2022-01-01', 'Alesp / ILP', 'Curso de Iniciação Política: A conquista da democracia; Política e organização social; Política, eleição e partidos; Ética e Política; Política e futuro', NULL, '320', NULL, 'São Paulo', 'ILP', NULL, NULL, '2003', NULL, 'v.1', 'ex.2', 'p.148', 1, NULL, 1, '004.', NULL, 1),
(1681, '2022-01-01', 'Alesp / ILP', 'Curso de Iniciação Política: A conquista da democracia; Política e organização social; Política, eleição e partidos; Ética e Política; Política e futuro', NULL, '320', NULL, 'São Paulo', 'ILP', NULL, NULL, '2003', NULL, 'v.1', 'ex.3', 'p.148', 1, NULL, 1, '004.', NULL, 1),
(1682, '2022-01-01', 'Alesp / ILP', 'Curso de Iniciação Política: A conquista da democracia; Política e organização social; Política, eleição e partidos; Ética e Política; Política e futuro', NULL, '320', NULL, 'São Paulo', 'ILP', NULL, NULL, '2003', NULL, 'v.1', 'ex.4', 'p.148', 1, NULL, 1, '004.', NULL, 1),
(1683, '2022-01-01', 'Alesp / ILP', 'Curso de Iniciação Política: A conquista da democracia; Política e organização social; Política, eleição e partidos; Ética e Política; Política e futuro', NULL, '320', NULL, 'São Paulo', 'ILP', NULL, NULL, '2003', NULL, 'v.1', 'ex.5', 'v148', 1, NULL, 1, '004.', NULL, 1),
(1684, '2022-01-01', 'Jadson Nunes Santos', 'Melhorar a acessibilidade só depende da sua atitude', NULL, '362.4', '978.85.87812.00.7', 'Itapevi-SP', 'Câmara Muncipal de Itapevi', NULL, NULL, '2020', NULL, 'v.1', 'ex.1', 'p.80', 3, NULL, 1, '**', NULL, 1),
(1685, '2022-01-01', 'Jadson Nunes Santos', 'Melhorar a acessibilidade só depende da sua atitude', NULL, '362.4', '978.85.87812.00.7', 'Itapevi-SP', 'Câmara Muncipal de Itapevi', NULL, NULL, '2020', NULL, 'v.1', 'ex.2', 'p.80', 3, NULL, 1, '**', NULL, 1),
(1686, '2022-01-01', 'Jadson Nunes Santos', 'Melhorar a acessibilidade só depende da sua atitude', NULL, '362.4', '978.85.87812.00.7', 'Itapevi-SP', 'Câmara Muncipal de Itapevi', NULL, NULL, '2020', NULL, 'v.1', 'ex.3', 'p.80', 3, NULL, 1, '**', NULL, 1),
(1687, '2022-01-01', 'Jadson Nunes Santos', 'Melhorar a acessibilidade só depende da sua atitude', NULL, '362.4', '978.85.87812.00.7', 'Itapevi-SP', 'Câmara Muncipal de Itapevi', NULL, NULL, '2020', NULL, 'v.1', 'ex.4', 'p.80', 3, NULL, 1, '**', NULL, 1),
(1688, '2022-01-01', 'Jadson Nunes Santos', 'Melhorar a acessibilidade só depende da sua atitude', NULL, '362.4', '978.85.87812.00.7', 'Itapevi-SP', 'Câmara Muncipal de Itapevi', NULL, NULL, '2020', NULL, 'v.1', 'ex.1', 'p.80', 3, NULL, 1, '**', NULL, 1),
(1689, '2022-01-01', 'ITAPEVI, Câmara Municipal de Itapevi', 'Agenda 2030: a Câmara Municipal de Itapevi que queremos', NULL, '342.05', '978.65.81697.00.6', 'Itapevi-SP', 'Câmara Muncipal de Itapevi', NULL, NULL, '2020', NULL, 'v.1', 'ex.1', 'p.72', 3, NULL, 1, '**', NULL, 1),
(1690, '2022-01-01', 'ITAPEVI, Câmara Municipal de Itapevi', 'Agenda 2030: a Câmara Municipal de Itapevi que queremos', NULL, '342.05', '978.65.81697.00.6', 'Itapevi-SP', 'Câmara Muncipal de Itapevi', NULL, NULL, '2020', '1 ed.', 'v.1', 'ex.2', 'p.72', 3, NULL, 1, '**', NULL, 1),
(1691, '2022-01-01', 'ITAPEVI, Câmara Municipal de Itapevi', 'Agenda 2030: a Câmara Municipal de Itapevi que queremos', NULL, '342.05', '978.65.81697.00.6', 'Itapevi-SP', 'Câmara Muncipal de Itapevi', NULL, NULL, '2020', '1 ed.', 'v.1', 'ex.3', 'p.72', 3, NULL, 1, '**', NULL, 1),
(1692, '2022-01-01', 'ITAPEVI, Câmara Municipal de Itapevi', 'Agenda 2030: a Câmara Municipal de Itapevi que queremos', NULL, '342.05', '978.65.81697.00.6', 'Itapevi-SP', 'Câmara Muncipal de Itapevi', NULL, NULL, '2020', '1 ed.', 'v.1', 'ex.4', 'p.72', 3, NULL, 1, '**', NULL, 1),
(1693, '2022-01-01', 'ITAPEVI, Câmara Municipal de Itapevi', 'Agenda 2030: a Câmara Municipal de Itapevi que queremos', NULL, '342.05', '978.65.81697.00.6', 'Itapevi-SP', 'Câmara Muncipal de Itapevi', NULL, NULL, '2020', '1 ed.', 'v.1', 'ex.5', 'p.72', 3, NULL, 1, '**', NULL, 1),
(1694, '2022-01-01', 'Assembleia Legislativa de SP', 'Temas de direito constituciona estadual e questões sobre o pacto federativo', NULL, '342', NULL, 'São Paulo', 'Assembleias Legislativas', NULL, NULL, '2004', NULL, NULL, 'ex.1', 'p.208', 1, NULL, 1, '003.', NULL, 1),
(1695, '2022-01-01', 'BNDES', 'Programa de Apoio a criança e jovens em situação de risco', NULL, '323', NULL, '**', 'BNDES', NULL, NULL, '2020', NULL, NULL, 'ex.1', 'p.112', 1, '15.00', 0, '003.', NULL, 1),
(1696, '2022-01-01', 'Ramon Leandro Freitaws Arnoni', 'Vade Mecum 800 em 1', '340', '348', '978.85.99895.15.3', 'São Paulo', 'Lemos e Cruz', NULL, NULL, '2007', NULL, NULL, 'ex.1', 'p.1477', 1, '30.00', 0, '003.', NULL, 1),
(1697, '2022-01-01', 'Antonio Luiz de Toledo Pinto (ORG.)', 'Vade Mecum obra coletiva de autores da Saraiva ', '34(81)(02)', '348', '978.85.02.08304.2', 'São Paulo', 'Saraiva', NULL, NULL, '2009', NULL, NULL, 'ex.1', 'p.1832', 1, '30.00', 0, '003.', NULL, 1),
(1698, '2022-01-01', 'Lúcia Avelar e Antonio Octávio (Org.)', 'Sistema Politico Brasileiro: uma introdução', NULL, '320', '978.7504.108.6', 'Rio de Janeiro', 'Instututo Konrad Adenauer', NULL, NULL, '2007', NULL, NULL, 'ex.1', 'p.496', 1, '15.00', 0, '003.', NULL, 1),
(1699, '2022-01-01', 'BRUNO, Ernani Silva ', 'Memória da Cidade de São Paulo - Série Registros n.4', NULL, '981.61', NULL, 'São Paulo', 'Secretarias ', 'n.4', NULL, '1981', '1 ed.', NULL, 'ex.1', 'p.218', 1, '15.00', 0, '003.', NULL, 1),
(1700, '2022-01-01', 'SAPUCAHY, Márcio Lúcio ', '1822 - Um botânico europeu em viagem pelo Vale do Paraíba', NULL, 'B869.93', '85.900670.1.7', 'São José dos Campos - SP', '**', NULL, NULL, '1998', '1 ed.', NULL, 'ex.1', 'p. 218', 1, '15.00', 0, '003.', NULL, 1),
(1701, '2022-01-01', 'Ministério das Relações Exteriores de São Paulo', 'De Tordesilhas ao Mercosul: uma exposição da história diplomática brasilieira', ' ', '327', NULL, 'São Paulo', 'Ministérios', NULL, NULL, NULL, '1 ed.', 'v.1', 'ex.1', 'p.76', 1, '15.00', 0, '003.', NULL, 1),
(1702, '2022-01-01', 'Museu de Artes de São Paulo ', 'Itália - Brasil - Relações desde o século XVI', NULL, '700', NULL, 'São Paulo', 'Museu de Artes São Paulo', NULL, NULL, '1980', '1 ed.', NULL, 'ex.1', 'p. 135', 1, '15.00', 0, '003.', NULL, 1),
(1703, '2022-01-01', 'FRENETTE,  Marco ', 'Os caiçaras contam', NULL, '080.96', NULL, 'São Paulo', 'Pulisher Brasil', NULL, NULL, '2000', '1 ed.', 'v.1', 'ex.1', 'p. 80', 1, '15.00', 0, '003.', NULL, 1),
(1704, '2022-01-01', 'OHTAKE, Ricardo / FERREIRA, Jussara Nunes', 'O olhar e o ficar. A busca do paraíso. 170 anos da imigração dos povos de língua alemã', NULL, '980', NULL, 'São Paulo', 'Museu do Imigrante', NULL, NULL, '1994', '1 ed.', NULL, 'ex.1', 'p. 51', 1, '15.00', 0, '003.', NULL, 1),
(1705, '2022-01-01', 'INFRAERO', 'No ar - 60 anos do Aeroporto de Congonhas', NULL, '921', NULL, 'São Paulo', 'Abril Editora', NULL, NULL, '1996', '1 ed.', NULL, 'ex.1', 'p. 112', 1, '15.00', 0, '003.', NULL, 1),
(1706, '2022-01-01', 'SOARES, Fernandes ', 'Rosa do Mar', NULL, 'B869', NULL, 'São Paulo', '**', NULL, NULL, '1979', '8 ed.', NULL, 'ex.2', 'p. 121', 1, '15.00', 0, '003.', NULL, 1),
(1707, '2022-01-01', 'WALLACE, Irving ', 'O Homem', NULL, '810', NULL, 'Lisboa', 'Livraria Clássica Editora', NULL, NULL, '1975', '6 ed.', NULL, 'ex.1', 'p. 706', 1, '15.00', 0, '003.', NULL, 1),
(1708, '2022-01-01', 'COSTA, Flávio Moreira da ', 'As armas e os barões', NULL, 'B869.93', NULL, 'Rio de Janeiro', 'Imago Editora Ltda', NULL, NULL, '1974', '1 ed.', NULL, 'ex.1', 'p. 144', 1, '15.00', 0, '003.', NULL, 1),
(1709, '2022-01-01', 'SETÚBAL, Paulo', 'O sonho das esmeraldas', NULL, 'B869.93', NULL, 'São Paulo', 'Editora Nacional', NULL, NULL, '1983', '5 ed.', NULL, 'ex.1', 'p. 179', 1, '15.00', 0, '003.', NULL, 1),
(1710, '2022-01-01', 'HERMINGWAY, Ernest ', 'Adeus às Armas ', NULL, '823', NULL, 'São Paulo', 'Editora Nacional', NULL, NULL, '1965', '1 ed.', NULL, 'ex.1', 'p.267', 1, '15.00', 0, '003.', NULL, 1),
(1711, '2022-01-01', 'BENTIM, Celso', 'No palácio da alvorada', NULL, '028.5', NULL, 'São Paulo', 'Edições MM', NULL, NULL, '1975', NULL, NULL, 'ex.1', 'p. 94', 1, '6.00', 0, '004.', NULL, 1),
(1712, '2022-01-01', 'ANDRADE, Jorge de ', 'Lembranças de Itapevi: imagens que contam a história de uma cidade', NULL, '770', '978.85.7935.115.0', 'São Paulo', 'Lothus Editorial', NULL, NULL, '2017', '1 ed.', NULL, 'ex.1', 'p.96', 1, '15.00', 0, '003.', NULL, 1),
(1713, '2022-01-01', 'JESUS, Vera Tereza de ', 'Ela e a reclusão: O condenado poderia ser você', NULL, 'B869.93', NULL, 'São Paulo', 'Edições O livreiro', NULL, NULL, NULL, '2 ed.', NULL, 'ex.1', 'p. 314', 1, '15.00', 0, '003.', NULL, 1),
(1714, '2022-01-01', 'ANDRADE, Mario de ', 'Obras completas de mario de Andrade - Macunaíma: o herói sem nenhum caráter - Volume 4', '869(081)34', 'B869.93', NULL, 'Brasília-DF', 'INL', NULL, NULL, '1984', '20 ed.', 'v.4', 'ex.1', 'p. 135', 1, '15.00', 0, '003.', NULL, 1),
(1715, '2022-01-01', 'AMADO, Jorge', 'Tocaia Grande: a face oculta: Romance', '869.0(81)31', 'B869.93', NULL, 'Rio de Janeiro', 'Record', NULL, NULL, '1984', '1 ed.', NULL, 'ex.1', 'p.505', 1, '15.00', 0, '003.', NULL, 1),
(1716, '2022-01-01', 'ALVAREZ, Walter C. ', 'Viva em paz com seus nervos', NULL, '150', NULL, 'São Paulo', 'Civilização Brasileira', NULL, NULL, '1959', '1 ed.', NULL, 'ex.1', 'p.209', 1, '15.00', 0, '003.', NULL, 1),
(1717, '2022-01-01', 'GRAHAM, Winston ', 'Marnie: Confissões de uma ladra', NULL, '813', NULL, '**', 'Boa Leitura Editora S.A.', NULL, NULL, '1961', '1 ed.', NULL, 'ex.1', 'p.332', 1, '15.00', 0, '003.', NULL, 1),
(1718, '2022-01-01', ' CARAMEZ, João ', 'Ética na Política', NULL, '921', NULL, 'São Paulo', 'Life Press', NULL, NULL, '2002', '1 ed.', NULL, 'ex.1', 'p.214', 1, '15.00', 0, '003.', NULL, 1),
(1719, '2022-01-01', 'DE MASI, Domenico ', 'A emoção e a regra: os grupos criativos na Europa de 1850 a 1950', '940', '940.285', '85.03.00612.x', 'Rio de Janeiro', 'José Olimpyo', NULL, NULL, '1999', '3 ed.', NULL, 'ex.1', 'p.419', 1, '15.00', 0, '003.', NULL, 1),
(1720, '2022-01-01', 'WALLACE, Irving ', 'A Senha', NULL, '810', NULL, 'Rio de Janeiro', 'Nova Fronteira', NULL, NULL, '1972', '5 ed.', NULL, 'ex.1', 'p. 490', 1, '15.00', 0, '003.', NULL, 1),
(1721, '2022-01-01', 'LIMA, Ébion de', 'Curso de Literatura Brasileira - Volume 1', NULL, '808', NULL, 'São Paulo', 'Coleção FTD LTDA', NULL, NULL, NULL, '1 ed.', 'v.1', 'ex.1', 'p. 218', 1, '15.00', 0, '003.', NULL, 1),
(1722, '2022-01-01', 'LIMA, Ébion de', 'Curso de Literatura Brasileira - Volume 2', NULL, '808', NULL, 'São Paulo', 'Coleção FTD LTDA', NULL, NULL, NULL, '1 ed.', 'v.2', 'ex.1', 'p. 349', 1, '15.00', 0, '003.', NULL, 1),
(1723, '2022-01-01', 'LIMA, Ébion de', 'Curso de Literatura Brasileira - Volume 3', NULL, '808', NULL, 'São Paulo', 'Coleção FTD LTDA', NULL, NULL, NULL, '1 ed.', 'v.3', 'ex.1', 'p. 351', 1, '15.00', 0, '003.', NULL, 1),
(1724, '2022-01-01', 'Sérgio Gonini Benício (Coord.)', 'Temas de Dissertação nos concursos da magistratura federal', '347.962(81)(043.3)', '347', '85.99003.06.2', 'São Paulo', 'Editora Federal', NULL, NULL, '2006', NULL, 'v.1', 'ex.1', 'p.512', 1, '15.00', 0, '003.', NULL, 1),
(1725, '2022-01-01', 'MURE, Pierre Da', 'Moulin Rouge', NULL, '843', NULL, 'São Paulo', 'Editora Mérito S.A', NULL, NULL, '1959', '5 ed.', NULL, 'ex.1', 'p.453', 1, '15.00', 0, '003.', NULL, 1),
(1726, '2022-01-01', 'TROTSKI, Leon', 'Pantheon Universal - Stalin ', NULL, '922', NULL, 'São Paulo', 'Instituto Progresso Editorial', NULL, NULL, '1947', '1 ed.', NULL, 'ex.1', 'p. 628', 1, '15.00', 0, '003.', NULL, 1),
(1727, '2022-01-01', 'Roberto da Matta', 'Universo do carnaval: reflexões e imagens', NULL, '394.25', NULL, 'Rio de Janeiro', 'Pinakitheke', NULL, NULL, '1981', NULL, 'v.1', 'ex.1', 'p. 112', 1, '15.00', 0, '003.', NULL, 1),
(1728, '2022-01-01', 'Aldrin Moura de Figueiredo (org.)', 'Pedra &Alma: 30 anos do Instituto do Patrimônio histórico e artístico Nacional no Pará (1979-2009)', NULL, '363.69', NULL, 'Belém-PA', 'IPHAN(PA)', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.100', 1, '15.00', 0, '003.', NULL, 1),
(1729, '2022-01-01', 'Antônio Couto de Andrade', 'Coleção Portas Abertas: Constituinte: assembleia permanente do povo - Volume 1', '342.4', '320', NULL, 'São Paulo', 'Ed. Nacional', NULL, NULL, '1985', '1 ed.', 'v.1', 'ex.1', 'p.100', 1, '15.00', 0, '003.', NULL, 1),
(1730, '2022-01-01', 'ALMEIDA, Lúcia Machado de', 'Passeio a Sabará - Volume 1', NULL, 'B869', NULL, 'São Paulo', 'Martisn Editora', NULL, NULL, '1960', '2 ed.', 'v.1', 'ex.1', 'p. 187', 1, '15.00', 0, '003.', NULL, 1),
(1731, '2022-01-01', 'Brasil - Divisão de Justiça Jurídica', 'Eleições 2002', '342.8(81)', '348', NULL, 'São Paulo', 'Assembleias Legislativas', NULL, NULL, '2002', NULL, 'v.1', 'ex.1', 'p.248', 1, '15.00', 0, '003.', NULL, 1),
(1732, '2022-01-01', 'Gustavo Becker e Octavio Mendonça Telles', 'Código eleitoral anotado e manualizado', NULL, '341.28', NULL, 'Brasília-DF', 'Brasília Jurídica', NULL, NULL, '1998', '1 ed. ', 'v.1', 'ex.1', 'p. 383', 1, '15.00', 0, '003.', NULL, 1),
(1733, '2022-01-01', 'Mozart Victor Russumano', 'Curso de direito do trabalho', '331.1', '344.01', NULL, 'Curitiba-PR', 'Juruá', NULL, NULL, '1993', '4 ed. ', 'v.1', 'ex.1', 'p. 466', 1, '15.00', 0, '003.', NULL, 1),
(1734, '2022-01-01', 'Walter Cruz Swensson', 'Lei de registros públicos anotada: anotações doutrinárias; anotações legislativas; anotações jurisprudencia; anotações normas da CGJ', NULL, '346810438', '85.7453.155.3', 'São Paulo', 'Juarez de Oliveira Ltda', NULL, NULL, '2000', '1 ed. ', 'v.1', 'ex.1', 'p. 460', 1, '15.00', 0, '003.', NULL, 1),
(1735, '2022-01-01', 'Ronald Dworkin', 'Uma questão de princípios', '340.12', '300', '85.336.2111.6', 'São Paulo', 'Martins fontes', NULL, NULL, '2005', '2 ed. ', 'v.1', 'ex.1', 'p. 593', 1, '15.00', 0, '003.', NULL, 1),
(1736, '2022-01-01', 'Paulo César Corrêa Borges', 'Marcadores Sociais da diferença e repressão penal', NULL, '300', '978.85.7983.150.8', 'São Paulo', 'Cultura Acadêmica', NULL, NULL, '2011', '1 ed. ', 'v.1', 'ex.1', 'p.238', 1, '15.00', 0, '003.', NULL, 1),
(1737, '2022-01-01', 'Ives Gandra da Silva Martins', 'Conheça a constituição: comentários à Constituição Brasileira - Volume 1', '342.4(81)', '348', '85.204.2303.5', 'Barueri-SP', 'Manole Editora', NULL, NULL, '2005', '1 ed. ', 'v.1', 'ex.1', 'p. 134', 1, '15.00', 0, '003.', NULL, 1),
(1738, '2022-01-01', 'Carlos Eduardo de Abreu Boucault', 'Direitos Adquiridos no Direito internacional privado', NULL, '346', NULL, 'Porto Alegre', 'Sergio Antonio Fabris Editor', NULL, NULL, '1996', '1 ed. ', 'v.1', 'ex.1', 'p. 118', 1, '15.00', 0, '003.', NULL, 1),
(1739, '2022-01-01', 'Roger Cousinet', 'Atividades Pedagógicas: A formação do educador e a pedagogia da aprendizagem - Volume 112', NULL, '370.7', NULL, 'São Paulo', 'Editora Nacional', NULL, NULL, '1974', '1 ed. ', 'v.112', 'ex.1', 'p. 186', 1, '15.00', 0, '003.', NULL, 1),
(1740, '2022-01-01', 'Luiz Carlos Rocha', 'Doping na legislação penal e desportiva', '343.575: 796(81)(094)', '340', '85.7283.236.x', 'Bauru-SP', 'Edipro', NULL, NULL, '1999', ' 1 ed. ', 'v.1', 'ex.1', 'p.252', 1, '15.00', 0, '003.', NULL, 1),
(1741, '2022-01-01', 'Iva Waisberg Bonow', 'Atividades Pedagógicas: Psicologia educacional e desenvolvimento humano: fundamentos psicossociais da educação - Volume 87', NULL, '370.15', NULL, 'São Paulo', 'Editora Nacional', NULL, NULL, '1972', ' 5 ed. ', 'v.87', 'ex.1', 'p.388', 1, '15.00', 0, '003.', NULL, 1),
(1742, '2022-01-01', 'Marcos Bernardes de Mello', 'Teoria do fato jurídico: plano da existência', '347.13', '340.1', '978.85.472.1246.9', 'São Paulo', 'Saraiva', NULL, NULL, '2017', '21 ed.', 'v.1', 'ex.1', 'p. 359', 1, '15.00', 0, '003.', NULL, 1),
(1743, '2022-01-01', 'BALDUS, Herbert', 'Série \"Brasiliana\" Grandes Formatos: Tapirapé - tribo tupí no Brasil Central - Volume 17', NULL, 'B869', NULL, 'São Paulo', 'Editora Nacional', NULL, NULL, '1970', '1 ed. ', 'v.17', 'ex.1', 'p. 509', 1, '15.00', 0, '003.', NULL, 1),
(1744, '2022-01-01', 'SODRÉ, Nelson Werneck ', 'Coleção Vera Cruz (Literarura Brasileira) - História da Literatura Brasileira - Volume 60', NULL, 'B869', NULL, 'Rio de Janeiro', 'Civilização Brasileira', NULL, NULL, '1969', ' 5 ed. ', 'v.60', 'ex.1', 'p. 596', 1, '15.00', 0, '003.', NULL, 1),
(1745, '2022-01-01', 'William Francis Cunningham', 'Introdução à educação', '37.01', '370.1', NULL, 'Brasília-DF', 'INL', NULL, NULL, '1975', ' 2 ed. ', 'v.1', 'ex.1', 'p. 506', 1, '15.00', 0, '003.', NULL, 1),
(1746, '2022-01-01', 'Nélson Godoy Bassil Dower', 'Instituições de direito público e privado', '342', '346', NULL, 'São Paulo', 'Atlas S.A.', NULL, NULL, '1985', ' 6 ed. ', 'v.1', 'ex.1', 'p. 391', 1, '15.00', 0, '003.', NULL, 1),
(1747, '2022-01-01', 'Ana Célia Castro (Org.)', 'Desenvolvimento em debate: novos rumos do desenvolvimento no mundo - Volume 1', NULL, '338', NULL, 'Rio de Janeiro', 'BNDES', NULL, NULL, '2002', '1 ed. ', 'v.1', 'ex.1', 'p. 452', 1, '15.00', 0, '003.', NULL, 1),
(1748, '2022-01-01', 'Celso Ribeiro Bastos', 'Curso de direito financeiro e de direito tributário', '34:336', '346.07', '85.02.00812.9', 'São Paulo', 'Saraiva', NULL, NULL, '1991', '1 ed. ', 'v.1', 'ex.1', 'p. 273', 1, '15.00', 0, '003.', NULL, 1),
(1749, '2022-01-01', 'HALLEWELL, Laurence ', 'O livro no Brasil: sua história -Coleção Coroa Vermelha: Estudos Brasileiros- Volume 6', NULL, '001.552', NULL, 'São Paulo', 'Universidade de São Paulo', NULL, NULL, '1985', '1 ed. ', 'v.6', 'ex.1', 'p.693', 1, '10.00', 0, '004.', NULL, 1),
(1750, '2022-01-01', 'Armando Antonio Sobreiro Neto', 'Direito Eleitoral - teoria e prática', '342.8', '342.07', '85.362.0251.3', 'Curitiba-PR', 'Juruá', NULL, NULL, '2003', '2 ed. ', 'v.1', 'ex.1', 'p. 404', 1, '15.00', 0, '003.', NULL, 1),
(1751, '2022-01-01', 'Peter Ferdinand Drucker', 'Coleção novos umbrais - A sociedade pós-capitalista', NULL, '330.9', NULL, 'São Paulo', 'Pioneira', NULL, NULL, '1997', '6 ed. ', 'v.1', 'ex.1', 'p.186', 1, '15.00', 0, '003.', NULL, 1),
(1752, '2022-01-01', 'Geraldo Tadeu Moreira Monteiro', 'Série Política Contemporânea: Manual prático de campanha eleitoral', '316.7', '342.07', '85.9855.01.0', 'Rio de Janeiro', 'Gramma Liv. E Ed.', NULL, NULL, '2004', '1 ed. ', 'v.1', 'ex.1', 'p.394', 1, '15.00', 0, '003.', NULL, 1),
(1753, '2022-01-01', 'Supremo Tribunal Federal ', 'Ação direta de inconstitucionalidade : jurisprudência (1988 -1991) - Volume 1', NULL, '341.205', '85.7469.234-4', 'Brasília-DF', 'STF', NULL, NULL, '2004', '1 ed. ', 'v.1', 'ex.1', 'p.617', 1, '15.00', 0, '003.', NULL, 1),
(1754, '2022-01-01', 'Supremo Tribunal Federal ', 'Ação direta de inconstitucionalidade : jurisprudência (1992 -1993) - Volume 2', NULL, '341.205', '85.7469.237.9', 'Brasília-DF', 'STF', NULL, NULL, '2004', '1 ed. ', 'v.2', 'ex.1', 'p. 546', 1, '15.00', 0, '003.', NULL, 1),
(1755, '2022-01-01', 'Supremo Tribunal Federal ', 'Ação direta de inconstitucionalidade : jurisprudência (1996 - 1997) - Volume 4', NULL, '341.205', '85.7469.273.9', 'Brasília-DF', 'STF', NULL, NULL, '2004', '1 ed. ', 'v.4', 'ex.1', 'p. 567', 1, '15.00', 0, '003.', NULL, 1),
(1756, '2022-01-01', 'Supremo Tribunal Federal ', 'Ação direta de inconstitucionalidade : jurisprudência (1998 - 1999) - Volume 5', NULL, '341.205', '85.7469.272.7', 'Brasília-DF', 'STF', NULL, NULL, '2004', '1 ed. ', 'v.5', 'ex.1', 'p. 487', 1, '15.00', 0, '003.', NULL, 1),
(1757, '2022-01-01', 'Oscar de Macedo Soares', 'História do Direito Brasileiro: Código penal da República dos Estados Unidos do Brasil - Volume 6', NULL, '341.5', NULL, 'Brasília-DF', 'Senado Federal ', NULL, NULL, '2004', '1 ed. ', 'v.6', 'ex.1', 'p. 860', 1, '15.00', 0, '003.', NULL, 1),
(1758, '2022-01-01', 'Caio Mario da Silva Pereira', 'Instituições de direito civil - Volume 2', NULL, '347', NULL, 'Rio de Janeiro', 'Forense', NULL, NULL, NULL, NULL, 'v.2', 'ex.1', 'p. 318', 1, '15.00', 0, '003.', NULL, 1),
(1759, '2022-01-01', 'Ecelin Maciel (org.)', 'Série Gestão institucional: Relatórios - Convergência e integração na comunicação pública nº3', '342.532: 659.3(81)', '340', '978.85.402.0070.8', 'Brasília-DF', 'Câmra dos Deputados', NULL, NULL, '2013', NULL, 'v.3', 'ex.1', 'p. 158', 1, '15.00', 0, '003.', NULL, 1),
(1760, '2022-01-01', 'Assembleia Legiaslativa', 'Constituião do Estado de São Paulo - Constituição Federal Atualizada (2009)', '342.4(815.6)', '348', NULL, 'São Paulo', 'Imprensa Oficial', NULL, NULL, '2009', '1 ed. ', 'v.1', 'ex.1', 'p.520', 1, '15.00', 0, '003.', NULL, 1),
(1761, '2022-01-01', 'Maria da Glória Lins da Silva Colucci e José Maurício Pinto de Almeida', 'Lições de Teoria Geral do Processo', NULL, '341.46', NULL, 'Curitiba-PR', 'Juruá', NULL, NULL, '1997', '4 ed. ', 'v.1', 'ex.1', 'p. 1988', 1, '15.00', 0, '003.', NULL, 1),
(1762, '2022-01-01', 'Álvaro Villaça Azevedo', 'Dever de coabitação: inadimplemento', '347.625.13', '340', NULL, 'São Paulo', 'José Bushatsky Editor', NULL, NULL, '1976', NULL, 'v.1', 'ex.1', 'p. 318', 1, '15.00', 0, '003.', NULL, 1),
(1763, '2022-01-01', 'Luiz Alberto David Araujo', 'Curso de direito constitucional', '342', '342', '85.02.02595.3', 'São Paulo', 'Saraiva', NULL, NULL, '1998', NULL, 'v.1', 'ex.1', 'p.382', 1, '15.00', 0, '003.', NULL, 1),
(1764, '2022-01-01', 'José Cretella Júnior', 'Teoria e prática do direito administrativo', '35/ 35(81)/ 341.3', '342', NULL, 'Rio de Janeiro', 'Forense', NULL, NULL, '1979', '1 ed. ', 'v.1', 'ex.1', 'p. 410', 1, '15.00', 0, '003.', NULL, 1),
(1765, '2022-01-01', 'Julio Fabbrini Mirabete', 'Manual de direito penal', NULL, '343', '85.224.1498.x', 'São Paulo', 'Atlas S.A.', NULL, NULL, '1996', '11 ed.', 'v.1', 'ex.1', 'p. 449', 1, '15.00', 0, '003.', NULL, 1),
(1766, '2022-01-01', 'Maria Helena Diniz', 'Curso de Direito civil brasileiro: Teoria Geral do direito civil - Volume 1', '347(81)', '347', '85.02.00366.6', 'São Paulo', 'Saraiva', NULL, NULL, '1995', '11 ed.', 'v.1', 'ex.1', 'p. 300', 1, '15.00', 0, '003.', NULL, 1),
(1767, '2022-01-01', 'Maria Helena Diniz', 'Curso de Direito civil brasileiro: Teoria geral das obrigações - Volume 2', '347(81)', '347', '85.02.02007.2', 'São Paulo', 'Saraiva', NULL, NULL, '1996', '10 ed.', 'v.2', 'ex.1', 'p. 431', 1, '15.00', 0, '003.', NULL, 1),
(1768, '2022-01-01', 'Maria Helena Diniz', 'Curso de Direito civil brasileiro: Teoria das Obrigações contratuais e extracontratuais - Volume 3', '347(81)', '347', '85.02.02020.x', 'São Paulo', 'Saraiva', NULL, NULL, '1996', '11 ed.', 'v.3', 'ex.1', 'p.610', 1, '15.00', 0, '003.', NULL, 1),
(1769, '2022-01-01', 'LOBATO, Monteiro ', 'Obras completas de Monteiro Lobato: América', NULL, 'B869.939', NULL, 'São Paulo', 'Editora Brasiliense', NULL, NULL, '1959', '9 ed.', 'v.9', 'ex.1', 'p.312', 1, '15.00', 0, '003.', NULL, 1),
(1770, '2022-01-01', 'Luiz Vicente Cornicchiaro', 'Dicionário de direito penal', '343.2(038)', '340.03', NULL, 'Brasília-DF', 'EUB', NULL, NULL, '1974', NULL, 'v.1', 'ex.1', 'p. 527', 1, '15.00', 0, '003.', NULL, 1),
(1771, '2022-01-01', 'Karl Marx e Friedrich Engels', 'A ideologia alemã ', NULL, '320.5', NULL, 'São Paulo', 'Editora Ciências Humanas Ltda', NULL, NULL, '1979', '2 ed. ', 'v.1', 'ex.1', 'p.138', 1, '15.00', 0, '003.', NULL, 1),
(1772, '2022-01-01', 'Celso Furtado', 'Imagem do Brasil - Volume 6 :  Um projeto para o Brasil', NULL, '300', NULL, 'Rio de Janeiro', 'Editor Saga', NULL, NULL, '1968', '2 ed. ', 'v.6', 'ex.1', 'p. 133', 1, '15.00', 0, '003.', NULL, 1),
(1773, '2022-01-01', 'TRAVASSOS, Nelson Palma ', 'Minhas memórias dos Monteiros Lobatos', NULL, 'B869.939', NULL, 'São Paulo', 'Edarte', NULL, NULL, '1964', '1 ed. ', NULL, 'ex.1', 'p. 253', 1, '15.00', 0, '003.', NULL, 1),
(1774, '2022-01-01', 'Luisa Riva Sanseverino', 'Curso de dieito do trabalho', '34.331/ 34.331(45)', '350', NULL, 'São Paulo', 'Universidade de São Paulo', NULL, NULL, '1976', '11 ed.', 'v.1', 'ex.1', 'p.433', 1, '15.00', 0, '003.', NULL, 1),
(1775, '2022-01-01', 'KANT, Emanuel ', 'Biblioteca de Autores Célebres: Crítica da Razão prática', NULL, '100', NULL, 'São Paulo', 'Editora Brasilie', NULL, NULL, NULL, '3 ed.', NULL, 'ex.1', 'p. 247', 1, '15.00', 0, '003.', NULL, 1),
(1776, '2022-01-01', 'LUDÍCIBUS, Sérgio de  (org.)', 'Manual de Contabilidade das sociedades por ações (aplicáveis também às demais sociedades)', NULL, '657.95', '85.224.1172.7', 'São Paulo', 'Atlas S.A.', NULL, NULL, '1994', '4 ed. ', NULL, 'ex.1', 'p. 778', 1, '15.00', 0, '003.', NULL, 1),
(1777, '2022-01-01', 'Caryl Chessman', 'A lei quer que eu morra', NULL, '340', NULL, 'São Paulo', '**', NULL, NULL, '1957', NULL, 'v.1', 'ex.1', ' p. 306', 1, '15.00', 0, '003.', NULL, 1),
(1778, '2022-01-01', 'Samuel Pfromm Neto', 'Biblioteca Pioneira de Ciências Sociais: Educação - Psicologia da adolescência', '159.9.0537', '155.5', NULL, 'Brasília-DF', 'Pioneira', NULL, NULL, '1973', '3 ed.', 'v.1', 'ex.1', 'p. 420', 1, '15.00', 0, '003.', NULL, 1),
(1779, '2022-01-01', 'Paulo de Castro', 'Subdesenvolvimento e Revolução', NULL, '300', NULL, 'Rio de Janeiro', 'Fundo de Cultura', NULL, NULL, '1962', '1 ed. ', 'v.1', 'ex.1', 'p. 239', 1, '15.00', 0, '003.', NULL, 1),
(1780, '2022-01-01', 'NICHOLLS, David ', 'Resposta certa', '821.111', '823', '978.85.8057.204.9', 'Rio de Janeiro', 'Intrínseca', NULL, NULL, '2012', '1 ed. ', NULL, 'ex.1', 'p. 352', 1, '15.00', 0, '003.', NULL, 1),
(1781, '2022-01-01', 'GROGAN, John', 'Marley e Eu: a vida e o amor ao lado do pior cão do mundo', NULL, '813', '85.991.70.84.8', 'São Paulo', 'Prestígio', NULL, NULL, '2006', '1 ed. ', NULL, 'ex.1', 'p.268', 1, '15.00', 0, '003.', NULL, 1),
(1782, '2022-01-01', 'JAMES, E.L.', 'Série Cinquenta tons de cinza - Volume 1', '821.111(73)3', '813', '978.85.8057.218.6', 'Rio de Janeiro', 'Intrínseca', NULL, NULL, '2012', '1 ed. ', NULL, 'ex.1', 'p.480', 1, '15.00', 0, '003.', NULL, 1),
(1783, '2022-01-01', 'ROSA, João Guimarães ', 'Primeiras estórias', '869.0(8)34', '869.9301', '85.209.0444.0', 'Rio de Janeiro', 'Nova Fronteira', NULL, NULL, NULL, '43 ed.', NULL, 'ex.1', 'p. 160', 1, '15.00', 0, '003.', NULL, 1),
(1784, '2022-01-01', 'TAHAN, Malba ', 'Céu de Alá', NULL, '869.93', NULL, 'Rio de Janeiro', 'Record', NULL, NULL, '1986', '14 ed.', NULL, 'ex.1', 'p.160', 1, '15.00', 0, '003.', NULL, 1),
(1785, '2022-01-01', 'BRANDÃO, Ignácio de Loyola', 'Coleção Melhores crônicas: Ignácio de Loyla Brandão', NULL, '869.93', '85.260.0920.6', 'São Paulo', 'Global Editora', NULL, NULL, '2004', '1 ed. ', NULL, 'ex.1', 'p.415', 1, '15.00', 0, '003.', NULL, 1),
(1786, '2022-01-01', 'FREITAS, Isabela', 'Não se iluda não', '159.947', '158.1', '978.85.8057.768.6', 'Rio de Janeiro', 'Intrínseca', NULL, NULL, '´2015', '1 ed. ', NULL, 'ex.1', 'p.272', 1, '15.00', 0, '003.', NULL, 1),
(1787, '2022-01-01', 'FREITAS, Isabela', 'Não se apega não', '159.947', '158.1', '978.85.8057.533.0', 'Rio de Janeiro', 'Intrínseca', NULL, NULL, '2014', '1 ed. ', NULL, 'ex.1', 'p.254', 1, '15.00', 0, '003.', NULL, 1),
(1788, '2022-01-01', 'WHITELEY, Richard C. ', 'A empresa voltada totalmente voltada para o cliente: do planejamento à ação', '658.562', '658.562', '85.7001.762.6', 'Rio de Janeiro', 'Campus', NULL, NULL, '1992', '6 ed. ', NULL, 'ex.1', 'p.263', 1, '15.00', 0, '003.', NULL, 1),
(1789, '2022-01-01', 'Darcy Azambuja', 'Introdução á ciência política', NULL, '320', '85.250.0257.7', 'Rio de Janeiro', 'Globo', NULL, NULL, '1987', '6 ed.', NULL, 'ex.1', 'p.315', 1, '15.00', 0, '003.', NULL, 1),
(1790, '2022-01-01', 'ASSIS, Machado de', 'Série Bom Livro: Dom Casmurro', NULL, 'B869', '85.08.04081.4', 'São Paulo', 'Ática Editora', NULL, NULL, '1998', '36 ed.', NULL, 'ex.1', 'p.184', 1, '15.00', 0, '003.', NULL, 1),
(1791, '2022-01-01', 'ASSIS, Machado de', 'Série Bom Livro: Memórias Póstumas de Brás Cubas', NULL, 'B869', '85.08.04082.2', 'São Paulo', 'Ática Editora', NULL, NULL, '2004', '28 ed.', NULL, 'ex.1', 'p.176', 1, '15.00', 0, '003.', NULL, 1),
(1792, '2022-01-01', 'ASSIS, Machado de', 'Série Bom Livro: Quicas Borba', NULL, 'B869', '85.08.04084.9', 'São Paulo', 'Ática Editora', NULL, NULL, '1998', '16 ed.', NULL, 'ex.1', 'p.214', 1, '15.00', 0, '003.', NULL, 1),
(1793, '2022-01-01', 'QUEIROZ, Eça de ', 'Série Bom Livro: O crime do padre amaro', NULL, 'B869', '85.08.04341.4', 'São Paulo', 'Ática Editora', NULL, NULL, '1999', '14 ed.', NULL, 'ex.1', 'p.358', 1, '15.00', 0, '003.', NULL, 1),
(1794, '2022-01-01', 'QUEIRÓZ, Eça de Queiró', 'Série Bom Livro:  A ilustre casa de Ramires', NULL, 'B869', '85.08.06411.x', 'São Paulo', 'Ática Editora', NULL, NULL, '1999', '6 ed.', NULL, 'ex.1', 'p.264', 1, '15.00', 0, '003.', NULL, 1),
(1795, '2022-01-01', 'ALENCAR, José de ', 'Série Bom Livro: Lucila', NULL, 'B869', '85.08.04080.6', 'São Paulo', 'Ática Editora', NULL, NULL, '2006', '27 ed.', NULL, 'ex.1', 'p.127', 1, '15.00', 0, '003.', NULL, 1),
(1796, '2022-01-01', 'ALENCAR, José de ', 'Série Bom Livro: Ubirajara', NULL, 'B869', '85.08.05249.9', 'São Paulo', 'Ática Editora', NULL, NULL, '1999', '16 ed.', NULL, 'ex.1', 'p. 94', 1, '15.00', 0, '003.', NULL, 1),
(1797, '2022-01-01', 'ALENCAR, José de ', 'Série Bom Livro: Senhora', NULL, 'B869', '85.08.04078.4', 'São Paulo', 'Ática Editora', NULL, NULL, '1998', '32 ed.', NULL, 'ex.1', 'p.215', 1, '15.00', 0, '003.', NULL, 1),
(1798, '2022-01-01', 'AZEVEDO, Aluízio de ', 'Série Bom Livro: O cortiço', NULL, 'B869', '85.08.04074.1', 'São Paulo', 'Ática Editora', NULL, NULL, '1997', '30 ed.', NULL, 'ex.1', 'p. 207', 1, '15.00', 0, '003.', NULL, 1),
(1799, '2022-01-01', 'ALMEIDA, Manuel Antônio de ', 'Série Bom Livro: Memória de um sargento de milícias', NULL, 'B869', '85.08.04611.1', 'São Paulo', 'Ática Editora', NULL, NULL, '1998', '30 ed.', NULL, 'ex.1', 'p.152', 1, '15.00', 0, '003.', NULL, 1),
(1800, '2022-01-01', 'ASSIS, Machado de', 'Crônicas Escolhida de Machado de Assis', NULL, 'B869.93', NULL, 'São Paulo', 'Ática S.A.', NULL, NULL, '1994', '1 ed.', NULL, 'ex.1', 'p. 182', 1, '15.00', 0, '003.', NULL, 1),
(1801, '2022-01-01', 'ANDRADE, Oswaldo de ', 'Coleção Vera Cruz (Literura Brasileira): Obras completas X: Telefonema, introdução e estabelecimento do texto de Vera Chalmers - Volume 147-I', '869.0(81)94', 'B869.93', NULL, 'Rio de Janeiro', 'Civilização Brasileira', NULL, NULL, '1976', '2 ed.', 'v.147', 'ex.1', 'p.172', 1, '15.00', 0, '003.', NULL, 1),
(1802, '2022-01-01', 'REGO, José Lins do ', 'Menino de Engenho', '869.(081)3', 'B869.93', '85.03.00341.4', 'Rio de Janeiro', 'José olympio', NULL, NULL, '1994', '60 ed.', NULL, 'ex.1', 'p.88', 1, '15.00', 0, '003.', NULL, 1),
(1803, '2022-01-01', 'AMADO, Jorge', 'Capitães da areia', '869.(081)31', 'B869.93', '85.01.00530.4', 'Rio de Janeiro', 'Record', NULL, NULL, '2006', '120 ed.', NULL, 'ex.1', 'p.272', 1, '15.00', 0, '003.', NULL, 1),
(1804, '2022-01-01', 'CALVINO, Italo ', 'O Cavaleiro inexistente', NULL, '853.91', '85.7164.303.2', 'São Paulo', 'Companhia das Letras', NULL, NULL, '1993', '2 ed.', NULL, 'ex.1', 'p.133', 1, '15.00', 0, '003.', NULL, 1),
(1805, '2022-01-01', 'BACH, George R. ', 'O inimigo intimo: como brigar com lealdade no amor e no casamento', NULL, '158.2', '978.85.3230.163.5', 'São Paulo', 'Summus Editorial', NULL, NULL, '1991', '1 ed.', NULL, 'ex.1', 'p.359', 1, '15.00', 0, '003.', NULL, 1),
(1806, '2022-01-01', 'SARAMAGO, José ', 'Ensaio sobre a cegueira - Romance', NULL, 'B869.93', '85.7164.495.0', 'São Paulo', 'Companhia das Letras', NULL, NULL, '2004', '1 ed.', NULL, 'ex.1', 'p.310', 1, '15.00', 0, '003.', NULL, 1),
(1807, '2022-01-01', 'VERÍSSIMO, Erico ', 'Um certo capitão Rodrigo', NULL, 'B869.93', '85.359.0598.7', 'São Paulo', 'Companhia das Letras', NULL, NULL, '2005', '3 ed.', NULL, 'ex.1', 'p.184', 1, '15.00', 0, '003.', NULL, 1),
(1808, '2022-01-01', 'ROWLEY, Hazel', 'Simone de Beauvoir e Jean-Paul Sartre: Tête-à Tête', NULL, '922', '85.7302.782.7', 'Rio de Janeiro', 'Objetiva', NULL, NULL, '2006', '1 ed.', NULL, 'ex.1', 'p.462', 1, '15.00', 0, '003.', NULL, 1);
INSERT INTO `librarycollection` (`id`, `registrationDate`, `author`, `title`, `cdu`, `cdd`, `isbn`, `local`, `publisher_edition`, `number`, `month`, `year`, `edition`, `volume`, `copyNumber`, `pageNumber`, `typeAcquisitionId`, `price`, `prohibitedSale`, `provider`, `exclusionInfoTerm`, `registeredByUserId`) VALUES
(1809, '2022-01-01', 'COLI, Jorge ', 'Coleção Primeiro Passos: O que é arte - Volume 46', ' ', '700.1', '85.11.01046.7', 'São Paulo', 'Brasiliense', NULL, NULL, '2006', '15 ed.', 'v.46', 'ex.1', 'p.135', 1, '15.00', 0, '003.', NULL, 1),
(1810, '2022-01-01', 'FRANCO, Silvia Cintra ', 'Série Vaga-Lume: Aventura no Império do Sol', NULL, '028.5', '85.08.03225.0', 'São Paulo', 'Ática S.A.', NULL, NULL, '1993', '5 ed.', NULL, 'ex.1', 'p.112', 1, '16.00', 0, '004.', NULL, 1),
(1811, '2022-01-01', 'PUNTEL, Luiz', 'Série Vaga-Lume: Meninos sem pátria', NULL, '028.5', '85.08.02769.9', 'São Paulo', 'Ática S.A.', NULL, NULL, '1992', '13 ed.', NULL, 'ex.1', 'p.127', 1, '16.00', 0, '004.', NULL, 1),
(1812, '2022-01-01', 'LIMA, Aristides Fraga ', 'Série Vaga-Lume: Perigo no mar', NULL, '028.5', '85.08.00670.5', 'São Paulo', 'Ática S.A.', NULL, NULL, '1991', '5 ed.', NULL, 'ex.1', 'p.111', 1, '16.00', 0, '004.', NULL, 1),
(1813, '2022-01-01', 'Bardari', 'Série Vaga-Lume: A maldição do tesouro do faraó', NULL, '028.5', '85.08.03766.x', 'São Paulo', 'Ática S.A.', NULL, NULL, '1991', '5 ed.', NULL, 'ex.1', 'p.109', 1, '16.00', 0, '004.', NULL, 1),
(1814, '2022-01-01', 'REY, Marcos ', 'Série Vaga-Lume: Um cadáver ouve rádio', NULL, '028.5', '85.08.00115.0', 'São Paulo', 'Ática S.A.', NULL, NULL, '2006', '14 ed.', NULL, 'ex.1', 'p.126', 1, '16.00', 0, '004.', NULL, 1),
(1815, '2022-01-01', 'ACEDO, Rosane / ARANHA, Célia ', 'Coleção encontro com a arte brasileira: Encontro com Tarcila', NULL, '750', '85.86390.04.6', 'São Paulo', 'Minden', NULL, NULL, NULL, '1 ed.', NULL, 'ex.1', 'p.40', 1, '15.00', 0, '003.', NULL, 1),
(1816, '2022-01-01', 'Fiona Bradley', 'Movimento da Arte Moderna: Surrealismo', NULL, '709.409.2', '85.86374.31.8', 'São Paulo', 'Cosac & Naify Edições', NULL, NULL, '1999', '1 ed.', 'v.1', 'ex.1', 'p.80', 1, '15.00', 0, '003.', NULL, 1),
(1817, '2022-01-01', 'TUFANO, Douglas ', 'Estudos de literatura brasileira', NULL, '869', NULL, 'São Paulo', 'Moderna', NULL, NULL, '1975', NULL, NULL, 'ex.1', 'p.240', 1, '15.00', 0, '003.', NULL, 1),
(1818, '2022-01-01', 'Heinrich Wölfflin', 'Conceitos fundamentais da arte: o problema da evolução dos estilos na arte mais recente', ' ', '709', '85.336.0505.6', 'São Paulo', 'Martins Fontes', NULL, NULL, '1996', '3 ed.', 'v.1', 'ex.1', 'p.348', 1, '15.00', 0, '003.', NULL, 1),
(1819, '2022-01-01', 'Leo Huberman', 'História da Riqueza do Homem', ' ', '338.17', '85.216.1063.7', 'Rio de Janeiro', 'LTC', NULL, NULL, '2003', '21 ed.', 'v.1', 'ex.1', 'p. 313', 1, '15.00', 0, '003.', NULL, 1),
(1820, '2022-01-01', 'Milton Braga Furtado', 'Síntese da economia brasileira', '338(81)', '330981', '85.216.0589.7', 'Rio de Janeiro', 'LTC', NULL, NULL, '1988', '5 ed.', 'v.1', 'ex.1', 'p.254', 1, '15.00', 0, '003.', NULL, 1),
(1821, '2022-01-01', 'Alexandre Assaf Neto', 'Mercado financeiro', NULL, '332.6', '85.224.3391.7', 'São Paulo', 'Atlas S.A.', NULL, NULL, '2003', '5 ed.', 'v.1', 'ex.1', 'p. 400', 1, '15.00', 0, '003.', NULL, 1),
(1822, '2022-01-01', 'SIRKIS, Alfredo', 'Os carbonários', '981\"1967\"', '981.063', '85.01.05315.5', 'Rio de Janeiro', 'Record', NULL, NULL, '1998', '14 ed.', NULL, 'ex.1', 'p.416', 1, '15.00', 0, '003.', NULL, 1),
(1823, '2022-01-01', 'ZIEGESAR, Cecily Von ', 'Gossip girl : Eu quero tudo - Volume 3', '821.111(73)3', '813', '85.01.07241.9', 'Rio de Janeiro', 'Record', NULL, NULL, '2006', '2 ed.', 'v.3', 'ex.1', 'p.287', 1, '15.00', 0, '003.', NULL, 1),
(1824, '2022-01-01', 'Annabel Griffiths', 'Bittergirl - Esqueça o ex e dê a volta por cima', '392.6', '306872', '978.85.7684.106.7', 'Rio de Janeiro', 'BestSeller', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.317', 1, '15.00', 0, '003.', NULL, 1),
(1825, '2022-01-01', 'OLIVEIRA, Edson Aparecida Araújo Querido ', 'Boas práticas em getão contemporânea: uma coletânia de estudos regionais', NULL, '658.4012', '978.85.66128.91.8', 'Taubaté', 'Universidade', NULL, NULL, '2017', '1 ed.', NULL, 'ex.1', 'p.408', 1, '15.00', 0, '003.', NULL, 1),
(1826, '2022-01-01', 'Quésia postigo Kamimura (org)', 'Saúde em diferentes contextos: educação, cuidado, economia e gestão', NULL, '338.9', '978.85.9561.125.2', 'Taubaté', 'Universidade', NULL, NULL, '2019', '1 ed.', 'v.1', 'ex.1', 'p.428', 1, '15.00', 0, '003.', NULL, 1),
(1827, '2022-01-01', 'OLIVEIRA, Edson Aparecida Araújo Querido ', 'Administração pública: Estudos de Gestão e Desenvolvimento Regional', NULL, '658.4', '978.85.66128.81.9', 'Taubaté', 'Universidade', NULL, NULL, '2016', '1 ed.', NULL, 'ex.1', 'p.390', 1, '15.00', 0, '003.', NULL, 1),
(1828, '2022-01-01', 'LIMA, Patrícia(Org.)', 'O vazio não está nem quando é silêncio - vozes femininas na literatura', NULL, 'B869', '978.65.86638.04.2', 'Bauru-SP', 'Mireveja Editora', NULL, NULL, '2020', '1 ed.', NULL, 'ex.1', 'p.144', 1, '15.00', 0, '003.', NULL, 1),
(1829, '2022-01-01', 'José Álvaro Moisés', 'Crise da Democracia representativa e neopopulismo no Brasil', NULL, '320.5', '978.85.990084.8.1', 'Rio de Janeiro', 'Intituto Konrad-Adenauer Stiftung', NULL, NULL, '2020', '1 ed.', NULL, 'ex.1', 'p.136', 1, '15.00', 0, '003.', NULL, 1),
(1830, '2022-01-01', 'Folha de São Paulo', 'Primeira Página - Folha de São Paulo', NULL, '981', '85.7402.699.9', 'São Paulo', 'Publifolha', NULL, NULL, '2006', '6 ed.', NULL, 'ex.1', 'p.240', 1, '15.00', 0, '003.', NULL, 1),
(1831, '2022-01-01', 'ZANINI, Ivo Zanini', 'Três \"B\" da Pintura Brasileira: Bonadei, Blank e Bussab', NULL, '750', NULL, 'São Paulo', 'Richard Bussab', NULL, NULL, '1997', '1 ed.', NULL, 'ex.1', 'p.99', 1, '15.00', 0, '003.', NULL, 1),
(1832, '2022-01-01', 'ZANINI, Ivo Zanini', 'Três \"B\" da Pintura Brasileira: Bonadei, Blank e Bussab', NULL, '750', NULL, 'São Paulo', 'Richard Bussab', NULL, NULL, '1997', '1 ed.', NULL, 'ex.2', 'p.99', 1, '15.00', 0, '003.', NULL, 1),
(1833, '2022-01-01', 'SILVA, Elias', 'Barueri: História Revista e Documentada - 1960 -1994', NULL, '921', NULL, 'Barueri-SP', 'Agencia Barueri de Comunicação', NULL, NULL, '1995', '1 ed.', NULL, 'ex.1', 'p.121', 1, '15.00', 0, '003.', NULL, 1),
(1834, '2022-01-01', 'DERANI, Alvaro', 'Universo Abrangente', NULL, '869915', NULL, 'São Paulo', 'Companhia Melhoramentos', NULL, NULL, '1987', '1 ed.', NULL, 'ex.1', 'p.86', 1, '15.00', 0, '003.', NULL, 1),
(1835, '2022-01-01', ' AERONÁUTICA, Instituto Tecnológico de ', '50 Anos do Instituto Tecnológic de Aeronáutica - 1950 - 2000', NULL, '921', NULL, 'São Paulo', 'Marketing Promocional LTDA', NULL, NULL, '2000', '1 ed.', NULL, 'ex.1', 'p.118', 1, '15.00', 0, '003.', NULL, 1),
(1836, '2022-01-01', 'Guinness World ', 'Guinness World records 2011 - Livro dos Recordes', NULL, '036.9', '978.85.0002.649.2', 'Rio de Janeiro', 'Ediouro', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p.287', 1, '15.00', 0, '003.', NULL, 1),
(1837, '2022-01-01', 'Guinness World ', 'Novo Guinness Book 1995 - O Livro dos Recordes', NULL, '036.9', NULL, 'Rio de Janeiro', 'Guiness Publishing', NULL, NULL, '1994', '1 ed.', NULL, 'ex.1', 'p.353', 1, '15.00', 0, '003.', NULL, 1),
(1838, '2022-01-01', 'PIRACICABA, Prefeitura Municipal de', 'Piracibaba: Passado e Presente', NULL, '921', NULL, 'Piracicaba-SP', 'Câmara', NULL, NULL, '1988', '1 ed.', NULL, 'ex.1', 'p.208', 1, '15.00', 0, '003.', NULL, 1),
(1839, '2022-01-01', 'NABUCO, Joaquim ', 'Grander Nomes do Pensamento Brasileito: Joaquim Nabuco - O abolicionismo', '326.1(81)', '928', '85.7402.190.3', 'São Paulo', 'Publifolha', NULL, NULL, '2000', '1 ed.', NULL, 'ex.1', 'p.183', 1, '15.00', 0, '003.', NULL, 1),
(1840, '2022-01-01', 'GONZALES, Karim Gizelle ', 'Nova Série Informática: Integração Microsoft Office 2003 Professional', NULL, '44', '85.7359.366.0', 'São Paulo', 'Senac', NULL, NULL, '2004', '1 ed.', NULL, 'ex.1', 'p.168', 1, '15.00', 0, '003.', NULL, 1),
(1841, '2022-01-01', 'HARADA, Susumo', 'Ulrico Shmidl', NULL, '869.64', '978.85.915076.2.7', 'Itapevi-SP', 'Marcos Torquato Ramalho', NULL, NULL, '2015', '1 ed.', NULL, 'ex.1', 'p.68', 1, '15.00', 0, '003.', NULL, 1),
(1842, '2022-01-01', 'Iolanda Keiko Ota', 'A vida do Ives Ota: o mensageiro da paz', NULL, '364154092', '85.900868.1.x', 'São Paulo', 'Movimento da paz e justiça Ives Ota', NULL, NULL, '1999', '1 ed.', 'v.1', 'ex.1', 'p.83', 1, '15.00', 0, '003.', NULL, 1),
(1843, '2022-01-01', 'BATISTA JÚNIOR, João ', ' A beleza da vida', NULL, '923.8', '978.85.69522.40.9', 'São Paulo', 'Abril', NULL, NULL, '2017', '1 ed.', NULL, 'ex.1', 'p.240', 1, '15.00', 0, '003.', NULL, 1),
(1844, '2022-01-01', 'MORAES, Vinícius de ', 'Coleção Folha Grandes Escritores Brasileiros: Para viver um grande amor', NULL, 'B869.8', '978.85.99896.34.1', 'Rio de Janeiro', 'Mediafashion', NULL, NULL, '2008', '1 ed.', NULL, 'ex.1', 'p.208', 1, '15.00', 0, '003.', NULL, 1),
(1845, '2022-01-01', 'MORGAN, Thomas', 'Coleção Astral: Os Números do amor', NULL, '810', '85.00.10411.2', 'Rio de Janeiro', 'Ediouro', NULL, NULL, '1989', '1 ed.', NULL, 'ex.1', 'p.120', 1, '15.00', 0, '003.', NULL, 1),
(1846, '2022-01-01', 'GOMES, Dias ', 'O pagador de promessas', '869.0(81)2', '869.92', '978.85.286.0317.0', 'Rio de Janeiro', 'Bertrand Brasil', NULL, NULL, '2010', '5 ed.', NULL, 'ex.1', 'p.173', 1, '15.00', 0, '003.', NULL, 1),
(1847, '2022-01-01', 'STALLYBRASS, Peter ', 'O casaco de Marx: roupas, memórias , dor', '391159923', '101', '85.86583.34.0', 'Belo Horizonte-MG', 'Autêntica', NULL, NULL, '2000', '2 ed.', NULL, 'ex.1', 'p.128', 1, '15.00', 0, '003.', NULL, 1),
(1848, '2022-01-01', 'KLINTOWITZ, Jacob ', 'Arte e Comunicação', NULL, '070', NULL, 'São Paulo', 'Editora Shalom LTDA', NULL, NULL, '1979', '2 ed.', NULL, 'ex.1', 'p.94', 1, '15.00', 0, '003.', NULL, 1),
(1849, '2022-01-01', 'QUERONÉIA, Plutarco de ', 'Os mistérios de Ísis e Osíris', NULL, '101', NULL, 'São Paulo', 'Nova Acrópole do Brasil', NULL, NULL, '1981', '1 ed.', NULL, 'ex.1', 'p.129', 1, '15.00', 0, '003.', NULL, 1),
(1850, '2022-01-01', 'JORGE, Fernando ', 'Água da Fonte: Crônicas e prosas variadas', NULL, 'B869.93', NULL, 'São Paulo', 'Editora Martins', NULL, NULL, '1959', '1 ed.', NULL, 'ex.1', 'p.181', 1, '15.00', 0, '003.', NULL, 1),
(1851, '2022-01-01', 'THAUMATURGO, Ivna ', 'A família de guizos: histórias e memórias', '92', '920', '85.200.0441.5', 'Rio de Janeiro', 'Civilização Brasileira', NULL, NULL, '1997', '1 ed.', NULL, 'ex.1', 'p.208', 1, '15.00', 0, '003.', NULL, 1),
(1852, '2022-01-01', 'MONJARDIM, Adelpho Poli', 'O grande almirante - Joaquim Marques Lisboa, Marquês de Tamandaré, Patrono da Marinha do Brasil', NULL, '921', NULL, 'Rio de Janeiro', 'Imprensa Naval', NULL, NULL, '1979', '1 ed.', NULL, 'ex.1', 'p.315', 1, '15.00', 0, '003.', NULL, 1),
(1853, '2022-01-01', 'MELO, Edilberto de Oliveira - Coronel', 'Polícia Militar - O salto na Amazônia e outras narrativas', NULL, '921', NULL, 'São Paulo', 'Impressa oficial', NULL, NULL, '1979', '1 ed.', NULL, 'ex.1', 'p.210', 1, '15.00', 0, '003.', NULL, 1),
(1854, '2022-01-01', 'COSTA, João Frank da ', 'Evolução Cultura da América Pré-colombiana', NULL, '980', NULL, 'Brasilia - DF', 'MEC', NULL, NULL, '1978', '1 ed.', NULL, 'ex.1', 'p.230', 1, '15.00', 0, '003.', NULL, 1),
(1855, '2022-01-01', 'João Figueiredo (Presidente do Brasil)', 'Discursos Presidente João Figueiredo - 1979 - Volume 1', NULL, '354.810.35', NULL, 'Brasilia - DF', 'Secretaria de Imprensa Federal', NULL, NULL, '1981', '1 ed.', 'v.1', 'ex.1', 'p.282', 1, '15.00', 0, '003.', NULL, 1),
(1856, '2022-01-01', 'João Figueiredo (Presidente do Brasil)', 'Discursos Presidente João Figueiredo - 1980 - Volume 2', NULL, '354.810.35', NULL, 'Brasilia - DF', 'Secretaria de Imprensa Federal', NULL, NULL, '1981', '1 ed.', 'v.2', 'ex.1', 'p.396', 1, '15.00', 0, '003.', NULL, 1),
(1857, '2022-01-01', 'João Figueiredo (Presidente do Brasil)', 'Discursos Presidente João Figueiredo - 1981 - Volume 3', NULL, '354.810.35', NULL, 'Brasilia - DF', 'Secretaria de Imprensa Federal', NULL, NULL, '1981', '1 ed.', 'v.3', 'ex.1', 'p.352', 1, '15.00', 0, '003.', NULL, 1),
(1858, '2022-01-01', 'CIPRO NETO, Pasquele ', 'Coleção Portugues Passo a Passo: Pode ser mais fácil do que você pensa - Volume 1', NULL, '469.507', '978.85.7768.109.9', 'Barueri-SP', 'Gold Editora', NULL, NULL, '2009', '1 ed.', 'v.1', 'ex.1', 'p.47', 1, '15.00', 0, '003.', NULL, 1),
(1859, '2022-01-01', 'CIPRO NETO, Pasquele ', 'Coleção Portugues Passo a Passo: Como grafar e acentuar as palavras - Volume 2', NULL, '469.507', '978.85.7768.109.9', 'Barueri-SP', 'Gold Editora', NULL, NULL, '2009', '1 ed.', 'v.2', 'ex.1', 'p.47', 1, '15.00', 0, '003.', NULL, 1),
(1860, '2022-01-01', 'CIPRO NETO, Pasquele ', 'Coleção Portugues Passo a Passo: Como Usara vírgula e outros sinais de pontuação - Volume 3', NULL, '469.507', '978.85.7768.109.9', 'Barueri-SP', 'Gold Editora', NULL, NULL, '2009', '1 ed.', 'v.3', 'ex.1', 'p.47', 1, '15.00', 0, '003.', NULL, 1),
(1861, '2022-01-01', 'CIPRO NETO, Pasquele ', 'Coleção Portugues Passo a Passo: Como conjugar e empregar os tempos e os modos dos verbos - Volume 4', NULL, '469.507', '978.85.7768.109.9', 'Barueri-SP', 'Gold Editora', NULL, NULL, '2009', '1 ed.', 'v.4', 'ex.1', 'p.47', 1, '15.00', 0, '003.', NULL, 1),
(1862, '2022-01-01', 'CIPRO NETO, Pasquele ', 'Coleção Portugues Passo a Passo: Conheça os principais casos de concordância verbal - Volume 5', NULL, '469.507', '978.85.7768.109.9', 'Barueri-SP', 'Gold Editora', NULL, NULL, '2009', '1 ed.', 'v.5', 'ex.1', 'p.47', 1, '15.00', 0, '003.', NULL, 1),
(1863, '2022-01-01', 'CIPRO NETO, Pasquele ', 'Coleção Portugues Passo a Passo: Conheça os principais casos de concordância nominal - Volume 6', NULL, '469.507', '978.85.7768.109.9', 'Barueri-SP', 'Gold Editora', NULL, NULL, '2009', '1 ed.', 'v.6', 'ex.1', 'p.47', 1, '15.00', 0, '003.', NULL, 1),
(1864, '2022-01-01', 'CIPRO NETO, Pasquele ', 'Coleção Portugues Passo a Passo:       Veja como as palavras e relacionam   -    Volume 7', NULL, '469.507', '978.85.7768.109.9', 'Barueri-SP', 'Gold Editora', NULL, NULL, '2009', '1 ed.', 'v.7', 'ex.1', 'p.47', 1, '15.00', 0, '003.', NULL, 1),
(1865, '2022-01-01', 'CIPRO NETO, Pasquele ', 'Coleção Portugues Passo a Passo: Por dentro dos ensinamentos sobre crase - Volume 8', NULL, '469.507', '978.85.7768.109.9', 'Barueri-SP', 'Gold Editora', NULL, NULL, '2009', '1 ed.', 'v.8', 'ex.1', 'p.47', 1, '15.00', 0, '003.', NULL, 1),
(1866, '2022-01-01', 'CIPRO NETO, Pasquele ', 'Coleção Portugues Passo a Passo: Como empregar os pronomes - Volume 9', NULL, '469.507', '978.85.7768.109.9', 'Barueri-SP', 'Gold Editora', NULL, NULL, '2009', '1 ed.', 'v.9', 'ex.1', 'p.47', 1, '15.00', 0, '003.', NULL, 1),
(1867, '2022-01-01', 'CIPRO NETO, Pasquele ', 'Coleção Portugues Passo a Passo: Coesão e coerência na eleboração de textos - Volume 10', NULL, '469.507', '978.85.7768.109.9', 'Barueri-SP', 'Gold Editora', NULL, NULL, '2009', '1 ed.', 'v.10', 'ex.1', 'p.47', 1, '15.00', 0, '003.', NULL, 1),
(1868, '2022-01-01', 'Oracy Nogueira', 'Família e Comunidade', NULL, '301', NULL, 'Belo Horizonte-MG', 'Interlivros', NULL, NULL, '1962', '1 ed.', 'v.1', 'ex.1', 'p. 541', 1, '15.00', 0, '003.', NULL, 1),
(1869, '2022-01-01', 'George B. de Huszar', 'Aplicaçjões práticas da democracia', NULL, '321.8', NULL, 'São Paulo', 'LEP', NULL, NULL, '1942', '1 ed.', 'v.1', 'ex.1', 'p.171', 1, '15.00', 0, '003.', NULL, 1),
(1870, '2022-01-01', 'CHRISTIE, Agatha ', 'Poirot Investiga', NULL, '823', NULL, 'Rio de Janeiro', 'Record', NULL, NULL, NULL, '5 ed.', NULL, 'ex.1', 'p.181', 1, '15.00', 0, '003.', NULL, 1),
(1871, '2022-01-01', 'Ministério da Cultura e Petrobrás', 'Boletim Ponto a Ponto - Ano II - Nº10 ', NULL, '090', NULL, 'Brasília-DF', 'Ministérios', NULL, NULL, 'Ano 2 - 2012', NULL, 'v.10', 'ex.1', NULL, 1, '15.00', 0, '003.', NULL, 1),
(1872, '2022-01-01', 'Ministério da Cultura e Petrobrás', 'Boletim Ponto a Ponto - Ano II - Nº10 ', NULL, '090', NULL, 'Brasília-DF', 'Ministérios', NULL, NULL, 'Ano 2 - 2012', NULL, 'v.10', 'ex.2', NULL, 1, '15.00', 0, '003.', NULL, 1),
(1873, '2022-01-01', 'Ministério da Cultura e Petrobrás', 'Boletim Ponto a Ponto - Ano II - Nº10 ', NULL, '090', NULL, 'Brasília-DF', 'Ministérios', NULL, NULL, 'Ano 2 - 2012', NULL, 'v.10', 'ex.3', NULL, 1, '15.00', 0, '003.', NULL, 1),
(1874, '2022-01-01', 'Stephenie Meyer', 'Eclipse (Edição em Braile) - Volulme 2/14', NULL, '090', '978.85.98078.41.0', '**', '**', NULL, NULL, '2009', '1 ed.', 'v.2', 'ex.1', NULL, 1, '15.00', 0, '003.', NULL, 1),
(1875, '2022-01-01', 'Senado Federal', 'Lei Orgânica de Itapevi - SP em Braille', NULL, '090', NULL, 'Brasilia - DF', 'Senado Federal ', NULL, NULL, '2016', NULL, NULL, 'ex.1', NULL, 1, '15.00', 0, '003.', NULL, 1),
(1876, '2022-01-01', 'Senado Federal', 'Lei Orgânica de Itapevi - SP em Braille', NULL, '090', NULL, 'Brasilia - DF', 'Senado Federal ', NULL, NULL, '2016', NULL, NULL, 'ex.2', NULL, 1, '15.00', 0, '003.', NULL, 1),
(1877, '2022-01-01', 'Senado Federal', 'Lei Orgânica de Itapevi - SP em Braille', NULL, '090', NULL, 'Brasilia - DF', 'Senado Federal ', NULL, NULL, '2016', NULL, NULL, 'ex.3', NULL, 1, '15.00', 0, '003.', NULL, 1),
(1878, '2022-01-01', 'Senado Federal', 'Lei Orgânica de Itapevi - SP em Braille', NULL, '090', NULL, 'Brasilia - DF', 'Senado Federal ', NULL, NULL, '2016', NULL, NULL, 'ex.2', NULL, 1, '15.00', 0, '003.', NULL, 1),
(1879, '2022-01-01', 'Senado Federal', 'Lei Orgânica de Itapevi - SP em Braille', NULL, '090', NULL, 'Brasilia - DF', 'Senado Federal ', NULL, NULL, '2016', NULL, NULL, 'ex.3', NULL, 1, '15.00', 0, '003.', NULL, 1),
(1880, '2022-01-01', 'Oficina Municipal - Fudação Konrad Adenauer', 'Escolas de Governo: formação e capacitação de agentes públicos', NULL, '658.001', '978.65.89434.01.6', 'São Paulo', 'Fundação Konrad Adenauer', NULL, NULL, '2020', '1 ed.', NULL, 'ex.1', 'p.358', 1, '15.00', 0, '003.', NULL, 1),
(1881, '2022-01-01', 'Sergio Margulis - Fundação Konrad Adenauer', 'Mudanças do clima: tudo que você queria e não queria saber', NULL, '333.72', '978.65.990084.9.8', 'Rio de Janeiro', 'Fundação Konrad Adenauer', NULL, NULL, '2020', '1 ed.', 'v.1', 'ex.1', 'p.180', 1, '15.00', 0, '003.', NULL, 1),
(1882, '2022-01-01', 'SÃO PAULO, Câmara municipal de', 'Câmara Municipal de São Paulo: 450 anos de História ', '342.532(815.6SP)\"1560-2010\"', '981.61', NULL, 'São Paulo', 'Câmara', NULL, NULL, '2012', '2 ed.', NULL, 'ex.1', 'p.148', 1, '15.00', 0, '003.', NULL, 1),
(1883, '2022-01-01', 'LACERDA, Ana Beatriz de Castro - Câmara dos Deputados - Congresso Nacional', 'Série Câmara em Imagens: A voz do cidadão na constituinte - nº 1', '342.4(81)', '981.61', '978.85.402.0707.3', 'Brasilia - DF', 'Câmada dos Deputados', 'n.1', NULL, '2018', '1 ed.', NULL, 'ex.1', 'p.155', 1, '15.00', 0, '003.', NULL, 1),
(1884, '2022-01-01', 'FURLAN, Rubens', 'Rubens Furlan: 30 anos de vida política', NULL, '921', NULL, 'São Paulo', 'Prol Editora', NULL, NULL, '2006', '1 ed.', NULL, 'ex.1', 'p.128', 1, '15.00', 0, '003.', NULL, 1),
(1885, '2022-01-01', 'Senado Federal', 'Relatório de Gestão - Ouvidoria do Senado Federal', NULL, '036.9', NULL, 'Brasilia - DF', 'Senado Federal ', NULL, NULL, '2016', '1 ed.', 'v.1', 'ex.1', 'p.76', 1, '15.00', 0, '003.', NULL, 1),
(1886, '2022-01-01', 'Barsa', 'Enciclopédia Barsa           Volume 2', NULL, '008', NULL, 'Rio de Janeiro', 'Encyclopaedia Britannica', NULL, NULL, '1965', '2 ed.', 'v.2', 'ex.1', 'p.444', 1, '10.90', 0, '003.', NULL, 1),
(1887, '2022-01-01', 'Barsa', 'Enciclopédia Barsa           Volume 3', NULL, '008', NULL, 'Rio de Janeiro', 'Encyclopaedia Britannica', NULL, NULL, '1965', '2 ed.', 'v.3', 'ex.1', 'p.476', 1, '10.90', 0, '003.', NULL, 1),
(1888, '2022-01-01', 'Barsa', 'Enciclopédia Barsa           Volume 4', NULL, '008', NULL, 'Rio de Janeiro', 'Encyclopaedia Britannica', NULL, NULL, '1965', '2 ed.', 'v.4', 'ex.1', 'p.476', 1, '10.90', 0, '003.', NULL, 1),
(1889, '2022-01-01', 'Barsa', 'Enciclopédia Barsa           Volume 5', NULL, '008', NULL, 'Rio de Janeiro', 'Encyclopaedia Britannica', NULL, NULL, '1965', '2 ed.', 'v.5', 'ex.1', 'p.476', 1, '10.90', 0, '003.', NULL, 1),
(1890, '2022-01-01', 'Barsa', 'Enciclopédia Barsa           Volume 6', NULL, '008', NULL, 'Rio de Janeiro', 'Encyclopaedia Britannica', NULL, NULL, '1965', '2 ed.', 'v.6', 'ex.1', 'p.484', 1, '10.90', 0, '003.', NULL, 1),
(1891, '2022-01-01', 'Barsa', 'Enciclopédia Barsa           Volume 7', NULL, '008', NULL, 'Rio de Janeiro', 'Encyclopaedia Britannica', NULL, NULL, '1965', '2 ed.', 'v.7', 'ex.1', 'p.492', 1, '10.90', 0, '003.', NULL, 1),
(1892, '2022-01-01', 'Barsa', 'Enciclopédia Barsa           Volume 8', NULL, '008', NULL, 'Rio de Janeiro', 'Encyclopaedia Britannica', NULL, NULL, '1965', '2 ed.', 'v.8', 'ex.1', 'p.476', 1, '10.90', 0, '003.', NULL, 1),
(1893, '2022-01-01', 'Barsa', 'Enciclopédia Barsa           Volume 9', NULL, '008', NULL, 'Rio de Janeiro', 'Encyclopaedia Britannica', NULL, NULL, '1965', '2 ed.', 'v.9', 'ex.1', 'p.476', 1, '10.90', 0, '003.', NULL, 1),
(1894, '2022-01-01', 'Barsa', 'Enciclopédia Barsa           Volume 10', NULL, '008', NULL, 'Rio de Janeiro', 'Encyclopaedia Britannica', NULL, NULL, '1965', '2 ed.', 'v.10', 'ex.1', 'p.468', 1, '10.90', 0, '003.', NULL, 1),
(1895, '2022-01-01', 'Barsa', 'Enciclopédia Barsa           Volume 11', NULL, '008', NULL, 'Rio de Janeiro', 'Encyclopaedia Britannica', NULL, NULL, '1965', '2 ed.', 'v.11', 'ex.1', 'p.460', 1, '10.90', 0, '003.', NULL, 1),
(1896, '2022-01-01', 'Barsa', 'Enciclopédia Barsa           Volume 12', NULL, '008', NULL, 'Rio de Janeiro', 'Encyclopaedia Britannica', NULL, NULL, '1965', '2 ed.', 'v.12', 'ex.1', 'p.484', 1, '10.90', 0, '003.', NULL, 1),
(1897, '2022-01-01', 'Barsa', 'Enciclopédia Barsa           Volume 13', NULL, '008', NULL, 'Rio de Janeiro', 'Encyclopaedia Britannica', NULL, NULL, '1965', '2 ed.', 'v.13', 'ex.1', 'p.460', 1, '10.90', 0, '003.', NULL, 1),
(1898, '2022-01-01', 'Barsa', 'Enciclopédia Barsa           Volume 14', NULL, '008', NULL, 'Rio de Janeiro', 'Encyclopaedia Britannica', NULL, NULL, '1965', '2 ed.', 'v.14', 'ex.1', 'p.120', 1, '10.90', 0, '003.', NULL, 1),
(1899, '2022-01-01', 'Barsa', 'Enciclopédia Barsa           Volume 15', NULL, '008', NULL, 'Rio de Janeiro', 'Encyclopaedia Britannica', NULL, NULL, '1965', '2 ed.', 'v.15', 'ex.1', 'p.506', 1, '10.90', 0, '003.', NULL, 1),
(1900, '2022-01-01', 'Barsa', 'Enciclopédia Barsa           Volume 16', NULL, '008', NULL, 'Rio de Janeiro', 'Encyclopaedia Britannica', NULL, NULL, '1965', '2 ed.', 'v.16', 'ex.1', 'p.399', 1, '10.90', 0, '003.', NULL, 1),
(1901, '2022-01-01', 'Barsa', 'Dicionário Barsa - Inglês/ Português - Volume 1', NULL, '008', NULL, 'Rio de Janeiro', 'Encyclopaedia Britannica', NULL, NULL, '1964', '1 ed.', 'v.1', 'ex.1', 'p.636', 1, '10.90', 0, '003.', NULL, 1),
(1902, '2022-01-01', 'Barsa', 'Dicionário Barsa -  Português/ Inglês - Volume 2', NULL, '008', NULL, 'Rio de Janeiro', 'Encyclopaedia Britannica', NULL, NULL, '1964', '1 ed.', 'v.2', 'ex.1', 'p.665', 1, '10.90', 0, '003.', NULL, 1),
(1903, '2022-01-01', 'BARBOSA, Paulo Roberto ', 'Segurança do Trabalho para Concurso Público', NULL, '613.6', '978.85.7984.285.6', 'Rio de Janeiro', 'Livre Expressão', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p.240', 1, '20.00', 0, '003.', NULL, 1),
(1904, '2022-01-01', 'MONTANARO, Juarez Oscar ', 'Medicina Legal para Cursos e Concursos', '614.19', '614', NULL, 'São Paulo', 'Gamatrom', NULL, NULL, '1995', '1 ed.', NULL, 'ex.1', 'p.135', 1, '20.00', 0, '003.', NULL, 1),
(1905, '2022-01-01', 'Domingos Sávio Zainaghi', 'Curso de legislação social: direito do trabalho', '351.83(81)', '350', '978.85.224.7069.3', 'São Paulo', 'Atlas S.A.', NULL, NULL, '2012', '13 ed.', 'v.1', 'ex.1', 'p.143', 1, '20.00', 0, '003.', NULL, 1),
(1906, '2022-01-01', 'CASTANHEIRA, Nelson Pereira', 'Estatística aplicada a todos os níveis - Série Matemática Aplicada', NULL, '519.5', '978.85.65704.91.5', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.253', 1, '20.00', 0, '003.', NULL, 1),
(1907, '2022-01-01', 'SILVA, Ernani João', 'Custos empresariais: uma visão sistêmica do processo de gestão de uma empresa', NULL, '658.1552', '978.85.5972.194.2', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2016', '1 ed.', NULL, 'ex.1', 'p.226', 1, '20.00', 0, '003.', NULL, 1),
(1908, '2022-01-01', 'Lon L. Fuller', 'O caso dos exploradores de cavernas', '340.12/ 340.11', '340.1', '85.882.7801.4', 'Porto Alegre', 'Fabris', NULL, NULL, '1976', '1 ed.', 'v.1', 'ex.1', 'p.75', 1, '20.00', 0, '003.', NULL, 1),
(1909, '2022-01-01', ' Nestor Sampaio Penteado Filho', 'Coleção OAB nacional - Primeira fase - Direitos Humanos - Vol. 13', '347.121.1(81)', '341.481', '978.85.14943.4', 'São Paulo', 'Saraiva', NULL, NULL, '2012', '3 ed.', 'v.13', 'ex.1', 'p.223', 1, '20.00', 0, '003.', NULL, 1),
(1910, '2022-01-01', 'Ângela . Cangiano Machado [et al.]', 'Coleção prática forense: Prática Penal - Vol. 6 ', '343.1', '345', '978.85.203.5974.7', 'São Paulo', 'Revista dos tribunais', NULL, NULL, '2015', '11 ed.', 'v.1', 'ex.1', 'p.479 ', 1, '15.00', 0, '003.', NULL, 1),
(1911, '2022-01-01', 'Francesco Carnelutti', 'As misérias do proceso penal', '341.1', NULL, '978.85.89919.40.1', 'São Paulo', 'Pillares', NULL, NULL, '2009', '3 ed.', 'v.1', 'ex.1', 'p.127', 1, '15.00', 0, '003.', NULL, 1),
(1912, '2022-01-01', 'FERRI, Ensico', 'Discursos de Acusação - ao lado das vítimas - Coleção a obra prima de cada autor - Vol 191', NULL, '808', '85.7232.650.2', 'São Paulo', 'Martins Claret', NULL, NULL, '2011', '2 ed.', 'v.191', 'ex.1', 'p.214', 1, '15.00', 0, '003.', NULL, 1),
(1913, '2022-01-01', 'Daniel Moretti', 'Coleção Pockets Jurídicos -Direito trabalhista II -  Vol. 31', '34.336.2(81)', '343', '978.85.02.08510.7', 'São Paulo', 'Saraiva', NULL, NULL, '2010', '1 ed.', 'v.1', 'ex.1', 'p.133', 1, '15.00', 0, '003.', NULL, 1),
(1914, '2022-01-01', 'Luiza Nagib Eluf', 'A paixão no banco dos réus - casos passionais célebres de Pontes Visgueiro a Pimenta Neves', '343.61.611(81)', '345.02', '85.02.03695.5', 'São Paulo', 'Saraiva', NULL, NULL, '2002', '1 ed.', 'v.1', 'ex.1', 'p.199', 1, '15.00', 0, '003.', NULL, 1),
(1915, '2022-01-01', 'Roque Sérgio D\'Andréa Ribeiro da Silva', 'Introdução ao Direito Constitucional Tributário', '34:336.2(81)', '342', '978.85.7838.906.2', 'Curitiba-PR', 'Ibpex', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.322', 1, '15.00', 0, '003.', NULL, 1),
(1916, '2022-01-01', 'SCHNEIDER, Elton Ivan ', 'A campanha empreendedora: a jornada de transformação de sonhos em realidade', NULL, '658.421', '978.85.8212.036.1', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.195', 1, '15.00', 0, '003.', NULL, 1),
(1917, '2022-01-01', 'Sérgio Pinto Martins', 'Direito do trabalho', '34:331(81)', '350', '85.224.3985.0', 'São Paulo', 'Atlas S.A.', NULL, NULL, '2005', '21 ed.', 'v.1', 'ex.1', 'p.895', 1, '15.00', 0, '003.', NULL, 1),
(1918, '2022-01-01', 'Reinaldo J. Themoteo (Coord.)', 'Reavivando e reforçando os diálogos entre Brasil e Europa - Série Relações Brasil- Europa - Vol.11', NULL, '320.6', '978.85.89432.05.0', 'Rio de Janeiro', 'Konrad Adenauer Stiftung', NULL, NULL, '2021', '1 ed.', 'v.11', 'ex.1', 'p.248', 1, '15.00', 0, '003.', NULL, 1),
(1919, '2022-01-01', 'Julian Krüper', 'Partidos Políticos: um enfoque transdisciplinar', NULL, '324.2', '978.65.89432.03.6', 'Rio de Janeiro', 'Konrad Adenauer Stiftung', NULL, NULL, '2020', '1 ed.', 'v.1', 'ex.1', 'p.324', 1, '15.00', 0, '003.', NULL, 1),
(1920, '2022-01-01', 'Laila Del Bem Seleme', 'Finanças sem complicação  ( Série Gestão Financeira)', NULL, '332', '978.85.8212.767.4', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.253', 1, '15.00', 0, '003.', NULL, 1),
(1921, '2022-01-01', 'Domingos Sávio Zainaghi', 'Processo do trabalho', '347331', '350', '978.85.203.3890.2', 'São Paulo', 'Revista dos tribunais', NULL, NULL, '2013', '2 ed.', 'v.1', 'ex.1', 'p.174', 1, '15.00', 0, '003.', NULL, 1),
(1922, '2022-01-01', 'Renato Marcão', 'Curso de execução penal', '343.8 (81) (094)', '345', '978.85.02.62107.7', 'São Paulo', 'Saraiva', NULL, NULL, '2015', '1 ed.', 'v.1', 'ex.1', 'p.376', 1, '15.00', 0, '003.', NULL, 1),
(1923, '2022-01-01', 'ELEUTERIO, Marcos Antonio Masoller ', 'Sistemas de informação gerenciais na atualidade', NULL, '658.403', '978.85.443.0285.9', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2015', '1 ed.', NULL, 'ex.1', 'p.199', 1, '15.00', 0, '003.', NULL, 1),
(1924, '2022-01-01', 'Cleso Antonio Bandeira de Mello', 'O conteúdo jurídico do princípio da igualdade', NULL, '341.481', '85.7420.047.6', 'São Paulo', 'Malheiros', NULL, NULL, '2012', '3 ed.', 'v.1', 'ex.1', 'p.48', 1, '15.00', 0, '003.', NULL, 1),
(1925, '2022-01-01', 'CHAVES, Maria Deosdédite Giaretta ', 'Manual prático de redação empresarial', '651.75', '651.75', '978.85.98366.39.5', 'Osasco-SP', 'Edifieo', NULL, NULL, '2011', '2 ed.', NULL, 'ex.1', 'p.156', 1, '15.00', 0, '003.', NULL, 1),
(1926, '2022-01-01', 'Luciano Amaro', 'Diteiro tributário Brasileiro', '34:336.2 (81)', NULL, '85.02.0750.1', 'São Paulo', 'Saraiva', NULL, NULL, '2002', '8 ed.', 'v.1', 'ex.1', 'p.491', 1, '30.00', 0, '003.', NULL, 1),
(1927, '2022-01-01', 'RANCICH FILHO, Nestor Alberto ', 'Adminstração estratégica (Série Adminsitração empresarial)', NULL, '658.4012', '978.85.8212.715.5', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.151', 1, '10.00', 0, '003.', NULL, 1),
(1928, '2022-01-01', 'CRUZ, Eduardo Picanço ', 'O processo decisório nas organizações (Série Admisntração estratégica)', NULL, '658.403', '978.85.8212.990.6', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2014', '1 ed.', NULL, 'ex.1', 'p.349', 1, '10.00', 0, '003.', NULL, 1),
(1929, '2022-01-01', 'MACEDO, Joel de Jesus ', 'Análise de projeto e orçamento empresarial (Série Gestão financeira)', NULL, '658.15', '978.85.8212.963.0', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2014', '1 ed.', NULL, 'ex.1', 'p.223', 1, '10.00', 0, '003.', NULL, 1),
(1930, '2022-01-01', 'BERTÉ, Rodrigo ', 'Gestão Ambiental no mercado empresarial', NULL, '658.4', '978.85.8212.789.6', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2013', '1 ed.', NULL, 'ex.1', 'p.199', 1, '10.00', 0, '003.', NULL, 1),
(1931, '2022-01-01', 'Paulo Vagner Ferreira', 'Análise de cenários econômicos (Série Gestão Financeira)', NULL, '338.9', '978.85.443.0244.6', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.218', 1, '10.00', 0, '003.', NULL, 1),
(1932, '2022-01-01', 'Flávio Ribas Tebchirani', 'Princípios de economia: micro e macro', NULL, '330', '978.85.8212.157.3', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.206', 1, '10.00', 0, '003.', NULL, 1),
(1933, '2022-01-01', 'SATNOS, Luiz Fernando Barcellos dos ', 'Evolução do pensamento administrativo (Série Administração empresarial)', NULL, '658.001', '978.85.8212.733.9', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2013', '1 ed.', NULL, 'ex.1', 'p.170', 1, '10.00', 0, '003.', NULL, 1),
(1934, '2022-01-01', 'Joni Tadeu Borges', 'Câmbio', NULL, '382', '978.85.65704.88.5', 'Curitiba-PR', 'Intersaberes', NULL, NULL, '2012', '1 ed.', 'v.1', 'ex.1', 'p.132', 1, '20.00', 0, '003.', NULL, 1),
(1935, '2022-01-01', 'Cood. Reinaldo J. Themoteo', 'Série Relações Brasil- Europa:  O novo acordo Mercosul - União Européia em perspectiva - Volume 10', NULL, '320.6', '978.65.990084.3.6', 'Rio de Janeiro', 'Konrad Adenauer Stiftung', NULL, NULL, '2020', '1 ed.', 'v.10', 'ex.1', 'p.92', 1, NULL, 1, '004.', NULL, 1),
(1936, '2022-01-01', 'Cood. Reinaldo J. Themoteo', 'Série Relações Brasil- Europa: Novos rumos da política na União Européia e os desdobramentos na América Latina - Volume 9', NULL, '320.6', '978.85.7504.232.8', 'Rio de Janeiro', 'Konrad Adenauer Stiftung', NULL, NULL, '2019', '1 ed.', 'v.9', 'ex.1', 'p.56', 1, NULL, 1, '004.', NULL, 1),
(1937, '2022-01-01', 'Assembleia Legiaslativa', 'Constituião do Estado de São Paulo - Constituição Federal Atualizada (2016)', '342.4(815.6)', '348', '978.85.61175.65.8', 'Brasilia - DF', 'Assembleias Legislativas', NULL, NULL, '2017', '1 ed.', 'v.1', 'ex.1', 'p.500', 1, NULL, 1, '004.', NULL, 1),
(1938, '2022-01-01', 'Acita e Prefeitura de Itapevi', 'Projeto Memória: Itapevi resgata sua história', NULL, '921', NULL, '**', '**', NULL, NULL, NULL, '1 ed.', 'v.1', 'ex.1', 'p.47', 1, '10.00', 0, '004.', NULL, 1),
(1939, '2022-01-01', 'Acita', 'Projeto Memória: Itapevi resgata a sua história - Caderno de Exercícios ', NULL, '921  ', NULL, '**', '**', NULL, NULL, '1997', '1 ed.', NULL, 'ex.1', 'p.34', 1, '10.00', 0, '004.', NULL, 1),
(1940, '2022-01-01', 'Congresso Nacional', 'Catálogo de Obras Raras e Valiosas da Coleção Luiz Viana Filho', NULL, '750', '978.85.7018.355.2', '**', '**', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p.471', 1, '10.00', 0, '003.', NULL, 1),
(1941, '2022-01-01', 'LOTTMAN, Herbert R.', 'A Rive Gauche: escritores, artístas e políticos em Paris 1930-1950', NULL, '944.081', ' 85.277.0034.4', 'Rio de Janeiro', 'Guanabara', NULL, NULL, '1987', '2.ed.', NULL, 'ex.1', 'p.421', 1, '30.00', 0, '003.', NULL, 1),
(1942, '2022-01-01', 'José carlos de Oliveira', 'Temas de direito público', NULL, '341', '978.85.7805.045.0', 'Jaboticabal', 'UNESP - Universidade Estadual Paulista', NULL, NULL, '2009', '1 ed.', NULL, 'ex.1', 'p.210', 1, '20.00', 0, '003.', NULL, 1),
(1943, '2022-01-01', 'VILELA, Antonio Carlos', 'Coisas que todo garoto deve saber', NULL, '028.5', '85.06.03109.5', 'São Paulo', 'Melhoramentos', NULL, NULL, '2005', '18 ed.', NULL, 'ex.1', 'p.97', 1, '20.00', 0, '003.', NULL, 1),
(1944, '2022-01-01', 'Secretaria de Educação de Várzea Paulista', 'Educação e diálogo. Encontros com educadores em Vársea Paulista', NULL, '371 R', '978.85.7805.070-2', 'São Paulo', 'Jaboticabal- Funep', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p. 171', 1, '20.00', 0, '003.', NULL, 1),
(1945, '2022-01-01', 'PEREIRA JÚNIOR, Jessé Torres', 'Da responsabilidade de agentes públicos e privados nos processos administrativos de licitação e contrataçaõ', '351.712', '350', '978.85.86314.83.4', 'São Paulo', 'NDJ', NULL, NULL, '2012', '1 ed.', NULL, 'ex.1', 'p.438', 1, '30.00', 0, '003.', NULL, 1),
(1946, '2022-01-01', 'LEME, Odilon Soares', 'Assim se escreve Gramática, Assim escreveram Literatura', NULL, '469', NULL, 'São Paulo', 'EPU', NULL, NULL, '1981', '1 ed.', NULL, 'ex.1', 'p.480', 1, '6.00', 0, '003.', NULL, 1),
(1947, '2022-01-01', 'SÃO PAULO, Estado', 'Coleção de leis e estatutos brasileiros : Constituição do Estado de São Paulo - Atualizada em 2004', '342.816.1', '342.4', '85.7060.333.9', 'São Paulo', 'Imprensa Oficial', NULL, NULL, '2005', '1 ed.', NULL, 'ex.1', 'p.168', 1, NULL, 1, '003.', NULL, 1),
(1948, '2022-01-01', 'COMPARATO, Fábio Konder', 'A afirmação histórica dos direitos humanos', '342', '441.481', '82.02.02877.4', 'São Paulo', 'Saraiva', NULL, NULL, '1999', '1 ed.', NULL, 'ex.1', 'p.421', 1, '10.00', 0, '003.', NULL, 1),
(1949, '2022-01-01', 'ITAPEVI, Câmara Municipal de Itapevi', 'Lei orgânica da Câmara Municipal de Itapevi - Atualizada em 2017', NULL, '348', NULL, 'Itapevi-SP', 'Câmara', NULL, NULL, '2018', '1 ed.', NULL, 'ex.1', 'p.53', 3, NULL, 1, '**', NULL, 1),
(1950, '2022-01-01', 'ITAPEVI, Câmara Municipal de Itapevi', 'Lei orgânica da Câmara Municipal de Itapevi - Atualizada em 2017', NULL, '348', NULL, 'Itapevi-SP', 'Câmara', NULL, NULL, '2018', '1 ed.', NULL, 'ex.2', 'p.53', 3, NULL, 1, '**', NULL, 1),
(1951, '2022-01-01', 'ITAPEVI, Câmara Municipal de Itapevi', 'Lei orgânica da Câmara Municipal de Itapevi - Atualizada em 2017', NULL, '348', NULL, 'Itapevi-SP', 'Câmara', NULL, NULL, '2018', '1 ed.', NULL, 'ex.3', 'p.53', 3, NULL, 1, '**', NULL, 1),
(1952, '2022-01-01', 'ITAPEVI, Câmara Municipal de Itapevi', 'Lei orgânica da Câmara Municipal de Itapevi - Atualizada em 2017', NULL, '348', NULL, 'Itapevi-SP', 'Câmara', NULL, NULL, '2018', '1 ed.', NULL, 'ex.4', 'p.53', 3, NULL, 1, '**', NULL, 1),
(1953, '2022-01-01', 'ITAPEVI, Câmara Municipal de Itapevi', 'Lei orgânica da Câmara Municipal de Itapevi - Atualizada em 2017', NULL, '348', NULL, 'Itapevi-SP', 'Câmara', NULL, NULL, '2018', '1 ed.', NULL, 'ex.5', 'p.53', 3, NULL, 1, '**', NULL, 1),
(1954, '2022-01-01', 'ITAPEVI, Câmara Municipal de Itapevi', 'Regimento Interno da Câmara Municipal de Itapevi - Atualizada em 2017', NULL, '348', NULL, 'Itapevi-SP', 'Câmara', NULL, NULL, '2018', '1 ed.', NULL, 'ex.1', 'p.98', 3, NULL, 1, '**', NULL, 1),
(1955, '2022-01-01', 'ITAPEVI, Câmara Municipal de Itapevi', 'Regimento Interno da Câmara Municipal de Itapevi - Atualizada em 2017', NULL, '348', NULL, 'Itapevi-SP', 'Câmara', NULL, NULL, '2018', '1 ed.', NULL, 'ex.2', 'p.98', 3, NULL, 1, '**', NULL, 1),
(1956, '2022-01-01', 'ITAPEVI, Câmara Municipal de Itapevi', 'Regimento Interno da Câmara Municipal de Itapevi - Atualizada em 2017', NULL, '348', NULL, 'Itapevi-SP', 'Câmara', NULL, NULL, '2018', '1 ed.', NULL, 'ex.3', 'p.98', 3, NULL, 1, '**', NULL, 1),
(1957, '2022-01-01', 'ITAPEVI, Câmara Municipal de Itapevi', 'Regimento Interno da Câmara Municipal de Itapevi - Atualizada em 2017', NULL, '348', NULL, 'Itapevi-SP', 'Câmara', NULL, NULL, '2018', '1 ed.', NULL, 'ex.4', 'p.98', 3, NULL, 1, '**', NULL, 1),
(1958, '2022-01-01', 'ITAPEVI, Câmara Municipal de Itapevi', 'Regimento Interno da Câmara Municipal de Itapevi - Atualizada em 2017', NULL, '348', NULL, 'Itapevi-SP', 'Câmara', NULL, NULL, '2018', '1 ed.', NULL, 'ex.5', 'p.98', 3, NULL, 1, '**', NULL, 1),
(1959, '2022-01-01', 'ROSSI, Marcelo ', 'Ágape', NULL, '241.4', NULL, 'São Paulo', 'Globo', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p.124', 1, '12.00', 0, '003.', NULL, 1),
(1960, '2022-01-01', 'GOUVÊA, Ricardo Quadros', 'A piedade pervertida', NULL, '248.4', NULL, 'São Paulo', 'Grapho ', NULL, NULL, '2006', '1 ed.', NULL, 'ex.1', 'p.110', 1, '30.00', 0, '003.', NULL, 1),
(1961, '2022-01-01', 'DANTAS, Humberto ', 'Cidades : para entender melhor o lugar onde vivemos', NULL, '307', '987.85.7504.235.9', 'Rio de Janeiro', 'Fundação Konrad Adenauer', NULL, NULL, '2019', '1 ed.', NULL, 'ex.1', 'p.148', 1, NULL, 1, '003.', NULL, 1),
(1962, '2022-01-01', 'DANTAS, Humberto ', 'Cidades : para entender melhor o lugar onde vivemos', NULL, '307', '987.85.7504.235.9', 'Rio de Janeiro', 'Fundação Konrad Adenauer', NULL, NULL, '2019', '1 ed.', NULL, 'ex.2', 'p.148', 1, NULL, 1, '003.', NULL, 1),
(1963, '2022-01-01', 'DANTAS, Humberto ', 'Cidades : para entender melhor o lugar onde vivemos', NULL, '307', '987.85.7504.235.9', 'Rio de Janeiro', 'Fundação Konrad Adenauer', NULL, NULL, '2019', '1 ed.', NULL, 'ex.3', 'p.148', 1, NULL, 1, '003.', NULL, 1),
(1964, '2022-01-01', 'DANTAS, Humberto ', 'Cidades : para entender melhor o lugar onde vivemos', NULL, '307', '987.85.7504.235.9', 'Rio de Janeiro', 'Fundação Konrad Adenauer', NULL, NULL, '2019', '1 ed.', NULL, 'ex.4', 'p.148', 1, NULL, 1, '003.', NULL, 1),
(1965, '2022-01-01', 'DANTAS, Humberto ', 'Cidades : para entender melhor o lugar onde vivemos', NULL, '307', '987.85.7504.235.9', 'Rio de Janeiro', 'Fundação Konrad Adenauer', NULL, NULL, '2019', '1 ed.', NULL, 'ex.5', 'p.148', 1, NULL, 1, '003.', NULL, 1),
(1966, '2022-01-01', 'HALFELD, Mauro', 'Investimentos : como administrar melhor o seu dinheiro', NULL, '332', '85.88350.01.7', 'São Paulo', 'Fundamentos educacionais', NULL, NULL, '2001', '1 ed.', NULL, 'ex.1', 'p.142', 1, NULL, 1, '003.', NULL, 1),
(1967, '2022-01-01', 'FERREIRA, Moacyr Costa', 'Dicionário de inventos e inventores', NULL, '608', NULL, 'São Paulo', 'Publicações', NULL, NULL, '1984', '1 ed.', NULL, 'ex.1', 'p.385', 1, NULL, 1, '003.', NULL, 1),
(1968, '2022-01-01', '***', 'Manual de Adminstração jurídica, contábil e financeira para organizações n ão-governamentais', '85.7596.008.3', '060', NULL, 'São Paulo', 'Peiorópolis', NULL, NULL, '2003', '1 ed.', NULL, 'ex.1', 'p.385', 1, NULL, 1, '003.', NULL, 1),
(1969, '2022-01-01', 'Senado Brasileiro', 'Fórum Senado Brasil 2012: Registros de um pensar sobre democracia', NULL, ' 320 R', NULL, 'Brasília-DF', 'SEEP - Editora do Senando Federal', NULL, NULL, '2012', NULL, NULL, 'ex.1', 'p. 197', 1, NULL, 1, '003.', NULL, 1),
(1970, '2022-01-01', 'UNESP', 'Serviço social e realidade - vol.15 / n.1', NULL, '301 R', '1413-4233', 'Franca-SP', 'Legis Summa Ltda', 'n. 1', NULL, '2006', NULL, 'v. 15', 'ex.1', 'p.288', 1, NULL, 1, '003.', NULL, 1),
(1971, '2022-01-01', 'Jefferson Carús Guedes e Juliana Sahione Mayrink Neiva (Org.)', 'Publicações da Escola da AGU (Advocacia Geral da União) - Vol 4', NULL, '341.2 R', '978.85.63257.02.4', 'São Paulo', 'Progressiva LTDA', NULL, NULL, '2010', NULL, 'v. 4', 'ex.1', 'p.175', 1, NULL, 1, '003.', NULL, 1),
(1972, '2022-01-01', 'Instituto Atende E UNESP-  Maria José Filho e Osvaldo Dalberio(Org.)', 'Família: conjuntura, organização e desenvolvimento', NULL, '301 R', '987.85.86420.98.6', 'Franca-SP', 'Legis Summa Ltda', NULL, NULL, '2007', NULL, NULL, 'ex.1', 'p.158', 1, NULL, 1, '003.', NULL, 1),
(1973, '2022-01-01', 'Faculdade de Ciências da UNESP', 'Ciência & Educação', NULL, '507 R', '1516-7313', 'Bauru-SP', 'UNESP - Universidade Estadual Paulista', 'n. 2', NULL, '2003', NULL, 'v. 9', 'ex.1', 'p.301-315', 1, NULL, 1, '003.', NULL, 1),
(1974, '2022-01-01', 'Faculdade de Ciências da UNESP', 'Ciência & Educação - Vol.10/ n.2', NULL, '507 R', '1516-7313', 'Bauru-SP', 'UNESP - Universidade Estadual Paulista', 'n. 2', NULL, '2004', NULL, 'v. 10', 'ex. 2', 'p.291-305', 1, NULL, 1, '003.', NULL, 1),
(1975, '2022-01-01', 'LAFER, Celso (Presidente do Conselho Editorial)', 'Política Externa', NULL, '327 R', '1518-6660', 'São Paulo', 'Politica Externa Editora', 'n.4', 'Mar/Abr/Maio', '2012', NULL, 'v. 20', 'ex.1', 'p.267', 1, NULL, 1, '003.', NULL, 1),
(1976, '2022-01-01', 'Fundação Konrad Adenauer ', 'Cadernos Adenauer: Fake News e as eleições de 2018', NULL, '320 R', '978.85.7504.225.0', 'Rio de Janeiro ', 'Fundação Adenauer', 'n. 4', 'Dez', 'XIX (2018)', NULL, NULL, 'ex.1', 'p.168', 1, NULL, 1, '003.', NULL, 1),
(1977, '2022-01-01', 'Supremo Trubunal Federal (STF)', 'Revista Trimestral de Jurisprudência / Vol.215', NULL, '340.6 R', '350.540', 'Brasília-DF', 'Supremo Tribunal Federal STF', NULL, 'jan a mar', '2011', NULL, 'v.215', 'ex.1', 'p.650', 1, NULL, 1, '003.', NULL, 1),
(1978, '2022-01-01', 'FMU -  Faculdades Metropolitanas Unidas', 'Revista do Curso de Direito da FMU - Vol.18 /  n.26', NULL, '340.1 R', NULL, 'São Paulo', 'UNI FMU', 'n.26', NULL, 'XVIII ', NULL, 'v.18', 'ex.1', 'p.224', 1, NULL, 1, '003.', NULL, 1),
(1979, '2022-01-01', 'NUPE - Núcleo Negro da UNESP', 'Revista Ethnos Brasil: Cultura e sociedade', NULL, '301 R', '16.762.843', 'São Paulo', 'UNESP - Universidade Estadual Paulista', 'n.1', 'Março', 'I (2002)', NULL, NULL, 'ex.1', 'p.154', 1, NULL, 1, '003.', NULL, 1),
(1980, '2022-01-01', 'NUPE - Núcleo Negro da UNESP', 'Revista Ethnos Brasil: Cultura e sociedade - Ano 1/ n.1', NULL, '301 R', '16.762.843', 'São Paulo', 'UNESP - Universidade Estadual Paulista', 'n.1', 'Março', 'I (2002)', NULL, NULL, 'ex. 2', 'p.154', 1, NULL, 1, '003.', NULL, 1),
(1981, '2022-01-01', 'Unesp', 'Ensaios de História', NULL, '900 R', '14.148.854', 'São Paulo', 'UNESP - Universidade Estadual Paulista', 'n.1', NULL, '2005', NULL, 'v.10', 'ex.1', 'p.1-118', 1, NULL, 1, '003.', NULL, 1),
(1982, '2022-01-01', 'IBGE', 'Brasil em números (Brazil in figures) - Volume 1', NULL, '310 R', '1808-1983', 'Rio de Janeiro ', 'IBGE', NULL, NULL, '1992', NULL, 'v.1', 'ex.1', 'p.477', 1, NULL, 1, '003.', NULL, 1),
(1983, '2022-01-01', 'Fundação Ulysses Guimarães', 'Eleições Estaduais: saber para vencer 2018', NULL, '320 R', NULL, 'Brasília-DF', 'Fundação Ulysses Guimarães', NULL, NULL, '2018', NULL, NULL, 'ex.1', 'p.159', 1, NULL, 1, '003.', NULL, 1),
(1984, '2022-01-01', 'Arquivo Municipal de SP', 'Revista do Arquivo Municipal de São Paulo - Edição comemorativa do cinquentenário', NULL, '980 R', '0034-9216', 'São Paulo', '***', NULL, NULL, '1984', NULL, NULL, 'ex.1', 'p.122', 1, NULL, 1, '003.', NULL, 1),
(1985, '2022-01-01', 'FONSECA, Joaquim ', 'Boletim de Direito Público - Centro de Estudos da Administração Pública - n.12/ Ano I', NULL, '340.1 R', '23.176.016', 'São Paulo', 'Fonseca e Bessa Advocacia', 'n.12', 'Outubro', 'I (2013)', NULL, NULL, 'ex.1', 'p.194', 1, NULL, 1, '003.', NULL, 1),
(1986, '2022-01-01', 'Câmara Municipal de SP', 'Revista do Parlamento Paulistano', NULL, '320 R', '22.382.844', 'São Paulo', 'Câmara de São Paulo', 'n.2', NULL, '1(2011)', NULL, 'v.2', 'ex.1', 'p.127', 1, NULL, 1, '003.', NULL, 1),
(1987, '2022-01-01', 'Escola do Parlamento da Câmara Municipal de SP', 'Revista Parlamento e sociedade - Vol.1/ n.1', NULL, '320 R', '23.184.248', 'São Paulo', 'Câmara de São Paulo', 'n.1', 'julho/dezembro', '2013', NULL, 'v.1', 'ex.1', 'p.1-120', 1, NULL, 1, '003.', NULL, 1),
(1988, '2022-01-01', 'Fundação Konrad Adenauer ', 'O Brasil no contexto político regional - Caderno Adenauer', NULL, '320 R', '15.190.951', 'São Paulo', 'Fundação Konrad Adenauer', 'n.4', 'novembro', 'XI (2010)', NULL, NULL, 'ex.1', 'p.231', 1, NULL, 1, '003.', NULL, 1),
(1989, '2022-01-01', 'Vários Autores', 'Revista de Iniciação Científica', NULL, '007 R', '16.787.706', 'Crisciúma-SC', 'Unesc - Univ. do Extremo Sul Catarinense', NULL, NULL, '2003', NULL, 'v.1', 'ex.1', 'p.175', 1, NULL, 1, '003.', NULL, 1),
(1990, '2022-01-01', 'Vários Autores', 'Revista de Iniciação Científica - XI Congresso de Iniciação científica da UNESP 1999 - n.1', NULL, '507 R', '15.185.400', 'São Paulo', 'UNESP - Universidade Estadual Paulista', 'n.1', NULL, '2000', NULL, NULL, 'ex.1', 'p.224', 1, NULL, 1, '003.', NULL, 1),
(1991, '2022-01-01', 'Vários Autores', 'Caderno de Formação Cultura: Experiências e Teorias - Volume 3', NULL, '301 R', '1980-3133', 'Ribeirão Preto', 'UNESP - Universidade Estadual Paulista', NULL, NULL, '2007', NULL, 'v.1', 'ex.1', 'p.74', 1, NULL, 1, '003.', NULL, 1),
(1992, '2022-01-01', 'Candido Giraldez Vieitez (Org.)', 'Oraganizações e Democracia', NULL, '301 R', '1519-0110', 'Marília-SP', 'UNESP - Universidade Estadual Paulista', 'n.1', NULL, '2000', NULL, NULL, 'ex.1', 'p.92', 1, NULL, 1, '003.', NULL, 1),
(1993, '2022-01-01', 'Unesp - Faculdade de Filosofia e Ciências ', 'Educação em Revista: O lúdico e a educação - Vol.7/ n.1', NULL, '150 R', '1518-7926', 'Marília-SP', 'UNESP - Universidade Estadual Paulista', 'n. 1', 'jun-dez', '2000', NULL, 'v. 7', 'ex.1', 'p.140', 1, NULL, 1, '003.', NULL, 1),
(1994, '2022-01-01', 'Unesp - Faculdade de Filosofia e Ciências ', 'Educação em Revista: Gestão democrática e educação - Vol.8/n.1', NULL, '150 R', '1518-7926', 'Marília-SP', 'UNESP - Universidade Estadual Paulista', 'n.1', 'jun-dez', '2007', NULL, 'v. 8', 'ex.1', 'p.86', 1, NULL, 1, '003.', NULL, 1),
(1995, '2022-01-01', 'Escola de Magistratura do Rio Grande do Norte', 'Revista Direito e Liberdade - Vol.16 / n.3', NULL, '340.1 R', '1809-3280', 'Natal-RN', 'Universidades', 'n.3', 'mai-ago', '2014', NULL, 'v. 16', 'ex.1', 'p.288', 1, NULL, 1, '003.', NULL, 1),
(1996, '2022-01-01', 'Escola de Magistratura do Rio Grande do Norte', 'Revista Direito e Liberdade- Vol.16 / n.2', NULL, '340.1 R', '1809-3280', 'Natal-RN', 'Universidades', 'n.2', 'set-dez', '2014', NULL, 'v. 16', 'ex.1', 'p.312', 1, NULL, 1, '003.', NULL, 1),
(1997, '2022-01-01', 'Universidadade Do Mato Grosso - UNEMAT', 'II Simpósio de Ensino, pesquisa e estenção: \"a Universidade e o compromisso na gestão ambiental', NULL, '370 R', '2177-7039', 'São Paulo', 'Universidades', NULL, NULL, '2010', NULL, NULL, 'ex.1', 'p.225', 1, NULL, 1, '003.', NULL, 1),
(1998, '2022-01-01', 'Associação dos Advogados de São Paulo', 'Revista do Advogado - Locação', NULL, '340.1 R', '0101-7497', 'São Paulo', 'AASP', '108', 'maio', '2010', NULL, NULL, 'ex.1', 'p.106', 1, NULL, 1, '003.', NULL, 1),
(1999, '2022-01-01', 'Associação dos Advogados de São Paulo', 'Revista do Advogado - Temas de direito eleitoral', NULL, '340.1 R', '0101-7497', 'São Paulo', 'AASP', '109', 'agosto', '2010', NULL, NULL, 'ex.1', 'p.129', 1, NULL, 1, '003.', NULL, 1),
(2000, '2022-01-01', 'Associação dos Advogados de São Paulo', 'Revista do Advogado - Relações de trabalho: justiça e equilibrio', NULL, '340.1 R', '0101-7497', 'São Paulo', 'AASP', '110', 'dez', '2010', NULL, NULL, 'ex.1', 'p.173', 1, NULL, 1, '003.', NULL, 1),
(2001, '2022-01-01', 'Associação dos Advogados de São Paulo', 'Revista do Advogado - Família e sucessões', NULL, '340.1 R', '0101-7497', 'São Paulo', 'AASP', '112', 'junho', '2011', NULL, NULL, 'ex.1', 'p.196', 1, NULL, 1, '003.', NULL, 1),
(2002, '2022-01-01', 'Associação dos Advogados de São Paulo', 'Revista do Advogado - A reforma do processo penal', NULL, '340.1 R', '0101-7497', 'São Paulo', 'AASP', '113', 'set', '2011', NULL, NULL, 'ex.1', 'p.148', 1, NULL, 1, '003.', NULL, 1),
(2003, '2022-01-01', 'Associação dos Advogados de São Paulo', 'Revista do Advogado - Direito e internet', NULL, '340.1 R', '0101-7497', 'São Paulo', 'AASP', '115', 'Abril', '2012', NULL, NULL, 'ex.1', 'p.143', 1, NULL, 1, '003.', NULL, 1),
(2004, '2022-01-01', 'Associação dos Advogados de São Paulo', 'Revista do Advogado - Contratos ', NULL, '340.1 R', '0101-7497', 'São Paulo', 'AASP', '116', 'Julho', '2012', NULL, NULL, 'ex.1', 'p.211', 1, NULL, 1, '003.', NULL, 1),
(2005, '2022-01-01', 'Associação dos Advogados de São Paulo', 'Revista do Advogado - Princípios constitucionais', NULL, '340.1 R', '0101-7497', 'São Paulo', 'AASP', '117', 'out', '2012', NULL, NULL, 'ex.1', 'p.201', 1, NULL, 1, '003.', NULL, 1),
(2006, '2022-01-01', 'Associação dos Advogados de São Paulo', 'Revista do Advogado - Temas atuais de direito tributário', NULL, '340.1 R', '0101-7497', 'São Paulo', 'AASP', '118', 'dez', '2012', NULL, NULL, 'ex.1', 'p.174', 1, NULL, 1, '003.', NULL, 1),
(2007, '2022-01-01', 'Associação dos Advogados de São Paulo', 'Revista do Advogado - Arbitragem', NULL, '340.1 R', '0101-7497', 'São Paulo', 'AASP', '119', 'Abril', '2013', NULL, NULL, 'ex.1', 'p.158', 1, NULL, 1, '003.', NULL, 1);
INSERT INTO `librarycollection` (`id`, `registrationDate`, `author`, `title`, `cdu`, `cdd`, `isbn`, `local`, `publisher_edition`, `number`, `month`, `year`, `edition`, `volume`, `copyNumber`, `pageNumber`, `typeAcquisitionId`, `price`, `prohibitedSale`, `provider`, `exclusionInfoTerm`, `registeredByUserId`) VALUES
(2008, '2022-01-01', 'Associação dos Advogados de São Paulo', 'Revista do Advogado - O processo eletrônico na visão do advogado', NULL, '340.1 R', '0101-7497', 'São Paulo', 'AASP', '120', 'agosto', '2013', NULL, NULL, 'ex.1', 'p.93', 1, NULL, 1, '003.', NULL, 1),
(2009, '2022-01-01', 'Associação dos Advogados de São Paulo', 'Revista do Advogado - 70 anos da CLT', NULL, '340.1 R', '0101-7497', 'São Paulo', 'AASP', '121', 'nov', '2013', NULL, NULL, 'ex.1', 'p.305', 1, NULL, 1, '003.', NULL, 1),
(2010, '2022-01-01', 'Associação dos Advogados de São Paulo', 'Revista do Advogado - Mediação e conciliação', NULL, '340.1 R', '0101-7497', 'São Paulo', 'AASP', '123', 'agosto', '2014', NULL, NULL, 'ex.1', 'p.181', 1, NULL, 1, '003.', NULL, 1),
(2011, '2022-01-01', 'Associação dos Advogados de São Paulo', 'Revista do Advogado - Corrupção', NULL, '340.1 R', '0101-7497', 'São Paulo', 'AASP', '125', 'dez', '2014', NULL, NULL, 'ex.1', 'p.146', 1, NULL, 1, '003.', NULL, 1),
(2012, '2022-01-01', 'Associação dos Advogados de São Paulo', 'Revista do Advogado - O novo código de processo civil', NULL, '340.1 R', '0101-7497', 'São Paulo', 'AASP', '126', 'maio', '2015', NULL, NULL, 'ex.1', 'p.209', 1, NULL, 1, '003.', NULL, 1),
(2013, '2022-01-01', 'Associação dos Advogados de São Paulo', 'Revista do Advogado - 10 anos da CNJ', NULL, '340.1 R', '0101-7497', 'São Paulo', 'AASP', '128', 'dez', '2015', NULL, NULL, 'ex.1', 'p.99', 1, NULL, 1, '003.', NULL, 1),
(2014, '2022-01-01', 'Associação dos Advogados de São Paulo', 'Revista do Advogado - O novo código de ética e disciplina da OAB', NULL, '340.1 R', '0101-7497', 'São Paulo', 'AASP', '129', 'abril', '2016', NULL, NULL, 'ex.1', 'p.170', 1, NULL, 1, '003.', NULL, 1),
(2015, '2022-01-01', 'Associação dos Advogados de São Paulo', 'Revista do Advogado - 25 Anos do código de defesa do consumidor - atuais desafios', NULL, '340.1 R', '0101-7497', 'São Paulo', 'AASP', '130', 'ago', '2016', NULL, NULL, 'ex.1', 'p.158', 1, NULL, 1, '003.', NULL, 1),
(2016, '2022-01-01', 'Associação dos Advogados de São Paulo', 'Revista do Advogado - Direito das empresas em crise', NULL, '340.1 R', '0101-7497', 'São Paulo', 'AASP', '131', 'out', '2016', NULL, NULL, 'ex.1', 'p.138', 1, NULL, 1, '003.', NULL, 1),
(2017, '2022-01-01', 'Associação dos Advogados de São Paulo', 'Revista do Advogado - Direito do agraonegócio', NULL, '340.1 R', '0101-7497', 'São Paulo', 'AASP', '134', 'junho', '2017', NULL, NULL, 'ex.1', 'p.170', 1, NULL, 1, '003.', NULL, 1),
(2018, '2022-01-01', 'Associação dos Advogados de São Paulo', 'Revista do Advogado - Direito Aeronáutico', NULL, '340.1 R', '0101-7497', 'São Paulo', 'AASP', '142', 'Junho', '2019', NULL, NULL, 'ex.1', 'p.153', 1, NULL, 1, '003.', NULL, 1),
(2019, '2022-01-01', 'Associação dos Advogados de São Paulo', 'Revista do Advogado - Homenagem a Walter Ceneviva', NULL, '340.1 R', '0101-7497', 'São Paulo', 'AASP', '145', 'Abril', '2020', NULL, NULL, 'ex.1', 'p.222', 1, NULL, 1, '003.', NULL, 1),
(2020, '2022-01-01', 'Associação dos Advogados de São Paulo', 'Revista do Advogado - Direito e Saude', NULL, '340.1 R', '0101-7497', 'São Paulo', 'AASP', '146', 'jun', '2020', NULL, NULL, 'ex.1', 'p.189', 1, NULL, 1, '003.', NULL, 1),
(2021, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Tráfico de drogas e constituição', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '1', NULL, '2009', NULL, NULL, 'ex.1', 'p.121', 1, NULL, 1, '003.', NULL, 1),
(2022, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Pena Mínima', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '2', NULL, '2009', NULL, NULL, 'ex.1', 'p.59', 1, NULL, 1, '003.', NULL, 1),
(2023, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Propriedade Intelectual', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '3', NULL, '2009', NULL, NULL, 'ex.1', 'p.35', 1, NULL, 1, '003.', NULL, 1),
(2024, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Direitos humanos', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '4', NULL, '2009', NULL, NULL, 'ex.1', 'p.54', 1, NULL, 1, '003.', NULL, 1),
(2025, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Direitos humanos', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '5', NULL, '2009', NULL, NULL, 'ex.1', 'p.58', 1, NULL, 1, '003.', NULL, 1),
(2026, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Penas Alternativas', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '6', NULL, '2009', NULL, NULL, 'ex.1', 'p.41', 1, NULL, 1, '003.', NULL, 1),
(2027, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Grupos de interesse (Lobby)', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '8', NULL, '2009', NULL, NULL, 'ex.1', 'p.76', 1, NULL, 1, '003.', NULL, 1),
(2028, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Direito Urbanistico', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '9', NULL, '2009', NULL, NULL, 'ex.1', 'p.62', 1, NULL, 1, '003.', NULL, 1),
(2029, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Ambiental', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '10', NULL, '2009', NULL, NULL, 'ex.1', 'p.59', 1, NULL, 1, '003.', NULL, 1),
(2030, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Igualdade de direitos entre mulheres e homens', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '11', NULL, '2009', NULL, NULL, 'ex.1', 'p.64', 1, NULL, 1, '003.', NULL, 1),
(2031, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Federalismo', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '13', NULL, '2009', NULL, NULL, 'ex.1', 'p.52', 1, NULL, 1, '003.', NULL, 1),
(2032, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Separação de poderes - vício de iniciativa', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '14', NULL, '2009', NULL, NULL, 'ex.1', 'p.58', 1, NULL, 1, '003.', NULL, 1),
(2033, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Observatório do judiciário', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '15', NULL, '2009', NULL, NULL, 'ex.1', 'p.57', 1, NULL, 1, '003.', NULL, 1),
(2034, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Estado democrático de direito e terceiro setor', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '16', NULL, '2009', NULL, NULL, 'ex.1', 'p.66', 1, NULL, 1, '003.', NULL, 1),
(2035, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Pena Mínima', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '17', NULL, '2009', NULL, NULL, 'ex.1', 'p.71', 1, NULL, 1, '003.', NULL, 1),
(2036, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Responsabilidade penal da pessoa jurídica', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '18', NULL, '2009', NULL, NULL, 'ex.1', 'p.76', 1, NULL, 1, '003.', NULL, 1),
(2037, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Estatuto dos povos indígenas', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '19', NULL, '2009', NULL, NULL, 'ex.1', 'p.59', 1, NULL, 1, '003.', NULL, 1),
(2038, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Reforma política e direito eleitoral', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '20', NULL, '2009', NULL, NULL, 'ex.1', 'p.44', 1, NULL, 1, '003.', NULL, 1),
(2039, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Agências reguladoras e tutela dos consumidores', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '21', NULL, '2010', NULL, NULL, 'ex.1', 'p.68', 1, NULL, 1, '003.', NULL, 1),
(2040, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Análise da nova lei de falências', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '22', NULL, '2013', NULL, NULL, 'ex.1', 'p.82', 1, NULL, 1, '003.', NULL, 1),
(2041, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Os novos procedimentos penais', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '23', NULL, '2013', NULL, NULL, 'ex.1', 'p.84', 1, NULL, 1, '003.', NULL, 1),
(2042, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - O papel da vítima no processo penal', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '24', NULL, '2013', NULL, NULL, 'ex.1', 'p.90', 1, NULL, 1, '003.', NULL, 1),
(2043, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Medidas assecuratórias no processo penal', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '25', NULL, '2013', NULL, NULL, 'ex.1', 'p.81', 1, NULL, 1, '003.', NULL, 1),
(2044, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - ECA: apuração do ato infracional atribuído a adolescentes', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '26', NULL, '2013', NULL, NULL, 'ex.1', 'p.68', 1, NULL, 1, '003.', NULL, 1),
(2045, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Conferências nacionais, participação social e processo legislativo', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '27', NULL, '2013', NULL, NULL, 'ex.1', 'p.66', 1, NULL, 1, '003.', NULL, 1),
(2046, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Junta comercial', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '28', NULL, '2013', NULL, NULL, 'ex.1', 'p.64', 1, NULL, 1, '003.', NULL, 1),
(2047, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Desconsideração da personalidade jurídica', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '29', NULL, '2013', NULL, NULL, 'ex.1', 'p.78', 1, NULL, 1, '003.', NULL, 1),
(2048, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Controle de constitucionalidade dos atos do poder executivo', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '30', NULL, '2013', NULL, NULL, 'ex.1', 'p.76', 1, NULL, 1, '003.', NULL, 1),
(2049, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Processo legislativo e controle de constitucionalidade', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '31', NULL, '2013', NULL, NULL, 'ex.1', 'p.90', 1, NULL, 1, '003.', NULL, 1),
(2050, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Análise das justificativas para a produção de normas penais', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '32', NULL, '2013', NULL, NULL, 'ex.1', 'p.72', 1, NULL, 1, '003.', NULL, 1),
(2051, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Coordenação do sistema de controle da administração pública federal', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '33', NULL, '2013', NULL, NULL, 'ex.1', 'p.93', 1, NULL, 1, '003.', NULL, 1),
(2052, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Improbidade administrativa', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '34', NULL, '2013', NULL, NULL, 'ex.1', 'p.89', 1, NULL, 1, '003.', NULL, 1),
(2053, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Propriedade intelectual e conhecimentos tradicionais', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '36', NULL, '2013', NULL, NULL, 'ex.1', 'p.84', 1, NULL, 1, '003.', NULL, 1),
(2054, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Dano Moral', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '37', NULL, '2013', NULL, NULL, 'ex.1', 'p.75', 1, NULL, 1, '003.', NULL, 1),
(2055, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - O desenho de sistemas de resolução alternativa de disputas para conflitos de interesse público', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '38', NULL, '2013', NULL, NULL, 'ex.1', 'p.92', 1, NULL, 1, '003.', NULL, 1),
(2056, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Regime jurídico dos bens da união', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '39', NULL, '2013', NULL, NULL, 'ex.1', 'p.106', 1, NULL, 1, '003.', NULL, 1),
(2057, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Modernizção do sistema de convênio da administração pública com a sociedade civil', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '41', NULL, '2013', NULL, NULL, 'ex.1', 'p.81', 1, NULL, 1, '003.', NULL, 1),
(2058, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Por um sistema nacional de ouvidorias públicas possibilidades e obstáculos', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '42', NULL, '2013', NULL, NULL, 'ex.1', 'p.86', 1, NULL, 1, '003.', NULL, 1),
(2059, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Bancos de perfis genéticos para fins de persecução criminal', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '43', NULL, '2013', NULL, NULL, 'ex.1', 'p.87', 1, NULL, 1, '003.', NULL, 1),
(2060, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Internalização da normas do mercosul', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '45', NULL, '2013', NULL, NULL, 'ex.1', 'p.97', 1, NULL, 1, '003.', NULL, 1),
(2061, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Regime jurídico de cooperativas populares e empreendimentos de economia solidária', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '46', NULL, '2013', NULL, NULL, 'ex.1', 'p.111', 1, NULL, 1, '003.', NULL, 1),
(2062, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Crime de cartel e a reparação de danos no poder judiciário brasileiro', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '47', NULL, '2013', NULL, NULL, 'ex.1', 'p.58', 1, NULL, 1, '003.', NULL, 1),
(2063, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Recuperação de terras públicas e modernização do registro de imóveis', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '48', NULL, '2013', NULL, NULL, 'ex.1', 'p.91', 1, NULL, 1, '003.', NULL, 1),
(2064, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Mecanismos jurídicos para a modernização e transparência da gestão pública - Volume 1', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '49.v1', NULL, '2013', NULL, NULL, 'ex.1', 'p.392', 1, NULL, 1, '003.', NULL, 1),
(2065, '2022-01-01', 'Faculdade Nacional do Direito da Universidade Federal do Rio de Janeiro', 'Série Pensando o Direito - Especial: O papel da pesquisa na política legislativa', NULL, '340.1 R', '2175-5760', 'Brasília-DF', 'UNIVERSIDADES', '50', NULL, '2013', NULL, NULL, 'ex.1', 'p.121', 1, NULL, 1, '003.', NULL, 1),
(2066, '2022-01-01', 'Marcos Antônio C Paixão (Dir.)', 'Revista Jurídica: orgão nacional de doutrina, jurisprudência, legislação e crícita judiciária - Juros e Licitações', NULL, '340.1 R', NULL, 'Sapucaia do Sul - RS', 'Notadez Informações Ltda', 'n.255', 'jan', '46 - 1999', NULL, NULL, 'ex.1', 'p.174', 1, NULL, 1, '003.', NULL, 1),
(2067, '2022-01-01', 'Escola do Parlamento da Câmara Municipal de SP', 'Revista Parlamento e sociedade - Vol.1/ n.1', NULL, '328 R', '2318-4248', 'São Paulo', 'Câmara de São Paulo', 'n.1', 'jun-dez', '2013', NULL, 'v. 1', 'ex.1', 'p.1-120', 1, '0.00', 0, '003.', NULL, 1),
(2068, '2022-01-01', 'Escola do Parlamento da Câmara Municipal de SP', 'Revista Parlamento e sociedade  - Vol.2 /n.2', NULL, '328 R', '2318-4248', 'São Paulo', 'Câmara de São Paulo', 'n.2', 'jan-jun', '2014', NULL, 'v. 2', 'ex.1', 'p.1-120', 1, NULL, 1, '003.', NULL, 1),
(2069, '2022-01-01', 'Escola do Parlamento da Câmara Municipal de SP', 'Revista Parlamento e sociedade - vol.3/ n.2', NULL, '328 R', '2318-4248', 'São Paulo', 'Câmara de São Paulo', 'n.2', 'jul-dez', '2014', NULL, 'v. 3', 'ex.1', 'p.1-108', 1, NULL, 1, '003.', NULL, 1),
(2070, '2022-01-01', 'Escola do Parlamento da Câmara Municipal de SP', 'Revista Parlamento e sociedade - Vol. 2/ n.3', NULL, '328 R', '2318-4248', 'São Paulo', 'Câmara de São Paulo', 'n.3', 'jul-dez', '2014', NULL, 'v. 2', 'ex.1', 'p.1-108', 1, NULL, 1, '003.', NULL, 1),
(2071, '2022-01-01', 'Escola do Parlamento da Câmara Municipal de SP', 'Revista Parlamento e sociedade - Vol. 3/ n.4', NULL, '328 R', '2318-4248', 'São Paulo', 'Câmara de São Paulo', 'n.4', 'jan-jun', '2015', NULL, 'v.3', 'ex.1', 'p.1-120', 1, NULL, 1, '003.', NULL, 1),
(2072, '2022-01-01', 'Escola do Parlamento da Câmara Municipal de SP', 'Revista Parlamento e sociedade - Vol.3/ n.5', NULL, '328 R', '2318-4248', 'São Paulo', 'Câmara de São Paulo', 'n.5', 'jul-dez', '2015', NULL, 'v.3', 'ex.1', 'p.1-128', 1, NULL, 1, '003.', NULL, 1),
(2073, '2022-01-01', 'Escola do Parlamento da Câmara Municipal de SP', 'Revista Parlamento e sociedade - vol.4/ n.6', NULL, '328 R', '2318-4248', 'São Paulo', 'Câmara de São Paulo', 'n.6', 'jan-jun', '2016', NULL, 'v.4', 'ex.1', 'p.1-136', 1, NULL, 1, '003.', NULL, 1),
(2074, '2022-01-01', 'Escola do Parlamento da Câmara Municipal de SP', 'Revista Parlamento e sociedade - Vol.4 /n.7', NULL, '328 R', '2318-4248', 'São Paulo', 'Câmara de São Paulo', 'n.7', 'jul-dez', '2016', NULL, 'v.4', 'ex.1', 'p.1-152', 1, NULL, 1, '003.', NULL, 1),
(2075, '2022-01-01', 'Escola do Parlamento da Câmara Municipal de SP', 'Revista Parlamento e sociedade - Vol.5/ n.8', NULL, '328 R', '2318-4248', 'São Paulo', 'Câmara de São Paulo', 'n.8', 'jan-jun', '2017', NULL, 'v.5', 'ex.1', 'p.1-124', 1, NULL, 1, '003.', NULL, 1),
(2076, '2022-01-01', 'Escola do Parlamento da Câmara Municipal de SP', 'Revista Parlamento e sociedade - Vol.5 /n.9', NULL, '328 R', '2318-4248', 'São Paulo', 'Câmara de São Paulo', 'n.9', 'jul-dez', '2017', NULL, 'v.5', 'ex.1', 'p.1-128', 1, NULL, 1, '003.', NULL, 1),
(2077, '2022-01-01', 'Câmara Municipal de SP', 'Revista Consultoria Técnico- Legislativa - SGP.5  - Vol.2/ n.1 ', NULL, '328 R', '2316-798x', 'São Paulo', 'Câmara de São Paulo', 'n.1', 'jan-dez', '2013', NULL, 'v.2', 'ex.1', 'p.1-212', 1, NULL, 1, '003.', NULL, 1),
(2078, '2022-01-01', 'Câmara Municipal de SP', 'Revista Procuradoria da Câmara Municipal de Sâo Paulo - Vol. 1/ n.1', NULL, '328 R', '2316-7998', 'São Paulo', 'Câmara de São Paulo', '1n.', 'jan-dez', '2012', NULL, 'v.1', 'ex.1', 'p.1-192', 1, NULL, 1, '003.', NULL, 1),
(2079, '2022-01-01', 'Câmara Municipal de SP', 'Revista Procuradoria da Câmara Municipal de Sâo Paulo - Vol. 2/ n.1', NULL, '328 R', '2316-7998', 'São Paulo', 'Câmara de São Paulo', 'n.1', 'jan-dez', '2013', NULL, 'v.2', 'ex.1', 'p.1-204', 1, NULL, 1, '003.', NULL, 1),
(2080, '2022-01-01', 'Câmara Municipal de SP', 'Revista Procuradoria da Câmara Municipal de Sâo Paulo  - Vol.3/ n.1 ', NULL, '328 R', '2316-7998', 'São Paulo', 'Câmara de São Paulo', 'n.1', 'jan-dez', '2014', NULL, 'v.3', 'ex.1', 'p.1-248', 1, NULL, 1, '003.', NULL, 1),
(2081, '2022-01-01', 'Tribunal de Contas de SP', 'Revista do Tribunal de Contas do Estado de São Paulo - n.100', NULL, '348 R', NULL, 'São Paulo', 'Tribunal de Contas do Estado de SP', 'n.100', 'jul/01 a Mai/02', '2001', NULL, NULL, 'ex.1', 'p.186', 1, NULL, 1, '003.', NULL, 1),
(2082, '2022-01-01', 'Tribunal de Contas de SP', 'Revista do Tribunal de Contas do Estado de São Paulo - n.101', NULL, '348 R', NULL, 'São Paulo', 'Tribunal de Contas do Estado de SP', 'n.101', 'Jun/02 a out/02', '2002', NULL, NULL, 'ex.1', 'p.71', 1, NULL, 1, '003.', NULL, 1),
(2083, '2022-01-01', 'Tribunal de Contas de SP', 'Revista do Tribunal de Contas do Estado de São Paulo - n.102', NULL, '348 R', NULL, 'São Paulo', 'Tribunal de Contas do Estado de SP', 'n.102', 'Nov/02 a Jan/02', '2002', NULL, NULL, 'ex.1', 'p.180', 1, NULL, 1, '003.', NULL, 1),
(2084, '2022-01-01', 'Tribunal de Contas de SP', 'Revista do Tribunal de Contas do Estado de São Paulo - n.105', NULL, '348 R', NULL, 'São Paulo', 'Tribunal de Contas do Estado de SP', 'n.105', 'Ago/03 a out/03', '2003', NULL, NULL, 'ex.1', 'p.143', 1, NULL, 1, '003.', NULL, 1),
(2085, '2022-01-01', 'Tribunal de Contas de SP', 'Revista do Tribunal de Contas do Estado de São Paulo - n.106', NULL, '348 R', NULL, 'São Paulo', 'Tribunal de Contas do Estado de SP', 'n.106', 'nov/03 a jan/04', '2004', NULL, NULL, 'ex.1', 'p.164', 1, NULL, 1, '003.', NULL, 1),
(2086, '2022-01-01', 'Tribunal de Contas de SP', 'Revista do Tribunal de Contas do Estado de São Paulo - n.108', NULL, '348 R', NULL, 'São Paulo', 'Tribunal de Contas do Estado de SP', 'n.108', 'maio/ a jul/04', '2004', NULL, NULL, 'ex.1', 'p.87', 1, NULL, 1, '003.', NULL, 1),
(2087, '2022-01-01', 'FAPESP - Fundação de Amparo a pesquisa do Estado de São Paulo', 'Revista Pesquisa Ciência e Tecnologia no Brasil - n.222', NULL, '302.2324 R', '1519-8779', 'São Paulo', 'FAPESP', 'n.222', 'set', '2010', NULL, NULL, 'ex.1', 'p.98', 1, NULL, 1, '003.', NULL, 1),
(2088, '2022-01-01', 'FAPESP - Fundação de Amparo a pesquisa do Estado de São Paulo', 'Revista Pesquisa Ciência e Tecnologia no Brasil - N.220', NULL, '302.2324 R', '1519-8779', 'São Paulo', 'FAPESP', 'n.220', 'Fev', '2014', NULL, NULL, 'ex.1', 'p.98', 1, NULL, 1, '003.', NULL, 1),
(2089, '2022-01-01', 'FAPESP - Fundação de Amparo a pesquisa do Estado de São Paulo', 'Revista Pesquisa Ciência e Tecnologia no Brasil - N.216', NULL, '302.2324 R', '1519-8779', 'São Paulo', 'FAPESP', 'n.216', 'jun', '2014', NULL, NULL, 'ex.1', 'p.98', 1, NULL, 1, '003.', NULL, 1),
(2090, '2022-01-01', 'Universidade Fedral de Uberlância-MG', 'Revista Pesquisa Ciência e Tecnologia no Brasil - Vol.11 / n.1', NULL, '302.2324 R', '1678-5622', 'Uberlândia-MG', 'UNIVERSIDADES', 'n.1', 'jan/jun', 'XVI - 2012', NULL, 'v.11', 'ex.1', 'p.177', 1, NULL, 1, '003.', NULL, 1),
(2091, '2022-01-01', 'Universidade Fedral de Uberlância-MG', 'Revista Pesquisa Ciência e Tecnologia no Brasil- Vol.11 / n.2', NULL, '302.2324 R', '1678-5622', 'Uberlândia-MG', 'UNIVERSIDADES', 'n.2', 'jul/dez', '2012', NULL, 'v.11', 'ex.1', 'p.170', 1, NULL, 1, '003.', NULL, 1),
(2092, '2022-01-01', 'Universidade Fedral de Uberlância-MG', 'Revista Pesquisa Ciência e Tecnologia no Brasil- Vol.12 / n.2', NULL, '302.2324 R', '1678-5622', 'Uberlândia-MG', 'UNIVERSIDADES', 'n.2', 'jul/dez', '2013', NULL, 'v.12', 'ex.1', 'p.153', 1, NULL, 1, '003.', NULL, 1),
(2093, '2022-01-01', 'Fundação Cultura Exêrcito Brasileiro', 'Revista da Cultura - Forte dos Andradas ', NULL, '302.2324 R', '1984-3690', 'Rio de Janeiro ', 'Fundações', 'n.28', 'Abril', 'XVI (2017)', NULL, NULL, 'ex.1', 'p.63', 1, NULL, 1, '003.', NULL, 1),
(2094, '2022-01-01', 'Ministério da Educação', 'Revista Brasileira da Educação Profissional e Tecnológica - Vol. 1/ n.1', NULL, '302.2324 R', '1983-0408', 'Brasília-DF', 'MINISTÉRIOS', 'n.1', 'Jun', '2008', NULL, 'v.1', 'ex.1', 'p.160', 1, NULL, 1, '003.', NULL, 1),
(2095, '2022-01-01', 'Ministério da Educação', 'Revista Brasileira da Educação Profissional e Tecnológica - Vol. 2/ n.2', NULL, '302.2324 R', '1983-0408', 'Brasília-DF', 'MINISTÉRIOS', 'n.2', 'nov', '2009', NULL, 'v.2', 'ex.1', 'p.114', 1, NULL, 1, '003.', NULL, 1),
(2096, '2022-01-01', 'Instituto Nacional de Estudos e Pesquisas Educacionais Anísio Teixeira', 'Revista Brasileira de Estudos Pedagógicos', NULL, '302.2324 R', '0034-7183', 'Brasília-DF', 'MINISTÉRIOS', 'n.224', 'Jan/abr', '2009', NULL, 'v.90', 'ex.1', 'p.251', 1, NULL, 1, '003.', NULL, 1),
(2097, '2022-01-01', 'USP', 'Revista Cultura e extensão USP - n.8', NULL, '302.2324 R', '2175-6805', 'São Paulo', 'UNIVERSIDADES', 'n.8', 'nov', '2012', NULL, NULL, 'ex.1', 'p.172', 1, NULL, 1, '003.', NULL, 1),
(2098, '2022-01-01', 'USP', 'Revista Cultura e extensão USP - n.10', NULL, '302.2324 R', '2175-6805', 'São Paulo', 'UNIVERSIDADES', 'n.10', 'nov', '2013', NULL, NULL, 'ex.1', 'p.137', 1, NULL, 1, '003.', NULL, 1),
(2099, '2022-01-01', 'USP', 'Revista Cultura e extensão USP  - n.15', NULL, '302.2324 R', '2175-6805', 'São Paulo', 'UNIVERSIDADES', 'n.15', 'jun/jul', '2016', NULL, NULL, 'ex.1', 'p.114', 1, NULL, 1, '003.', NULL, 1),
(2100, '2022-01-01', 'USP', 'Revista Cultura e extensão USP - n.16', NULL, '302.2324 R', '2175-6805', 'São Paulo', 'UNIVERSIDADES', 'n.16', 'nov', '2016', NULL, NULL, 'ex.1', 'p.132', 1, NULL, 1, '003.', NULL, 1),
(2101, '2022-01-01', 'Tribunal de Contas de SP', 'Ciclo de debates com agentes políticos e dirigentes municipais - 20 anos (1996-2016)', NULL, '348 R', '0103-5746', 'São Paulo', 'Tribunal de Contas do Estado de SP', 'n.136', NULL, '2016', NULL, NULL, 'ex.1', 'p.92', 1, NULL, 1, '003.', NULL, 1),
(2102, '2022-01-01', 'Federação das Apaes de SP', 'Apae em destaque', NULL, '302.2324 R', NULL, 'São Paulo', 'Fundações', 'n.23', 'Dez', '2019', NULL, NULL, 'ex.1', 'p.62', 1, NULL, 1, '003.', NULL, 1),
(2103, '2022-01-01', 'Diário da Região', 'Diário da Região - 50 anos', NULL, '302.232.4 R', NULL, 'Osasco-SP', '**', NULL, NULL, '2019', NULL, NULL, 'ex.1', 'p.125', 1, NULL, 1, '003.', NULL, 1),
(2104, '2022-01-01', 'Senado Federal', '30 Anos da Carta das Mulheres', NULL, '302.2324 R', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2018', NULL, NULL, 'ex.1', 'p.53', 1, NULL, 1, '003.', NULL, 1),
(2105, '2022-01-01', 'Ministério da Cultura', 'Revista dos Palmares - Cultura Afro-brasileira', NULL, '302.2324 R', '1808-7280', 'Brasília-DF', 'MINISTÉRIOS', 'n.1', 'ago', 'I - 2005', NULL, NULL, 'ex.1', 'p.56', 1, NULL, 1, '003.', NULL, 1),
(2106, '2022-01-01', 'Revista da ABEU', 'Revista Verbo - n.9', NULL, '302.2324 R', '2178-1869', 'São Paulo', '**', 'n.9', 'ago', '2013', NULL, NULL, 'ex.1', 'p.56', 1, NULL, 1, '003.', NULL, 1),
(2107, '2022-01-01', 'Revista da ABEU', 'Revista Verbo - n.10', NULL, '302.2324 R', '2178-1869', 'São Paulo', '**', 'n.10', 'ago', '2014', NULL, NULL, 'ex.1', 'p.56', 1, NULL, 1, '003.', NULL, 1),
(2108, '2022-01-01', 'UNIVERSIDADE DE SÃO CAETANO', 'Revista Gestão e Regionalidade - n.63 / vol. 22', NULL, '302.2324 R', '1808-5792', 'São Paulo', 'UNIVERSIDADES', 'n.63', 'Jan/abr', '2006', NULL, 'v.22', 'ex.1', 'p.82', 1, NULL, 1, '003.', NULL, 1),
(2109, '2022-01-01', 'Fundação Mario Covas', 'Visitas Internacionais - Fundação Mário Covas em Revista', NULL, '302.2324 R', ' ', 'São Paulo', 'FUNDAÇÕES', 'n.2', 'Março', 'I - 2009', NULL, NULL, 'ex.1', 'p. 104', 1, NULL, 1, '003.', NULL, 1),
(2110, '2022-01-01', 'Fundação Mario Covas', 'Acervos de Personalidades - Fundação Mário Covas em Revista ', NULL, '302.2324 R', NULL, 'São Paulo', 'FUNDAÇÕES', 'n.3', 'Março', 'I - 2009', NULL, NULL, 'ex.1', 'p. 88', 1, NULL, 1, '003.', NULL, 1),
(2111, '2022-01-01', 'Fundação Mario Covas', 'História Oral - Fundação Mário Covas em Revista', NULL, '302.2324 R', NULL, 'São Paulo', 'FUNDAÇÕES', 'n.4', 'Março', 'I - 2009', NULL, NULL, 'ex.1', 'p. 88', 1, NULL, 1, '003.', NULL, 1),
(2112, '2022-01-01', 'Fundação Mario Covas', 'Propaganda Política - Fundação Mário Covas em Revista', NULL, '302.2324 R', NULL, 'São Paulo', 'FUNDAÇÕES', 'n. 5', 'Março', 'I - 2009', NULL, NULL, 'ex.1', 'p. 88', 1, NULL, 1, '003.', NULL, 1),
(2113, '2022-01-01', 'Interlegis ', 'Initerlegis 18 anos - História, Conquistas e desafios', NULL, '302.2324 R', NULL, 'Brasília-DF', '**', 'n.1', NULL, '2015', NULL, NULL, 'ex.1', 'p. 80', 1, NULL, 1, '003.', NULL, 1),
(2114, '2022-01-01', 'Escola do Parlamento da Câmara Municipal de Itapevi', 'Revista Parlamento de Itapevi - Democracia e Cidadania', NULL, '328 R', NULL, 'Itapevi-SP', '**', 'n.1', NULL, '2020', NULL, NULL, 'ex.1', 'p.79', 3, NULL, 1, '**', NULL, 1),
(2115, '2022-01-01', 'Escola do Parlamento da Câmara Municipal de Itapevi', 'Revista Parlamento de Itapevi - Democracia e Cidadania', NULL, '328 R', NULL, 'Itapevi-SP', '**', 'n.1', NULL, '2020', NULL, NULL, 'ex.2', 'p.79', 3, NULL, 1, '**', NULL, 1),
(2116, '2022-01-01', 'Escola do Parlamento da Câmara Municipal de Itapevi', 'Revista Parlamento de Itapevi - Democracia e Cidadania', NULL, '328 R', NULL, 'Itapevi-SP', '**', 'n.1', NULL, '2020', NULL, NULL, 'ex.3', 'p.79', 3, NULL, 1, '**', NULL, 1),
(2117, '2022-01-01', 'Escola do Parlamento da Câmara Municipal de Itapevi', 'Revista Parlamento de Itapevi - Democracia e Cidadania', NULL, '328 R', NULL, 'Itapevi-SP', '**', 'n.1', NULL, '2020', NULL, NULL, 'ex.4', 'p.79', 3, NULL, 1, '**', NULL, 1),
(2118, '2022-01-01', 'Escola do Parlamento da Câmara Municipal de Itapevi', 'Revista Parlamento de Itapevi - Democracia e Cidadania', NULL, '328 R', NULL, 'Itapevi-SP', '**', 'n.1', NULL, '2020', NULL, NULL, 'ex.5', 'p.79', 3, NULL, 1, '**', NULL, 1),
(2119, '2022-01-01', '***', 'Revista Saúde já Vital', NULL, '302.2324 R', '0104-1568', 'São Paulo', 'Abril Editora', 'n.418', 'jul', '2017', NULL, NULL, 'ex.1', 'p.74', 1, '10.00', 0, '003.', NULL, 1),
(2120, '2022-01-01', '***', 'Revista Istoé', NULL, '302.2324 R', '0104-3943', 'São Paulo', 'Três Editorial Ltda', 'n.2584', 'jul', '2019', NULL, NULL, 'ex.1', 'p.66', 1, '10.00', 0, '003.', NULL, 1),
(2121, '2022-01-01', '***', 'Revista Istoé', NULL, '302.2324 R', '0104-3943', 'São Paulo', 'Três Editorial Ltda', 'n.2585', 'jul', '2019', NULL, NULL, 'ex.1', 'p. 66', 1, '10.00', 0, '003.', NULL, 1),
(2122, '2022-01-01', '***', 'Revista Época', NULL, '302.2324 R', NULL, 'São Paulo', 'Globo', 'n.979', 'mar', '2017', NULL, NULL, 'ex.1', 'p.82', 1, '10.00', 0, '003.', NULL, 1),
(2123, '2022-01-01', '***', 'Revista Época', NULL, '302.2324 R', NULL, 'São Paulo', 'Globo', 'n.980', 'Abril', '2017', NULL, NULL, 'ex.1', 'p.82', 1, '10.00', 0, '003.', NULL, 1),
(2124, '2022-01-01', '***', 'Revista Época', NULL, '302.2324 R', NULL, 'São Paulo', 'Globo', 'n.982', 'Abril', '2017', NULL, NULL, 'ex.1', 'p.82', 1, '10.00', 0, '003.', NULL, 1),
(2125, '2022-01-01', '***', 'Revista Época', NULL, '302.2324 R', NULL, 'São Paulo', 'Globo', 'n.1074', 'fev', '2019', NULL, NULL, 'ex.1', 'p.82', 1, '10.00', 0, '003.', NULL, 1),
(2126, '2022-01-01', '***', 'Revista Época', NULL, '302.2324 R', NULL, 'São Paulo', 'Globo', 'n.1075', 'Fev', '2019', NULL, NULL, 'ex.1', 'p.82', 1, '10.00', 0, '003.', NULL, 1),
(2127, '2022-01-01', '***', 'Revista Época', NULL, '302.2324 R', NULL, 'São Paulo', 'Globo', 'n.1076', 'Fev', '2019', NULL, NULL, 'ex.1', 'p.82', 1, '10.00', 0, '003.', NULL, 1),
(2128, '2022-01-01', '***', 'Revista Época', NULL, '302.2324 R', NULL, 'São Paulo', 'Globo', 'n.1081', 'mar', '2019', NULL, NULL, 'ex.1', 'p.82', 1, '10.00', 0, '003.', NULL, 1),
(2129, '2022-01-01', '***', 'Revista Época', NULL, '302.2324 R', NULL, 'São Paulo', 'Globo', 'n.1082', 'Abril', '2019', NULL, NULL, 'ex.1', 'p.82', 1, '10.00', 0, '003.', NULL, 1),
(2130, '2022-01-01', '***', 'Revista Época', NULL, '302.2324 R', NULL, 'São Paulo', 'Globo', 'n.1085', 'Abril', '2019', NULL, NULL, 'ex.1', 'p.82', 1, '10.00', 0, '003.', NULL, 1),
(2131, '2022-01-01', '***', 'Revista Época', NULL, '302.2324 R', NULL, 'São Paulo', 'Globo', 'n.1087', 'mai', '2019', NULL, NULL, 'ex.1', 'p.82', 1, '10.00', 0, '003.', NULL, 1),
(2132, '2022-01-01', '***', 'Revista Época', NULL, '302.2324 R', NULL, 'São Paulo', 'Globo', 'n.1088', 'mai', '2019', NULL, NULL, 'ex.1', 'p.82', 1, '10.00', 0, '003.', NULL, 1),
(2133, '2022-01-01', '***', 'Revista Época', NULL, '302.2324 R', NULL, 'São Paulo', 'Globo', 'n.1089', 'mai', '2019', NULL, NULL, 'ex.1', 'p.82', 1, '10.00', 0, '003.', NULL, 1),
(2134, '2022-01-01', '***', 'Revista Época', NULL, '302.2324 R', NULL, 'São Paulo', 'Globo', 'n.1090', 'mai', '2019', NULL, NULL, 'ex.1', 'p.82', 1, '10.00', 0, '003.', NULL, 1),
(2135, '2022-01-01', '***', 'Revista Época', NULL, '302.2324 R', NULL, 'São Paulo', 'Globo', 'n.1091', 'jun', '2019', NULL, NULL, 'ex.1', 'p.82', 1, '10.00', 0, '003.', NULL, 1),
(2136, '2022-01-01', '***', 'Revista Época', NULL, '302.2324 R', NULL, 'São Paulo', 'Globo', 'n.1093', 'jun', '2019', NULL, NULL, 'ex.1', 'p.82', 1, '10.00', 0, '003.', NULL, 1),
(2137, '2022-01-01', '***', 'Revista Época', NULL, '302.2324 R', NULL, 'São Paulo', 'Globo', 'n.1094', 'jul', '2019', NULL, NULL, 'ex.1', 'p.82', 1, '10.00', 0, '003.', NULL, 1),
(2138, '2022-01-01', '***', 'Revista Época', NULL, '302.2324 R', NULL, 'São Paulo', 'Globo', 'n.1095', 'jul', '2019', NULL, NULL, 'ex.1', 'p.82', 1, '10.00', 0, '003.', NULL, 1),
(2139, '2022-01-01', '***', 'Revista Época', NULL, '302.2324 R', NULL, 'São Paulo', 'Globo', 'n.1096', 'jul', '2019', NULL, NULL, 'ex.1', 'p.82', 1, '10.00', 0, '003.', NULL, 1),
(2140, '2022-01-01', '***', 'Revista Época', NULL, '302.2324 R', NULL, 'São Paulo', 'Globo', 'n.1102', 'ago', '2019', NULL, NULL, 'ex.1', 'p.82', 1, '10.00', 0, '003.', NULL, 1),
(2141, '2022-01-01', '***', 'Revista Época', NULL, '302.2324 R', NULL, 'São Paulo', 'Globo', 'n.1103', 'ago', '2019', NULL, NULL, 'ex.1', 'p.82', 1, '10.00', 0, '003.', NULL, 1),
(2142, '2022-01-01', '***', 'Revista Época', NULL, '302.2324 R', NULL, 'São Paulo', 'Globo', 'n.1106', 'set', '2019', NULL, NULL, 'ex.1', 'p.82', 1, '10.00', 0, '003.', NULL, 1),
(2143, '2022-01-01', '***', 'Revista Época', NULL, '302.2324 R', NULL, 'São Paulo', 'Globo', 'n.1122', 'jan', '2020', NULL, NULL, 'ex.1', 'p.82', 1, '10.00', 0, '003.', NULL, 1),
(2144, '2022-01-01', '***', 'Revista Brasileiros', NULL, '302.2324 R', NULL, 'São Paulo', '***', 'n.92', 'mar', '2015', NULL, NULL, 'ex.1', 'p.114', 1, '10.00', 0, '003.', NULL, 1),
(2145, '2022-01-01', '***', 'Revista Scientific American', NULL, '302.2324 R', '1807-1562', 'São Paulo', '***', 'n.21', NULL, NULL, NULL, NULL, 'ex.1', 'p.90', 1, '10.00', 0, '003.', NULL, 1),
(2146, '2022-01-01', '***', 'Revista Exame', NULL, '302.2324 R', '0102-2881', 'São Paulo', '***', 'n.9', 'mai', '2013', NULL, 'v. 1041', 'ex.1', 'p.210', 1, '10.00', 0, '003.', NULL, 1),
(2147, '2022-01-01', '***', 'Revista Exame', NULL, '302.2324 R', '0102-2881', 'São Paulo', '***', 'n.4', 'Março', '2014', NULL, 'v. 1060', 'ex.1', 'p.130', 1, '10.00', 0, '003.', NULL, 1),
(2148, '2022-01-01', '***', 'Revista Exame', NULL, '302.2324 R', '977.010228800.2', 'São Paulo', '***', 'n.9', 'maio', '2014', NULL, 'v. 1065', 'ex.1', 'p.194', 1, '10.00', 0, '003.', NULL, 1),
(2149, '2022-01-01', '***', 'Revista Super Interessante Especial: Coleção Grandes Guerras', NULL, '302.2324 R', '977.010228800.2', 'São Paulo', 'Abril Editora', NULL, 'out', '2004', NULL, NULL, 'ex.1', 'p.82', 1, '10.00', 0, '003.', NULL, 1),
(2150, '2022-01-01', 'Instituto Ayrton Senna', 'Revista A Mídia dos jovens', NULL, '302.2324 R', '1519-5384', 'São Paulo', '***', 'n.8', NULL, '2001', NULL, NULL, 'ex.1', 'p.44', 1, '10.00', 0, '003.', NULL, 1),
(2151, '2022-01-01', '***', 'Revista Ciência Criminal', NULL, '302.2324 R', '1809-9068', 'São Paulo', 'Segmento Editora', 'n.4', NULL, '2008', NULL, NULL, 'ex.1', 'p.58', 1, '10.00', 0, '003.', NULL, 1),
(2152, '2022-01-01', '***', 'Revista Ciência e Vida', NULL, '302.2324 R', '1982-2448', 'São Paulo', 'Escala Editora', 'n.7', NULL, NULL, NULL, NULL, 'ex.1', 'p.82', 1, '10.00', 0, '003.', NULL, 1),
(2153, '2022-01-01', '***', 'Revista Veja Luxo', NULL, '302.2324 R', '1982-2448', 'São Paulo', 'Abril Editora', NULL, 'ago', '2012', NULL, NULL, 'ex.1', 'p.226', 1, '10.00', 0, '003.', NULL, 1),
(2154, '2022-01-01', 'Câmara Municipal de Barueri', 'Cartilha do Sistema de Gestão Ambiental', NULL, '028.5 R', NULL, 'Barueri-SP', 'Câmara ', NULL, NULL, NULL, NULL, NULL, 'ex.1', 'p.15', 1, NULL, 1, '003.', NULL, 1),
(2155, '2022-01-01', 'Sandra Sarué e Marcelo Boffa', 'E se fosse com você?', NULL, '028.5 R', '978.85.06.057.46.9', 'São Paulo', 'Melhoramentos Editora', NULL, NULL, '2007', NULL, NULL, 'ex.1', 'p.39', 1, NULL, 1, '003.', NULL, 1),
(2156, '2022-01-01', 'Brasil', 'Série por dentro do assunto: Drogas: Cartilha álcool e jovens', NULL, '613.81.053.6 R', NULL, 'Brasília-DF', 'Secretarias ', NULL, NULL, '2010', NULL, NULL, 'ex.1', 'p.40', 1, NULL, 1, '003.', NULL, 1),
(2157, '2022-01-01', 'Mauricio de Souza', 'Turma da Mônica em Sede de vitória', NULL, '028.5 R', NULL, 'São Paulo', 'Mauricío de Souza Editora', NULL, NULL, NULL, NULL, NULL, 'ex.1', 'p.19', 1, NULL, 1, '003.', NULL, 1),
(2158, '2022-01-01', 'Mauricio de Souza', 'Turma da Mônica em Segurança no trasporte rodoviário', NULL, '028.5 R', NULL, 'São Paulo', 'Mauricío de Souza Editora', NULL, NULL, NULL, NULL, NULL, 'ex.1', 'p.19', 1, NULL, 1, '003.', NULL, 1),
(2159, '2022-01-01', 'Ziraldo', 'Eu Senadoro um passeio', NULL, '028.5 R', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, NULL, NULL, NULL, 'ex.1', 'p.33', 1, NULL, 1, '003.', NULL, 1),
(2160, '2022-01-01', 'Ziraldo', 'Eu Senadoro um passeio', NULL, '028.5 R', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, NULL, NULL, NULL, 'ex.2', 'p.33', 1, NULL, 1, '003.', NULL, 1),
(2161, '2022-01-01', 'Ziraldo', 'Eu Senadoro um passeio', NULL, '028.5 R', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, NULL, NULL, NULL, 'ex.3', 'p.33', 1, NULL, 1, '003.', NULL, 1),
(2162, '2022-01-01', 'Ziraldo', 'Eu Senadoro um passeio', NULL, '028.5 R', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, NULL, NULL, NULL, 'ex.4', 'p. 33', 1, NULL, 1, '003.', NULL, 1),
(2163, '2022-01-01', 'Câmara dos Deputados', 'Revista Plenarinho: O jeito criança de ser cidadão - Xô intolerância', NULL, '342.05 R', '2178-7417', 'Brasília-DF', 'Câmara dos Deputados', 'n. 1', NULL, '2018', NULL, NULL, 'ex.1', 'p. 24', 1, NULL, 1, '003.', NULL, 1),
(2164, '2022-01-01', 'Câmara dos Deputados', 'Revista Plenarinho: O jeito criança de ser cidadão - Xô intolerância', NULL, '342.05 R', '2178-7417', 'Brasília-DF', 'Câmara dos Deputados', 'n.1', NULL, '2018', NULL, NULL, 'ex.2', 'p. 24', 1, NULL, 1, '003.', NULL, 1),
(2165, '2022-01-01', 'Câmara dos Deputados', 'Revista Plenarinho: O jeito criança de ser cidadão - Xô intolerância', NULL, '342.05 R', '2178-7417', 'Brasília-DF', 'Câmara dos Deputados', 'n.1', NULL, '2018', NULL, NULL, 'ex.3', 'p. 24', 1, NULL, 1, '003.', NULL, 1),
(2166, '2022-01-01', 'Câmara dos Deputados', 'Revista Plenarinho: O jeito criança de ser cidadão - Xô intolerância', NULL, '342.05 R', '2178-7417', 'Brasília-DF', 'Câmara dos Deputados', 'n.1', NULL, '2018', NULL, NULL, 'ex.4', 'p. 24', 1, NULL, 1, '003.', NULL, 1),
(2167, '2022-01-01', 'Câmara dos Deputados', 'Revista Plenarinho: Séries ação de cidadania - Eca em tirinhas para crianças', NULL, '342.05 R', NULL, 'Brasília-DF', 'Câmara dos Deputados', 'n.21', NULL, '2015', NULL, NULL, 'ex.1', 'p. 34', 1, NULL, 1, '003.', NULL, 1),
(2168, '2022-01-01', 'Câmara dos Deputados', 'Revista Plenarinho: Séries ação de cidadania - Eca em tirinhas para crianças', NULL, '342.05 R', NULL, 'Brasília-DF', 'Câmara dos Deputados', 'n.21', NULL, '2015', NULL, NULL, 'ex.2', 'p. 34', 1, NULL, 1, '003.', NULL, 1),
(2169, '2022-01-01', 'Câmara dos Deputados', 'Revista Plenarinho: Séries ação de cidadania - Eca em tirinhas para crianças', NULL, '342.05 R', NULL, 'Brasília-DF', 'Câmara dos Deputados', 'n.21', NULL, '2015', NULL, NULL, 'ex.3', 'p. 34', 1, NULL, 1, '003.', NULL, 1),
(2170, '2022-01-01', 'Câmara dos Deputados', 'Revista Plenarinho: Séries ação de cidadania - Eca em tirinhas para crianças', NULL, '342.05 R', NULL, 'Brasília-DF', 'Câmara dos Deputados', 'n.21', NULL, '2015', NULL, NULL, 'ex.4', 'p. 34', 1, NULL, 1, '003.', NULL, 1),
(2171, '2022-01-01', 'Câmara dos Deputados', 'Revista Plenarinho: O jeito criança de ser cidadão - Uma aventura para ajudar o planeta', NULL, '342.05 R', NULL, 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, NULL, NULL, NULL, 'ex.1', 'p. 18', 1, NULL, 1, '003.', NULL, 1),
(2172, '2022-01-01', 'Câmara dos Deputados', 'Revista Plenarinho: O jeito criança de ser cidadão - Uma aventura para ajudar o planeta', NULL, '342.05 R', NULL, 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, NULL, NULL, NULL, 'ex.2', 'p. 18', 1, NULL, 1, '003.', NULL, 1),
(2173, '2022-01-01', 'Câmara dos Deputados', 'Revista Plenarinho: O jeito criança de ser cidadão - Uma aventura para ajudar o planeta', NULL, '342.05 R', NULL, 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, NULL, NULL, NULL, 'ex.3', 'p. 18', 1, NULL, 1, '003.', NULL, 1),
(2174, '2022-01-01', 'Câmara dos Deputados', 'Revista Plenarinho: O jeito criança de ser cidadão - Uma aventura para ajudar o planeta', NULL, '342.05 R', NULL, 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, NULL, NULL, NULL, 'ex.4', 'p. 18', 1, NULL, 1, '003.', NULL, 1),
(2175, '2022-01-01', 'Câmara dos Deputados', 'Revista Plenarinho: O jeito criança de ser cidadão - Uma aventura para ajudar o planeta', NULL, '342.05 R', NULL, 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, NULL, NULL, NULL, 'ex. 5', 'p. 18', 1, NULL, 1, '003.', NULL, 1),
(2176, '2022-01-01', 'Câmara dos Deputados', 'Revista Plenarinho: O jeito criança de ser cidadão - Turma do plenarinho conta o Bullying', NULL, '342.05 R', NULL, 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '7', NULL, NULL, 'ex.1', 'p.16', 1, NULL, 1, '003.', NULL, 1),
(2177, '2022-01-01', 'Câmara dos Deputados', 'Revista Plenarinho: O jeito criança de ser cidadão - Turma do plenarinho conta o Bullying', NULL, '342.05 R', NULL, 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '7', NULL, NULL, 'ex.2', 'p.16', 1, NULL, 1, '003.', NULL, 1),
(2178, '2022-01-01', 'Câmara dos Deputados', 'Revista Plenarinho: O jeito criança de ser cidadão - Turma do plenarinho conta o Bullying', NULL, '342.05 R', NULL, 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '7', NULL, NULL, 'ex.3', 'p.16', 1, NULL, 1, '003.', NULL, 1),
(2179, '2022-01-01', 'Câmara dos Deputados', 'Revista Plenarinho: O jeito criança de ser cidadão - Turma do plenarinho conta o Bullying', NULL, '342.05 R', NULL, 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '7', NULL, NULL, 'ex.4', 'p.16', 1, NULL, 1, '003.', NULL, 1),
(2180, '2022-01-01', 'Câmara dos Deputados', 'Revista Plenarinho: O jeito criança de ser cidadão - Turma do plenarinho conta o Bullying', NULL, '342.05 R', NULL, 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '7', NULL, NULL, 'ex. 5', 'p.16', 1, NULL, 1, '003.', NULL, 1),
(2181, '2022-01-01', 'Câmara dos Deputados', 'Revista Plenarinho: O jeito criança de ser cidadão - Turma do plenarinho conta a exploração sexual', NULL, '342.05 R', NULL, 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '5', NULL, NULL, 'ex.1', 'p.16', 1, NULL, 1, '003.', NULL, 1),
(2182, '2022-01-01', 'Câmara dos Deputados', 'Revista Plenarinho: O jeito criança de ser cidadão - Turma do plenarinho conta a exploração sexual', NULL, '342.05 R', NULL, 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '5', NULL, NULL, 'ex.2', 'p.16', 1, NULL, 1, '003.', NULL, 1),
(2183, '2022-01-01', 'Câmara dos Deputados', 'Revista Plenarinho: O jeito criança de ser cidadão - Turma do plenarinho conta a exploração sexual', NULL, '342.05 R', NULL, 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '5', NULL, NULL, 'ex.3', 'p.16', 1, NULL, 1, '003.', NULL, 1),
(2184, '2022-01-01', 'Câmara dos Deputados', 'Revista Plenarinho: O jeito criança de ser cidadão - Turma do plenarinho conta a exploração sexual', NULL, '342.05 R', NULL, 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '5', NULL, NULL, 'ex.4', 'p.16', 1, NULL, 1, '003.', NULL, 1),
(2185, '2022-01-01', 'Câmara dos Deputados', 'Revista Plenarinho: O jeito criança de ser cidadão - Turma do plenarinho conta a exploração sexual', NULL, '342.05 R', NULL, 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '5', NULL, NULL, 'ex. 5', 'p.16', 1, NULL, 1, '003.', NULL, 1),
(2186, '2022-01-01', 'Câmara dos Deputados', 'Revista Plenarinho: O jeito criança de ser cidadão - Uma aventura democrática', NULL, '342.05 R', '2178-7417', 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, NULL, NULL, NULL, 'ex.1', 'p.36', 1, NULL, 1, '003.', NULL, 1),
(2187, '2022-01-01', 'Câmara dos Deputados', 'Revista Plenarinho: O jeito criança de ser cidadão - Uma aventura democrática', NULL, '342.05 R', '2178-7417', 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, NULL, NULL, NULL, 'ex.2', 'p.36', 1, NULL, 1, '003.', NULL, 1),
(2188, '2022-01-01', 'Câmara dos Deputados', 'Revista Plenarinho: O jeito criança de ser cidadão - Uma aventura democrática', NULL, '342.05 R', '2178-7417', 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, NULL, NULL, NULL, 'ex.3', 'p.36', 1, NULL, 1, '003.', NULL, 1),
(2189, '2022-01-01', 'Câmara dos Deputados', 'Revista Plenarinho: O jeito criança de ser cidadão - Uma aventura democrática', NULL, '342.05 R', '2178-7417', 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, NULL, NULL, NULL, 'ex.4', 'p.36', 1, NULL, 1, '003.', NULL, 1),
(2190, '2022-01-01', 'Câmara dos Deputados', 'Revista Plenarinho: O jeito criança de ser cidadão - Uma aventura democrática', NULL, '342.05 R', '2178-7417', 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, NULL, NULL, NULL, 'ex. 5', 'p.36', 1, NULL, 1, '003.', NULL, 1),
(2191, '2022-01-01', '**', 'Salve o meu ambiente', NULL, '342.05 R', NULL, 'Brasília-DF', 'Seleções Reader\'s Digest', NULL, NULL, NULL, NULL, NULL, 'ex.1', 'p.32', 1, NULL, 1, '003.', NULL, 1),
(2192, '2022-01-01', 'Instituto Sidarta', 'Programa Eu escrevo, tu escreves, nós mudamos', NULL, '028.5 R', NULL, 'Cotia-SP', 'Instituto Sidarta', NULL, NULL, '2011', NULL, NULL, 'ex.1', 'p.248', 1, NULL, 1, '003.', NULL, 1),
(2193, '2022-01-01', 'Câmara dos Deputados', 'Verdade ou consequencia: deputados e cidadãos', NULL, '028.5 R', NULL, 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, NULL, NULL, NULL, 'ex.1', 'p.42', 1, NULL, 1, '003.', NULL, 1),
(2194, '2022-01-01', 'Jarbas Bezerra', 'Cidadania A-Z', NULL, '342.8 R', NULL, 'Natal-RN', 'RN Econômico', NULL, NULL, '2013', NULL, NULL, 'ex.1', 'p.92', 1, NULL, 1, '003.', NULL, 1),
(2195, '2022-01-01', 'Maria de Lourdes Zemel', 'Liberdade é poder decidir: uso de drogas', NULL, '362.29 R', NULL, 'São Paulo', 'FTD', NULL, NULL, '2000', NULL, NULL, 'ex.1', 'p.56', 1, NULL, 1, '003.', NULL, 1),
(2196, '2022-01-01', 'Assembleia Legilativa do Espírito Santo', 'Escola do Legislativo', NULL, '362.29 R', NULL, 'São Paulo', 'Assembleia Legilativa do Espírito Santo', NULL, NULL, NULL, NULL, NULL, 'ex.1', 'p.25', 1, NULL, 1, '003.', NULL, 1),
(2197, '2022-01-01', 'Sandra Clara Barzan', 'A Câmara e você: Cidade de Taboão da Serra', NULL, '362.29 R', '978.85.63815.67.5', 'São Paulo', 'Galeria das Letras', NULL, NULL, '2015', NULL, NULL, 'ex.1', 'p.35', 1, NULL, 1, '003.', NULL, 1),
(2198, '2022-01-01', 'Ministério da Cultura', 'O minstério da Cultura em 1986', NULL, '980 R', NULL, 'Rio de Janeiro ', 'MINISTÉRIOS', NULL, NULL, NULL, NULL, NULL, 'ex.1', 'p.65', 1, NULL, 1, '003.', NULL, 1),
(2199, '2022-01-01', 'UFAM - Universidade Federal do Amazônas', 'Pesquisa na Amazônia', NULL, '900 R', NULL, 'Manaus-AM', 'UNIVERSIDADES', NULL, NULL, '2019', NULL, NULL, 'ex.1', 'p.23', 1, NULL, 1, '003.', NULL, 1),
(2200, '2022-01-01', '**', 'Kibutz - Cooperativa Agrícola ', NULL, '900 R', NULL, '**', '**', NULL, NULL, '1982', NULL, NULL, 'ex.1', 'p.16', 1, NULL, 1, '003.', NULL, 1),
(2201, '2022-01-01', '**', 'Perguntas e Respostas: Arrecadação - ECAD, direitos autorais', NULL, '340 R', NULL, '**', 'ECAD', NULL, NULL, NULL, NULL, NULL, 'ex.1', 'p.24', 1, NULL, 1, '003.', NULL, 1),
(2202, '2022-01-01', 'Governo do Estado de São Paulo', 'Símbolos Nacionais do Brasil', NULL, '900 R', NULL, 'São Paulo', 'MINISTÉRIOS', NULL, NULL, '1972', NULL, NULL, 'ex.1', 'p.45', 1, NULL, 1, '003.', NULL, 1),
(2203, '2022-01-01', '**', 'Salve a Selva', NULL, '900 R', NULL, '**', '**', NULL, NULL, NULL, NULL, NULL, 'ex.1', 'p.32', 1, NULL, 1, '003.', NULL, 1),
(2204, '2022-01-01', 'Escola do Legislativo de Alto Alegre -RR', 'Projeto Inclusão social escolar libras - Linguagem brasileira de sinais', NULL, '419 R ', NULL, '**', 'Assembleia Legislativa de Roraima', NULL, NULL, '2019', NULL, NULL, 'ex.1', 'p.22', 1, NULL, 1, '003.', NULL, 1);
INSERT INTO `librarycollection` (`id`, `registrationDate`, `author`, `title`, `cdu`, `cdd`, `isbn`, `local`, `publisher_edition`, `number`, `month`, `year`, `edition`, `volume`, `copyNumber`, `pageNumber`, `typeAcquisitionId`, `price`, `prohibitedSale`, `provider`, `exclusionInfoTerm`, `registeredByUserId`) VALUES
(2205, '2022-01-01', 'Senado Federal', 'Pacto Federativo - encontro com governadores ', NULL, '342.07 R', NULL, 'Brasília-DF', 'Senado Federal', NULL, 'maio', '2015', NULL, NULL, 'ex.1', 'p.47', 1, NULL, 1, '003.', NULL, 1),
(2206, '2022-01-01', '**', 'Série instrodutória Mercados Derivados', NULL, '342.07 R', NULL, '**', 'BM&F Brasil', NULL, NULL, NULL, NULL, NULL, 'ex.1', 'p.63', 1, NULL, 1, '003.', NULL, 1),
(2207, '2022-01-01', 'Iara Bernardi - Deputada Federal', 'Assédio Sexual é crime e precisa ser punido', NULL, '342.07 R', NULL, 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '2001', NULL, NULL, 'ex.1', 'p.39', 1, NULL, 1, '003.', NULL, 1),
(2208, '2022-01-01', 'Rodrigues Coelho', 'Catálogo de Obras de Rodrigues Coelho', NULL, '750 R', NULL, 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '2001', NULL, NULL, 'ex.1', 'p.39', 1, NULL, 1, '003.', NULL, 1),
(2209, '2022-01-01', 'Sesi-SP', 'Leonardo da Vinci - a Natureza da invenção', NULL, '750 R', NULL, 'Brasília-DF', 'Sesi-SP', NULL, NULL, '2014', NULL, NULL, 'ex.1', 'p.14', 1, NULL, 1, '003.', NULL, 1),
(2210, '2022-01-01', 'Secretaria do Meio Ambiente do Estado de SP', 'Município Verde Azul, A responsabilidade do Legislativo Local - 50 ideias', NULL, '348 R', NULL, 'São Paulo', 'Secretarias ', NULL, NULL, NULL, NULL, NULL, 'ex.1', 'p.46', 1, NULL, 1, '003.', NULL, 1),
(2211, '2022-01-01', 'Secretaria Especial de Direitos Humanos', 'Convenção sobre os Direitos das Pessoas com deficiência', NULL, '348 R', NULL, 'Brasília-DF', 'Secretarias ', NULL, NULL, '2007', NULL, NULL, 'ex.1', 'p.48', 1, NULL, 1, '003.', NULL, 1),
(2212, '2022-01-01', 'Controladoria Geral da União (CGU)', 'Coleção OGU: Orientações para implantação de uma unidade de ouvidoria: rumo ao sistema participativo - Volume 1', NULL, '348 R', NULL, 'Brasília-DF', 'Secretarias ', NULL, NULL, NULL, NULL, NULL, 'ex.1', 'p.26', 1, NULL, 1, '003.', NULL, 1),
(2213, '2022-01-01', 'Controladoria Geral da União (CGU)', 'Coleção OGU: Orientações para implantação da lei de acesso à informação nas ouvidorias públicas - Volume 2', NULL, '348 R', NULL, 'Brasília-DF', 'Secretarias ', NULL, NULL, NULL, NULL, NULL, 'ex.1', 'p.74', 1, NULL, 1, '003.', NULL, 1),
(2214, '2022-01-01', 'Controladoria Geral da União (CGU)', 'Coleção OGU: Orientações para o atendimento ao cidadão nas ouvidorias públicas - Volume 3', NULL, '348 R', NULL, 'Brasília-DF', 'Secretarias ', NULL, NULL, NULL, NULL, NULL, 'ex.1', 'p.58', 1, NULL, 1, '003.', NULL, 1),
(2215, '2022-01-01', 'Senado Federal', 'SNCI - Proposta para a construção de um sistema Nacional de Conhecimento e inovação', NULL, '328 R', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2012', NULL, NULL, 'ex.1', 'p.44', 1, NULL, 1, '003.', NULL, 1),
(2216, '2022-01-01', '**', 'Guia de Carreiras jurídicas', NULL, '340.1 R', NULL, 'Brasília-DF', '**', NULL, NULL, NULL, NULL, NULL, 'ex.1', 'p.30', 1, NULL, 1, '003.', NULL, 1),
(2217, '2022-01-01', 'MAGISTRATURA, Escola Paulista de', 'Cadernos Jurídicos - Ano 4, Número 14', NULL, '340.1 R', '85.7060.171.9', 'São Paulo', 'imprensa oficial', 'n.14', NULL, '2003', NULL, NULL, 'ex.1', 'p.109', 1, NULL, 1, '003.', NULL, 1),
(2218, '2022-01-01', 'MAGISTRATURA, Escola Paulista de', 'Cadernos Jurídicos - Ano 4, Número 15', NULL, '340.1 R', '85.7060.184.0', 'São Paulo', 'imprensa oficial', 'n. 15', 'maio-junho', '2003', NULL, NULL, 'ex.1', 'p.133', 1, NULL, 1, '003.', NULL, 1),
(2219, '2022-01-01', 'MAGISTRATURA, Escola Paulista de', 'Cadernos Jurídicos - Ano 4, Número 16', NULL, '340.1 R', '85.7060.185.9', 'São Paulo', 'imprensa oficial', 'n. 16', 'julho-agosto', '2003', NULL, NULL, 'ex.1', 'p.127', 1, NULL, 1, '003.', NULL, 1),
(2220, '2022-01-01', 'MAGISTRATURA, Escola Paulista de', 'Cadernos Jurídicos - Ano 5, Número 20', NULL, '340.1 R', '85.7060.250.2', 'São Paulo', 'imprensa oficial', 'n.20', 'março-abril', '2004', NULL, NULL, 'ex.1', 'p.110', 1, NULL, 1, '003.', NULL, 1),
(2221, '2022-01-01', 'MAGISTRATURA, Escola Paulista de', 'Cadernos Jurídicos - Ano 5, Número 21', NULL, '340.1 R', '1806-5449', 'São Paulo', 'imprensa oficial', 'n.21', 'maio-junho', '2004', NULL, NULL, 'ex.1', 'p.125', 1, NULL, 1, '003.', NULL, 1),
(2222, '2022-01-01', 'MAGISTRATURA, Escola Paulista de', 'Cadernos Jurídicos - Ano 5, Número 22', NULL, '340.1 R', '1806-5449', 'São Paulo', 'imprensa oficial', 'n.22', 'julho-agosto', '2004', NULL, NULL, 'ex.1', 'p.126', 1, NULL, 1, '003.', NULL, 1),
(2223, '2022-01-01', 'MAGISTRATURA, Escola Paulista de', 'Cadernos Jurídicos - Ano 5, Número 23', NULL, '340.1 R', '1806-5449', 'São Paulo', 'imprensa oficial', 'n. 23', 'set-out', '2004', NULL, NULL, 'ex.1', 'p.143', 1, NULL, 1, '003.', NULL, 1),
(2224, '2022-01-01', 'MAGISTRATURA, Escola Paulista de', 'Cadernos Jurídicos - Ano 5, Número 24', NULL, '340.1 R', '1806-5449', 'São Paulo', 'imprensa oficial', 'n. 24', 'nov-dez', '2004', NULL, NULL, 'ex.1', 'p.141', 1, NULL, 1, '003.', NULL, 1),
(2225, '2022-01-01', 'Escola do Parlamento da Câmara de São Paulo', 'Projeto Reporter do Futuro: Cursos de Complementação Universitária - n.1', NULL, '070 R', NULL, 'São Paulo', 'imprensa oficial', 'n. 1', NULL, '2016', NULL, NULL, 'ex.1', 'p.106', 1, NULL, 1, '003.', NULL, 1),
(2226, '2022-01-01', 'Escola do Legislativo do Espírito Santo', 'Jornal Escola do Legislativo - Biênio 2017-2018', NULL, '342.05 R', NULL, 'Espirito Santo', 'Câmara Muncipal ', NULL, NULL, '2017/2018', NULL, 'v. 1', 'ex.1', 'p. 15', 1, NULL, 1, '003.', NULL, 1),
(2227, '2022-01-01', 'CENPEC - Centro de estudos e pesquisas em educação, cultura e açõa comunitária', 'Formação em serviço - Guia de apoio às ações do Secretário da Educação: Qualidade de ensino e formação de professores', NULL, '379 R', NULL, 'São Paulo', 'Cenpec', NULL, NULL, NULL, NULL, 'v. 1', 'ex.1', 'p. 8', 1, NULL, 1, '003.', NULL, 1),
(2228, '2022-01-01', 'CENPEC - Centro de estudos e pesquisas em educação, cultura e açõa comunitária', 'Formação em serviço - Guia de apoio às ações do Secretário da Educação: Formação dos dirigentes educacionais', NULL, '379 R', NULL, 'São Paulo', 'Cenpec', NULL, NULL, NULL, NULL, 'v. 2', 'ex.1', 'p. 20', 1, NULL, 1, '003.', NULL, 1),
(2229, '2022-01-01', 'CENPEC - Centro de estudos e pesquisas em educação, cultura e açõa comunitária', 'Formação em serviço - Guia de apoio às ações do Secretário da Educação: Gestão de programas de formação', NULL, '379 R', NULL, 'São Paulo', 'Cenpec', NULL, NULL, NULL, NULL, 'v. 3', 'ex.1', 'p. 24', 1, NULL, 1, '003.', NULL, 1),
(2230, '2022-01-01', 'CENPEC - Centro de estudos e pesquisas em educação, cultura e açõa comunitária', 'Formação em serviço - Guia de apoio às ações do Secretário da Educação: Formação para professores leigos', NULL, '379 R', NULL, 'São Paulo', 'Cenpec', NULL, NULL, NULL, NULL, 'v. 4', 'ex.1', 'p. 20', 1, NULL, 1, '003.', NULL, 1),
(2231, '2022-01-01', 'CENPEC - Centro de estudos e pesquisas em educação, cultura e açõa comunitária', 'Formação em serviço - Guia de apoio às ações do Secretário da Educação: Papel da tv na formação de educadores', NULL, '379 R', NULL, 'São Paulo', 'Cenpec', NULL, NULL, NULL, NULL, 'v. 5', 'ex.1', 'p. 20', 1, NULL, 1, '003.', NULL, 1),
(2232, '2022-01-01', 'CENPEC - Centro de estudos e pesquisas em educação, cultura e açõa comunitária', 'Formação em serviço - Guia de apoio às ações do Secretário da Educação: A escola como foco de ação', NULL, '379 R', NULL, 'São Paulo', 'Cenpec', NULL, NULL, NULL, NULL, 'v. 6', 'ex.1', 'p. 20', 1, NULL, 1, '003.', NULL, 1),
(2233, '2022-01-01', 'CENPEC - Centro de estudos e pesquisas em educação, cultura e açõa comunitária', 'Formação em serviço - Guia de apoio às ações do Secretário da Educação: Recursos didáticos para a formação em serviço', NULL, '379 R', NULL, 'São Paulo', 'Cenpec', NULL, NULL, NULL, NULL, 'v. 7', 'ex.1', 'p. 14', 1, NULL, 1, '003.', NULL, 1),
(2234, '2022-01-01', 'CENPEC - Centro de estudos e pesquisas em educação, cultura e açõa comunitária', 'Formação em serviço - Guia de apoio às ações do Secretário da Educação: Formação em serciço: problemas e soluções', NULL, '379 R', NULL, 'São Paulo', 'Cenpec', NULL, NULL, NULL, NULL, 'v. 8', 'ex.1', 'p. 20', 1, NULL, 1, '003.', NULL, 1),
(2235, '2022-01-01', 'CENPEC - Centro de estudos e pesquisas em educação, cultura e açõa comunitária', 'Formação em serviço - Guia de apoio às ações do Secretário da Educação: A profissionalização da docencia ', NULL, '379 R', NULL, 'São Paulo', 'Cenpec', NULL, NULL, NULL, NULL, 'v. 9', 'ex.1', 'p. 12', 1, NULL, 1, '003.', NULL, 1),
(2236, '2022-01-01', 'CENPEC - Centro de estudos e pesquisas em educação, cultura e açõa comunitária', 'Formação em serviço - Guia de apoio às ações do Secretário da Educação: Registro das experiências', NULL, '379 R', NULL, 'São Paulo', 'Cenpec', NULL, NULL, NULL, NULL, 'v. 11', 'ex.1', 'p. 92', 1, NULL, 1, '003.', NULL, 1),
(2237, '2022-01-01', 'Museu Oscar Niemeyer', 'Folheto Nemer - Aquarelas recentes - Geometria residual', NULL, '750 R', NULL, 'Curitiba-PR', '**', NULL, NULL, NULL, NULL, 'v. 1', 'ex.1', 'p. 8', 1, NULL, 1, '003.', NULL, 1),
(2238, '2022-01-01', 'Museu Oscar Niemeyer', 'Folheto Cinco Elementos - Juliane Fuganti', NULL, '750 R', NULL, 'Curitiba-PR', '**', NULL, NULL, NULL, NULL, 'v. 1', 'ex.1', 'p. 8', 1, NULL, 1, '003.', NULL, 1),
(2239, '2022-01-01', 'Museu Oscar Niemeyer', 'Folheto Pierre Verger', NULL, '779 R', NULL, 'Curitiba-PR', '**', NULL, NULL, NULL, NULL, 'v. 1', 'ex.1', 'p. 8', 1, NULL, 1, '003.', NULL, 1),
(2240, '2022-01-01', 'Museu Oscar Niemeyer', 'Folheto Cinco Elementos - Luz / Matéria - Acervo do museu', NULL, '730 R', NULL, 'Curitiba-PR', '**', NULL, NULL, NULL, NULL, 'v. 1', 'ex.1', 'p. 8', 1, NULL, 1, '003.', NULL, 1),
(2241, '2022-01-01', '***', 'A trajetória política de Roberto Freire', NULL, '921 R', NULL, 'São Paulo', '**', NULL, NULL, NULL, NULL, 'v. 1', 'ex.1', 'p. 44', 1, NULL, 1, '003.', NULL, 1),
(2242, '2022-01-01', '***', 'Acesso à Informação Pública: uma introdução à Lei nº 12.527 de 18 de novembro de 2011', NULL, '348 R', NULL, 'São Paulo', '**', NULL, NULL, '2011', NULL, 'v. 1', 'ex.1', 'p. 24', 1, NULL, 1, '003.', NULL, 1),
(2243, '2022-01-01', 'Assembleia Legislativa do Rio Grande do Sul', 'Relatório final da comissão especial Rio grandense Resiliente', NULL, '342.03 R', NULL, 'Rio Grande do Sul', 'Assembleias Legislativas', NULL, NULL, '2016', NULL, 'v. 1', 'ex.1', 'p. 96', 1, NULL, 1, '003.', NULL, 1),
(2244, '2022-01-01', 'Corregedoria Geral da adminsitração de São Paulo', 'A ouvidoria no Governo do Estado de São Paulo', NULL, '348 R', NULL, 'São Paulo', 'Corregedoria do Estado de SP', NULL, NULL, '2013', NULL, 'v. 1', 'ex.1', 'p. 48', 1, NULL, 1, '003.', NULL, 1),
(2245, '2022-01-01', 'Marcos Camargo Capagnone', 'Parlamento Transparente: sistema de avaliação de desempenho do parlamento', NULL, '328 R', NULL, 'São Paulo', 'Cepam', NULL, NULL, '2003', NULL, NULL, 'ex.1', 'p. 84', 1, NULL, 1, '003.', NULL, 1),
(2246, '2022-01-01', 'Escola do Parlamento de São Paulo', 'Relatório de atividades 2015-2016', NULL, '342.05 R', NULL, 'São Paulo', 'Câmara Muncipal ', NULL, 'dez', '2016', NULL, NULL, 'ex.1', 'p. 82', 1, NULL, 1, '003.', NULL, 1),
(2247, '2022-01-01', 'Câmara Municipal de São Paulo', 'Apartes', NULL, '342.05 R', NULL, 'São Paulo', 'Abril Editora', 'n. 17', 'out - dez', '2015', NULL, NULL, 'ex.1', 'p. 42', 1, '10.00', 0, '003.', NULL, 1),
(2248, '2022-01-01', 'Câmara Municipal de São Paulo', 'Apartes', NULL, '342.05 R', NULL, 'São Paulo', 'Abril Editora', 'n. 19', 'març - abr', '2016', NULL, NULL, 'ex.1', 'p. 42', 1, '10.00', 0, '003.', NULL, 1),
(2249, '2022-01-01', 'Câmara Municipal de São Paulo', 'Apartes', NULL, '342.05 R', NULL, 'São Paulo', 'Abril Editora', 'n. 21', 'jun - ago', '2016', NULL, NULL, 'ex.1', 'p. 34', 1, '10.00', 0, '003.', NULL, 1),
(2250, '2022-01-01', '**', 'Revista Veja São Paulo', NULL, '302.2324 R', NULL, 'São Paulo', 'Abril Editora', 'n. 26', 'junho', '2012', NULL, NULL, 'ex.1', 'p. 226', 1, '10.00', 0, '003.', NULL, 1),
(2251, '2022-01-01', '**', 'Revista Veja São Paulo', NULL, '302.2324 R', NULL, 'São Paulo', 'Abril Editora', 'n. 28', 'julho', '2012', NULL, NULL, 'ex.1', 'p. 154', 1, '10.00', 0, '003.', NULL, 1),
(2252, '2022-01-01', '**', 'Revista Veja São Paulo', NULL, '302.2324 R', NULL, 'São Paulo', 'Abril Editora', 'n. 28', 'julho', '2016', NULL, NULL, 'ex.1', 'p. 78', 1, '10.00', 0, '003.', NULL, 1),
(2253, '2022-01-01', '**', 'Revista Veja', NULL, '302.2324 R', NULL, 'São Paulo', 'Abril Editora', 'n. 35', 'ago', '2012', NULL, NULL, 'ex.1', 'p. 142', 1, '10.00', 0, '003.', NULL, 1),
(2254, '2022-01-01', '**', 'Revista Viva S/A', NULL, '302.2324 R', '1806-7433', 'São Paulo', '**', 'n. 110', 'Fev', '2011', NULL, NULL, 'ex.1', 'p. 90', 1, '10.00', 0, '003.', NULL, 1),
(2255, '2022-01-01', '**', 'Revista Itapevi', NULL, '302.2324 R', NULL, 'Itapevi-SP', 'Winners Book', NULL, NULL, '2013', NULL, NULL, 'ex.1', 'p. 98', 1, '10.00', 0, '003.', NULL, 1),
(2256, '2022-01-01', '**', 'Revista Circuito', NULL, '302.2324 R', NULL, 'Granja Viana-SP', '**', NULL, 'ago', '2010', NULL, NULL, 'ex.1', 'p. 66', 1, '10.00', 0, '003.', NULL, 1),
(2257, '2022-01-01', '**', 'Revista Investidor Internacioanl ', NULL, '302.2324 R', NULL, 'São Paulo', '**', 'n. 241', 'set', '2012', NULL, NULL, 'ex.1', 'p. 74', 1, '10.00', 0, '003.', NULL, 1),
(2258, '2022-01-01', '**', 'Revista Drone Show News e Mundo Geo', NULL, '302.2324 R', NULL, 'São Paulo', '**', NULL, 'out - nov', 'Ano 2         Ano 18', NULL, NULL, 'ex.1', 'p. 15           p. 37', 1, '10.00', 0, '003.', NULL, 1),
(2259, '2022-01-01', '**', 'Revista Negócios para Negócios', NULL, '302.2324 R', NULL, 'Cotia-SP', '**', 'n. 50', 'out', '2013', NULL, NULL, 'ex.1', 'p. 34', 1, '10.00', 0, '003.', NULL, 1),
(2260, '2022-01-01', '**', 'Revista Gazeta de Barueri e Região', NULL, '302.2324 R', NULL, 'Barueri-SP', '**', NULL, 'set', '2014', NULL, NULL, 'ex.1', 'p. 27', 1, '10.00', 0, '003.', NULL, 1),
(2261, '2022-01-01', '**', 'Super Interessante: A bíblia como você nunca viu', NULL, '341.1 R', NULL, 'São Paulo', '**', NULL, 'junho', '2012', NULL, NULL, 'ex.1', 'p. 89', 1, '10.00', 0, '003.', NULL, 1),
(2262, '2022-01-01', '**', 'Revista 22', NULL, '302.2324 R', NULL, 'São Paulo', 'FGV', 'n. 101', 'abr - maio', '2016', NULL, NULL, 'ex.1', 'p. 49', 1, '10.00', 0, '003.', NULL, 1),
(2263, '2022-01-01', '**', 'Revsita Circuito Especial de Educação', NULL, '302.2324 R', NULL, 'São Paulo', '**', NULL, 'jan - dez', NULL, NULL, NULL, 'ex.1', 'p. 74', 1, '10.00', 0, '003.', NULL, 1),
(2264, '2022-01-01', '**', 'Nogócios Públicos', NULL, '302.2324 R', '1984-2589', 'São Paulo', 'Editora Negócios', 'n. 112', 'nov', '2013', NULL, NULL, 'ex.1', 'p. 66', 1, '10.00', 0, '003.', NULL, 1),
(2265, '2022-01-01', '**', 'O pregoeito', NULL, '302.2324 R', '1984-2570', 'São Paulo', 'Editora Negócios', 'n. 117', 'agosto', '2014', NULL, NULL, 'ex.1', 'p. 46', 1, '10.00', 0, '003.', NULL, 1),
(2266, '2022-01-01', '**', 'Filantropia', NULL, '302.2324 R', '1677-1362', 'São Paulo', 'Instituto Filantropia', 'n. 76', NULL, '2017', NULL, NULL, 'ex.1', 'p. 96', 1, '10.00', 0, '003.', NULL, 1),
(2267, '2022-01-01', '**', 'Piauí', NULL, '302.2324 R', '1980-1750', 'São Paulo', '**', 'n. 131', 'agosto', '2007', NULL, NULL, 'ex. 1', 'p. 62', 1, '10.00', 0, '003.', NULL, 1),
(2268, '2022-01-01', 'Instituto Butantan', 'Caderno de História da Ciência : História Natural: a constituição de Hermann Von Ihering (1850-1930) - Vol.8 /n.1', NULL, '607 R', '1809-7634', 'São Paulo', 'Instituto Butantan', 'n.1', 'jan-jul', '2012', NULL, 'v.8', 'ex. 1', 'p.182', 1, NULL, 1, '003.', NULL, 1),
(2269, '2022-01-01', 'Instituto Butantan', 'Caderno de História da Ciência: Paulo Emílio Vanzolini - vol.9/ n.2', NULL, '607 R', '1809-7634', 'São Paulo', 'Instituto Butantan', 'n.1', 'jul-dez', '2013', NULL, 'v.9', 'ex.1', 'p.261', 1, NULL, 1, '003.', NULL, 1),
(2270, '2022-01-01', 'Instituto Butantan', 'Caderno de História da Ciência: Ciência, intelectuais e nação no Brasil - vol.9/n.2', NULL, '607 R', '1809-7634', 'São Paulo', 'Instituto Butantan', 'n.2', 'jul-dez', '2013', NULL, 'v.9', 'ex.1', 'p.139', 1, NULL, 1, '003.', NULL, 1),
(2271, '2022-01-01', 'Instituto Butantan', 'Caderno de História da Ciência: 150 anos de Vital Brasil - Vol.10/n.1', NULL, '607 R', '1809-7634', 'São Paulo', 'Instituto Butantan', 'n.1', 'jan-jun', '2014', NULL, 'v.10', 'ex.1', 'p.204', 1, NULL, 1, '003.', NULL, 1),
(2272, '2022-01-01', 'Secretaria de Saúde de São Paulo', 'Indicadores de Gestão de Pessoas - n.12', NULL, '361.6 R', NULL, 'São Paulo', 'Secretarias ', 'n.12', 'julho', '2013', NULL, NULL, 'ex.1', 'p.79', 1, NULL, 1, '003.', NULL, 1),
(2273, '2022-01-01', 'ISA- Istituto Socio Ambiental', 'Almanaque Brasil Socioambiental 2008 : uma novo perspectiva para entender a situação do Brasil e a nossa contribuição para a crise planetária', NULL, '036.9 ', '978.85.85994.45.7', '***', '***', NULL, NULL, '2008', NULL, NULL, 'ex.1', 'p. 551', 1, NULL, 1, '003.', NULL, 1),
(2274, '2022-01-01', 'Plácido Arraes (Org.)', 'Anuário 2018-2019: Ibrachina (Intituto cultural Brasil-China)', NULL, '036.9', '978.85.80444.63.2', '***', 'D\'Plácido Editora', NULL, NULL, '2019', NULL, NULL, 'ex.1', 'p. 104', 1, NULL, 1, '003.', NULL, 1),
(2275, '2022-01-01', 'Euroart Castelli - Org.', 'Exposição de Pinturas de Gustavo Rosa', NULL, '750.3 ', NULL, 'São Paulo', '***', NULL, NULL, '2001', NULL, NULL, 'ex.1', 'p.42', 1, NULL, 1, '003.', NULL, 1),
(2276, '2022-01-01', 'Brasil - Ministério da Fazenda', 'III Prêmio SEAE 2008: monografias em defesa da concorrência e regulação econômica', NULL, '060 R', '978.85.61200.02.2', 'Brasília-DF', 'Ministérios', NULL, NULL, '2009', NULL, NULL, 'ex.1', 'p.898', 1, NULL, 1, '003.', NULL, 1),
(2277, '2022-01-01', 'Sociedade Brasileira de História da Educação', 'I congresso brasileiro de história da educação: Educação no Brasil: História e historiografia', NULL, '370.9 R', NULL, 'Rio de Janeiro', 'Universidade', NULL, NULL, '2000', NULL, NULL, 'ex.1', 'p.305', 1, NULL, 1, '003.', NULL, 1),
(2278, '2022-01-01', 'Silvério Crestana (org.)', 'Guia do vereador empreendedor: políticas municipais de apoio às micro e pequenas empresas', NULL, '320 R', NULL, 'São Paulo', 'Sebrae', NULL, NULL, '2008', NULL, NULL, 'ex.1', 'p. 45', 1, NULL, 1, '003.', NULL, 1),
(2279, '2022-01-01', 'Silvério Crestana (org.)', 'Guia do vereador empreendedor: políticas municipais de apoio às micro e pequenas empresas', NULL, '320 R', NULL, 'São Paulo', 'Sebrae', NULL, NULL, '2008', NULL, NULL, 'ex.2', 'p. 45', 1, NULL, 1, '003.', NULL, 1),
(2280, '2022-01-01', 'Silvério Crestana (org.)', 'Guia do vereador empreendedor:Prefeito', NULL, '320 R', NULL, 'São Paulo', 'Sebrae', NULL, NULL, '2008', NULL, NULL, 'ex.1', 'p. 48', 1, NULL, 1, '003.', NULL, 1),
(2281, '2022-01-01', 'Silvério Crestana (org.)', 'Guia do vereador empreendedor:Prefeito', NULL, '320 R', NULL, 'São Paulo', 'Sebrae', NULL, NULL, '2008', NULL, NULL, 'ex.2', 'p. 48', 1, NULL, 1, '003.', NULL, 1),
(2282, '2022-01-01', 'ADVB', 'Revista Mercado', NULL, '659 R', NULL, 'São Paulo', 'ADVB', NULL, 'jun', '2017', NULL, NULL, 'ex. 1', 'p. 162', 1, NULL, 1, '003.', NULL, 1),
(2283, '2022-01-01', 'Vários Autores', 'Revista de Cultura Vozes: Literatura brasileira ', NULL, 'B869.03 ', NULL, '**', 'Vozes', 'n.10', 'dez', '1972', NULL, 'v.66', 'ex.1', 'p.84', 1, NULL, 1, '003.', NULL, 1),
(2284, '2022-01-01', 'Konrad-Adenauer-Stiftung', 'Stefan Zweig e o Brasil: Exílio e Integração', NULL, '921 ', '978.659.900847.4', 'Rio de Janeiro', 'Konrad Adenauer', NULL, NULL, NULL, NULL, NULL, 'ex.1', 'p.55', 1, NULL, 1, '003.', NULL, 1),
(2285, '2022-01-01', 'Luiz Eduardo Garcia Silva - Konrad-Adenauer-Stiftung', 'Perspectivas e desafios da economia brasileira no pós-pandemia', NULL, '338.9 R', '978.85.990084.4.3', 'Rio de Janeiro', 'Konrad Adenauer', NULL, NULL, '2020', NULL, NULL, 'ex.1', 'p.84', 1, NULL, 1, '003.', NULL, 1),
(2286, '2022-01-01', 'Konrad-Adenauer-Stiftung', 'Cultura do Debate na Política e no Espaço Público', NULL, '320 R', '978.85.7504.231.1', 'Rio de Janeiro', 'Konrad Adenauer', NULL, NULL, '2019', NULL, NULL, 'ex.1', 'p.50', 1, NULL, 1, '003.', NULL, 1),
(2287, '2022-01-01', 'Konrad-Adenauer-Stiftung', 'A quarta Revolução industrial: inovações, desfios e oportunidades', NULL, '320 R', '1519-0951', 'Rio de Janeiro', 'Konrad Adenauer', 'n.1', 'abril ', 'XIX - 2020', NULL, NULL, 'ex.1', 'p.214', 1, NULL, 1, '003.', NULL, 1),
(2288, '2022-01-01', 'Konrad-Adenauer-Stiftung', 'Eleições municipais e desafios de 2020', NULL, '320 R', '1519-0951', 'Rio de Janeiro', 'Konrad Adenauer', 'n.2', NULL, '2020', NULL, NULL, 'ex.1', 'p.236', 1, NULL, 1, '003.', NULL, 1),
(2289, '2022-01-01', 'Konrad-Adenauer-Stiftung', 'Soberania na atualidade', NULL, '320 R', '1519-0951', 'Rio de Janeiro', 'Konrad Adenauer', 'n.3', NULL, '2020', NULL, NULL, 'ex.1', 'p.197', 1, NULL, 1, '003.', NULL, 1),
(2290, '2022-01-01', 'Konrad-Adenauer-Stiftung', 'Participalão e instituições democráticas no combate à pandemia', NULL, '320 R', '1519-0951', 'Rio de Janeiro', 'Konrad Adenauer', 'n.4', NULL, '2020', NULL, NULL, 'ex.1', 'p.131', 1, NULL, 1, '003.', NULL, 1),
(2291, '2022-01-01', 'Konrad-Adenauer-Stiftung', 'Novo ativismo político no Brasil: os evangélicos do século XXI', NULL, '322.1 R', '978.85.7504.234.2', 'Rio de Janeiro', 'Konrad Adenauer', NULL, NULL, '2020', NULL, NULL, 'ex.1', 'p.392', 1, NULL, 1, '003.', NULL, 1),
(2292, '2022-01-01', 'Konrad-Adenauer-Stiftung', 'XVII FORTE - Conferência de Segurança Internacional: Novas Fronteiras e Soberania frente aos Desafios Globais', NULL, '320 R', '2176-297X', 'Rio de Janeiro', 'Konrad Adenauer', NULL, NULL, '2020', NULL, NULL, 'ex.1', 'p.140', 1, NULL, 1, '003.', NULL, 1),
(2293, '2022-01-01', 'Ciranda Cultural', 'Almanaque Covid-19 Dr. Duverde', NULL, '028.5 R', '978.65.5500.203.4', 'Jandira-SP', 'Ciranda Cultural', NULL, NULL, '2020', NULL, NULL, 'ex.1', 'p.32', 1, NULL, 1, '003.', NULL, 1),
(2294, '2022-01-01', 'Ciranda Cultural', 'Almanaque Covid-19 Dr. Duverde', NULL, '028.5 R', '978.65.5500.203.4', 'Jandira-SP', 'Ciranda Cultural', NULL, NULL, '2020', NULL, NULL, 'ex.2', 'p.32', 1, NULL, 1, '003.', NULL, 1),
(2295, '2022-01-01', 'Ciranda Cultural', 'Almanaque Covid-19 Dr. Duverde', NULL, '028.5 R', '978.65.5500.203.4', 'Jandira-SP', 'Ciranda Cultural', NULL, NULL, '2020', NULL, NULL, 'ex.3', 'p.32', 1, NULL, 1, '003.', NULL, 1),
(2296, '2022-01-01', 'Federação das Apaes de SP', 'Apae em destaque', NULL, '300 ', NULL, 'São Paulo', 'Feapaes-SP', 'n.25', 'dez', '2020', NULL, NULL, 'ex.1', 'p.62', 1, NULL, 1, '003.', NULL, 1),
(2297, '2022-01-01', 'UNESP - Universidade Estadual Paulista', 'UnespCiências', NULL, '607 R', NULL, 'São Paulo', 'Universidades', 'n.73', 'abril', '2016', NULL, NULL, 'ex.1', 'p. 50', 1, NULL, 1, '003.', NULL, 1),
(2298, '2022-01-01', 'UNESP - Universidade Estadual Paulista', 'UnespCiências', NULL, '607 R', NULL, 'São Paulo', 'Universidades', 'n.82', 'fev', '2017', NULL, NULL, 'ex.1', 'p. 50', 1, NULL, 1, '003.', NULL, 1),
(2299, '2022-01-01', 'UNESP - Universidade Estadual Paulista', 'UnespCiências', NULL, '607 R', NULL, 'São Paulo', 'Universidades', 'n.83', 'março', '2017', NULL, NULL, 'ex.1', 'p. 50', 1, NULL, 1, '003.', NULL, 1),
(2300, '2022-01-01', 'UNESP - Universidade Estadual Paulista', 'UnespCiências', NULL, '607 R', NULL, 'São Paulo', 'Universidades', 'n.85', 'maio', '2017', NULL, NULL, 'ex.1', 'p. 50', 1, NULL, 1, '003.', NULL, 1),
(2301, '2022-01-01', 'UNESP - Universidade Estadual Paulista', 'UnespCiências', NULL, '607 R', NULL, 'São Paulo', 'Universidades', 'n.86', 'junho', '2017', NULL, NULL, 'ex.1', 'p. 50', 1, NULL, 1, '003.', NULL, 1),
(2302, '2022-01-01', 'UNESP - Universidade Estadual Paulista', 'UnespCiências', NULL, '607 R', NULL, 'São Paulo', 'Universidades', 'n.87', 'julho', '2017', NULL, NULL, 'ex.1', 'p. 50', 1, NULL, 1, '003.', NULL, 1),
(2303, '2022-01-01', 'UNESP - Universidade Estadual Paulista', 'UnespCiências', NULL, '607 R', NULL, 'São Paulo', 'Universidades', 'n.91', 'nov', '2017', NULL, NULL, 'ex.1', 'p. 50', 1, NULL, 1, '003.', NULL, 1),
(2304, '2022-01-01', 'ICEP - Investimentos, Comércio e Turismo de Portugal', 'Portugal: Quando o Atântico encontra a Europa', NULL, '658. R', NULL, 'Lisboa', 'ICEP - Investimentos, Comércio e Turismo de Portugal', NULL, NULL, NULL, NULL, NULL, 'ex.1', 'p.24', 1, '20.00', 0, '003.', NULL, 1),
(2305, '2022-01-01', 'Simpósio sobre gramado', 'Tópicos Atuais em Gramados II', NULL, '635.9642 R', '21.775.583', 'Botucatu-SP', 'UNESP - Universidade Estadual Paulista', NULL, NULL, '2010', NULL, NULL, 'ex.1', 'p.205', 1, NULL, 1, '003.', NULL, 1),
(2306, '2022-01-01', 'Suely Broxado', 'Guia para Profissionais de Saúde Mental: Sexualidade e DST/AIDS', NULL, '614 R', NULL, 'Rio de Janeiro ', 'Institudo Franco Basaglia/IFB', NULL, NULL, '2002', NULL, NULL, 'ex.1', 'p.64', 1, NULL, 1, '003.', NULL, 1),
(2307, '2022-01-01', 'ARAÚJO, Teo Weingrill ', 'Prevenção das DST/aids em Adolescentes e jovens: bochuras de referência para os profissionais de saúde', NULL, '614 ', '978-55-99792-03-2', 'São Paulo', 'Centro de Referência e Treinamento DST/aids', NULL, NULL, '2007', NULL, NULL, 'ex.1', 'p.126', 1, '10.00', 0, '003.', NULL, 1),
(2308, '2022-01-01', 'Lair Guerra de Macedo Rodrigues (Org.)', 'Hepatites, Aids e Herpes na Prática Odontologica', NULL, '614 R', NULL, 'Brasília-DF', 'Líttera Maciel ', NULL, NULL, '1996', NULL, NULL, 'ex.1', 'p.56', 1, '10.00', 0, '003.', NULL, 1),
(2309, '2022-01-01', 'OAB-SP', 'E-cartilha : Peticionamento eletrônico/Processo elertrônico', NULL, '341.46 R', NULL, 'São Paulo', 'Sinsa', NULL, NULL, '2012', NULL, 'v.1', 'ex.1', 'p. 127', 1, NULL, 1, '003.', NULL, 1),
(2310, '2022-01-01', 'Paulo César Corrêia Borges', 'Leituras de um realismo jurídico - penal marginal, Homenagem a Alessandro Baratta', NULL, '345 R', '978.85.7983.249.9', 'São Paulo', 'Cultora Acadêmica', NULL, NULL, '2012', NULL, NULL, 'ex.1', 'p. 271', 1, NULL, 1, '003.', NULL, 1),
(2311, '2022-01-01', 'Comitê Brasileiro do Conselho Internacional de Museus', 'Código de ética do ICOM (Conselho Internacional de Museus)', NULL, '069 R', NULL, 'São Paulo', 'Impressão Oficial', NULL, NULL, '2009', NULL, NULL, ' ex.1', 'p. 29', 1, NULL, 1, '003.', NULL, 1),
(2312, '2022-01-01', 'Paulo César Corrêia Borges (Org.)', 'Marcadores Sociais da diferença e repressão penal', NULL, '345 R', NULL, 'Franca-SP', 'Cultora Acadêmica', NULL, NULL, '2004', NULL, NULL, ' ex.1', 'p.238', 1, NULL, 1, '003.', NULL, 1),
(2313, '2022-01-01', 'Secretaria de Meio Ambiente - Governo de Estado de S`P', 'Coleta Seletiva para prefeituras - Guia de implantação', NULL, '341.46 R', '85.86624.41.1', 'São Paulo', 'Governo do Estado de SP', NULL, NULL, '2014', NULL, NULL, ' ex.1', 'p.40', 1, NULL, 1, '003.', NULL, 1),
(2314, '2022-01-01', 'Assembleia Legislativa de Minas', 'Tudo que o vereador precisa saber para que o seu mandato seja o que precisa e espera a sociedade brasileira', NULL, '352.16', NULL, 'Belo Horizonte-MG', 'Atual', NULL, NULL, '2011', NULL, NULL, 'ex.1', 'p.134', 1, NULL, 1, '003.', NULL, 1),
(2315, '2022-01-01', '***', 'Guia de Fuções Microsoft Exel', NULL, '004', NULL, '***', '***', NULL, NULL, '1992', NULL, NULL, 'ex.1', 'p.652', 1, '20.00', 0, '003.', NULL, 1),
(2316, '2022-01-01', 'Francisco Mario Viceconti Costa (coord.)', ' Universo Jurídico', NULL, '348 R', '85.87931.05.9', 'Rio de Janeiro ', 'Revic', NULL, NULL, '2001', NULL, NULL, 'ex.1', 'p.352', 1, '20.00', 0, '003.', NULL, 1),
(2317, '2022-01-01', 'Câmra Municipal de Varzea Paulista', 'Lei Orgânica do Município de Várzea Paulista', NULL, '348 R', NULL, 'São Paulo', '***', NULL, NULL, '1990', NULL, 'v.1', 'ex.1', 'p.71', 1, NULL, 1, '003.', NULL, 1),
(2318, '2022-01-01', 'OLIVEIRA, José carlos de', 'Temas de direito público', NULL, '341', '978.85.7805.045.0', 'Jaboticabal', 'UNESP - Universidade Estadual Paulista', NULL, NULL, '2009', NULL, NULL, 'ex.1', 'p.210', 1, '22.00', 0, '003.', NULL, 1),
(2319, '2022-01-01', 'CÂMARA, Marcelo ', 'Cachaça: prazer brasileiro', NULL, '641.874 R', '85.7478.136.3', 'Rio de Janeiro ', 'Mauad', NULL, NULL, '2004', NULL, NULL, 'ex.1', 'p.144', 1, '33.00', 0, '003.', NULL, 1),
(2320, '2022-01-01', 'Dyonne Stamato (Cood.)', 'Os Municípios e a lei de acesso à informação pública', NULL, '342 R', NULL, 'São Paulo', 'Conan - Consultoria em Administração Municipal', NULL, NULL, '2012', NULL, NULL, 'ex.1', 'p.94', 1, NULL, 1, '003.', NULL, 1),
(2321, '2022-01-01', '***', 'Almanaque Abril 2001 - Mundo em mapas e retrospectiva 2000', NULL, '036.9 R', '858771043.5', 'São Paulo', 'Abril Editora', NULL, NULL, '2001', NULL, 'v.1', 'ex.1', 'p.385', 1, '10.00', 0, '003.', NULL, 1),
(2322, '2022-01-01', '***', 'Almanaque Abril 2001 - Brasil em mapas e retrospectiva 2000', NULL, '036.9 R', '858771043.5', 'São Paulo', 'Abril Editora', NULL, NULL, '2001', NULL, 'v.1', 'ex.1', 'p.385', 1, '10.00', 0, '003.', NULL, 1),
(2323, '2022-01-01', '***', 'Almanaque Abril 2001 - Guia da cidadania', NULL, '036.9 R', NULL, 'São Paulo', 'Abril Editora', NULL, NULL, '2001', NULL, 'v.1', 'ex.1', 'p.97', 1, '10.00', 0, '003.', NULL, 1),
(2324, '2022-01-01', 'Durval Augusto Rezende Filho (Org.)', 'Panorama do Desempenho do Tribunal de Justiça de São Paulo 2015-2016', NULL, '348.04 R', '978.85.69031.14.7', 'São Paulo', 'IPAM - Instituto Paulista de Magistratura', NULL, NULL, '2018', NULL, NULL, 'ex.1', 'p.111', 1, NULL, 1, '003.', NULL, 1),
(2325, '2022-01-01', 'Durval Augusto Rezende Filho (Org.)', 'Panorama do Desempenho do Tribunal de Justiça de São Paulo 2015-2016', NULL, '348.04 R', '978.85.69031.14.7', 'São Paulo', 'IPAM - Instituto Paulista de Magistratura', NULL, NULL, '2018', NULL, NULL, 'ex.2', 'p.111', 1, NULL, 1, '003.', NULL, 1),
(2326, '2022-01-01', 'Durval Augusto Rezende Filho (Org.)', 'Panorama do Desempenho do Tribunal de Justiça de São Paulo 2015-2016', NULL, '348.04 R', '978.85.69031.14.7', 'São Paulo', 'IPAM - Instituto Paulista de Magistratura', NULL, NULL, '2018', NULL, 'v.1', 'ex.3', 'p.111', 1, NULL, 1, '003.', NULL, 1),
(2327, '2022-01-01', 'Brasil - Secretaria Nacional da Juventude', 'Reflexões sobre a política nacional de juventude 2003-2010', NULL, '325 R', NULL, 'Brasília-DF', '***', NULL, NULL, '2011', NULL, NULL, 'ex.1', 'p.115', 1, NULL, 1, '003.', NULL, 1),
(2328, '2022-01-01', 'Centro de pesquisa do Cinema Brasileiro', 'Cadeno de Pesquisa (Cinema) Nº 1', NULL, '770 R', NULL, '***', '***', NULL, NULL, '1984', NULL, 'v.1', 'ex.1', 'p.54', 1, NULL, 1, '003.', NULL, 1),
(2329, '2022-01-01', 'MASSARANI, EmanueL von Lauenstein ', 'Guia Esportivo - Artístico da Cidade de São Paulo (1 Guia, 60 imagens e 12 postais)', NULL, '796 ', NULL, 'São Paulo', '***', NULL, NULL, NULL, NULL, NULL, 'ex.1', 'p.59', 1, '20.00', 0, '003.', NULL, 1),
(2330, '2022-01-01', 'Antônio Sonsin', 'Bragança Paulista - Brasil', NULL, '980 R', NULL, 'Bragança Paulista-SP', '***', NULL, NULL, '2007', NULL, NULL, 'ex.1', NULL, 1, NULL, 1, '003.', NULL, 1),
(2331, '2022-01-01', 'Unidade de Politicas Públicas - Fundação Prefeito Faria Lima', 'Fontes de Recursos aos municípios', NULL, '352.16 R', NULL, 'São Paulo', 'Fundação Prefeito Faria Lima - CEPAM', NULL, NULL, '2002', NULL, NULL, 'ex.1', 'p.260', 1, NULL, 1, '003.', NULL, 1),
(2332, '2022-01-01', 'MASSARANI, EmanueL von Lauenstein ', 'Esculturas da Vila Pan - XV Jogos Pan Americanos Rio 2007 - Nino Ferraz', NULL, '796 R', NULL, 'São Paulo', '***', NULL, NULL, '2007', NULL, NULL, 'ex.1', '15p.', 1, '30.00', 0, '005.', NULL, 1),
(2333, '2022-01-01', 'Secretaria de Esportes, Recreação e Lazer do Município de SP', 'Museu de Arte do Esporte Olímpico', NULL, '796 R', NULL, 'São Paulo', '***', NULL, NULL, '2007', NULL, NULL, 'ex.1', 'p.35', 1, '20.00', 0, '005.', NULL, 1),
(2334, '2022-01-01', 'Secretaria de Esportes, Recreação e Lazer do Município de SP', 'Museu de Arte do Esporte Olímpico', NULL, '796 R', NULL, 'São Paulo', '***', NULL, NULL, '2007', NULL, NULL, 'ex.2', 'p.35', 1, '20.00', 0, '005.', NULL, 1),
(2335, '2022-01-01', 'CHADAD, Vera Lúcia Chaccur (Direção Geral)', 'Salão de Arte 2007', NULL, '708 ', NULL, 'São Paulo', 'Corset Gráfica', NULL, NULL, '2007', NULL, NULL, 'ex.1', 'p.118', 1, '20.00', 0, '005.', NULL, 1),
(2336, '2022-01-01', 'CHADAD, Vera Lúcia Chaccur (Direção Geral)', 'Salão de Arte 2008', NULL, '708 ', NULL, 'São Paulo', 'Corset Gráfica', NULL, NULL, '2008', NULL, NULL, 'ex.1', 'p.158', 1, '20.00', 0, '005.', NULL, 1),
(2337, '2022-01-01', 'CHADAD, Vera Lúcia Chaccur (Direção Geral)', 'Salão de Arte 2009', NULL, '708 ', NULL, 'São Paulo', 'Corset Gráfica', NULL, NULL, '2009', NULL, NULL, 'ex.1', 'p.179', 1, '20.00', 0, '005.', NULL, 1),
(2338, '2022-01-01', 'CHADAD, Vera Lúcia Chaccur (Direção Geral)', 'Salão de Arte 2010', NULL, '708 ', NULL, 'São Paulo', 'Corset Gráfica', NULL, NULL, '2010', NULL, NULL, 'ex.1', 'p.166', 1, '20.00', 0, '005.', NULL, 1),
(2339, '2022-01-01', 'CHADAD, Vera Lúcia Chaccur (Direção Geral)', 'Salão de Arte 2011', NULL, '708 ', NULL, 'São Paulo', 'Corset Gráfica', NULL, NULL, '2011', NULL, NULL, 'ex.1', 'p.163', 1, '20.00', 0, '005.', NULL, 1),
(2340, '2022-01-01', 'CHADAD, Vera Lúcia Chaccur (Direção Geral)', 'Salão de Arte 2013', NULL, '708 ', NULL, 'São Paulo', 'Corset Gráfica', NULL, NULL, '2013', NULL, NULL, 'ex.1', 'P.167', 1, '20.00', 0, '005.', NULL, 1),
(2341, '2022-01-01', 'CHADAD, Vera Lúcia Chaccur (Direção Geral)', 'Salão de Arte 2014', NULL, '708 ', NULL, 'São Paulo', 'Corset Gráfica', NULL, NULL, '2014', NULL, NULL, 'ex.1', 'p.147', 1, '20.00', 0, '005.', NULL, 1),
(2342, '2022-01-01', '***', 'Ciclo de conferências e debates \"Empreendedorismo e Inovação na Tecnologia na regional IPWS\" do 16º Encontro da SBQ regional interior paulista Waldemar Saffioti - ', NULL, '060 R', '85.60114.08.5', 'Franca-SP', 'UNESP - Universidade Estadual Paulista', NULL, NULL, '2007', NULL, NULL, 'ex.1', 'p.274', 1, NULL, 1, '003.', NULL, 1),
(2343, '2022-01-01', 'Luiz Antonio Soares Hentz (Coord.)', 'Obrigações empresariais', NULL, '352.2 R', NULL, 'Franca-SP', 'UNESP - Universidade Estadual Paulista', NULL, NULL, '1998', NULL, NULL, 'ex.1', 'p.260', 1, NULL, 1, '003.', NULL, 1),
(2344, '2022-01-01', 'Fundação Alexandres Gustão', 'Encontro sobre negociações internacionais de Estados e Municípios 2006', NULL, '327.2 R', '978.85.7631.073.0', 'Brasília-DF', 'Impresso no Brasil', NULL, NULL, '2007', NULL, 'v.1', 'ex.1', 'p.148', 1, NULL, 1, '003.', NULL, 1),
(2345, '2022-01-01', 'PRESTES, Cristine', 'Guia Valor Econômico de licitações', NULL, '352.5 R', '85.250.3748.6', 'São Paulo', 'Globo', NULL, NULL, '2004', NULL, NULL, 'ex.1', 'p.141', 1, '20.00', 0, '003.', NULL, 1),
(2346, '2022-01-01', 'Câmara dos Deputados ', 'Cartilha de fiscalização e controle: um manual de exercício de cidadania', NULL, '352.8 R', '978.85.402.0467.6', 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '2015', NULL, NULL, 'ex.1', 'p.93', 1, NULL, 1, '003.', NULL, 1),
(2347, '2022-01-01', 'Milton Würdig', 'As \"profanações\" de Milton', NULL, NULL, NULL, 'Porto Alegre', '***', NULL, NULL, '1986', NULL, 'v.1', 'ex.1', NULL, 1, NULL, 1, '003.', NULL, 1),
(2348, '2022-01-01', '***', 'Fatos sobre os Estados Unidos', NULL, '973 R', NULL, '***', '***', NULL, NULL, NULL, NULL, 'v.1', 'ex.1', 'p.92', 1, NULL, 1, '003.', NULL, 1),
(2349, '2022-01-01', 'Prefeitura de Santana do Parnaíba', 'Cartilha do Morador do Centro Histórico de Santana do Parnaíba', NULL, '921 R', NULL, 'Santana do Parnaíba', 'Secretaria', NULL, NULL, NULL, NULL, 'v.1', 'ex.1', 'p.43', 1, NULL, 1, '003.', NULL, 1),
(2350, '2022-01-01', 'Raphael Cavalcante e Clarissa Estrêla (Coord.)', 'Relatório bibliográfico sobre a condição do negro no Brasil', NULL, '036.9 R', '978.85.402.0653.3', 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '2018', NULL, NULL, 'ex.1', 'p.315', 1, NULL, 1, '003.', NULL, 1),
(2351, '2022-01-01', 'Escola Legislativa da Câmara Municipal de Araras-SP', 'Sessão Solene de Homenagem e Devolução dos mandatos de Prefeito e vice-prefeito à Milton Severino e Acésio Devitte', NULL, '092 R', NULL, 'Araras-SP', 'Câmara ', NULL, NULL, '2015', NULL, NULL, 'ex.1', 'p.58', 1, NULL, 1, '003.', NULL, 1),
(2352, '2022-01-01', 'Rubens Figueiredo', 'Caderno Escola Política nº 1 : Manual prático de marketing político', NULL, '320.6 R', '85.7504.059.6', 'São Paulo', 'Fundação Konrad Adenauer', 'n.1', NULL, '2006', NULL, NULL, 'ex.1', 'p.74', 1, NULL, 1, '003.', NULL, 1),
(2353, '2022-01-01', 'Wilhelm Hofmeister e Gustavo Adolfo P. D. Santos', 'Caderno Escola Política nº 2: Os partidos políticos na Democracia', NULL, '320.6 R', '978.85.7504.109.3', 'São Paulo', 'Fundação Konrad Adenauer', 'n.2', NULL, '2007', NULL, NULL, 'ex.1', 'p.106', 1, NULL, 1, '003.', NULL, 1),
(2354, '2022-01-01', 'CIEE', 'Coleção CIEE 81 : A realidade educacional brasileira e o desenvolvimento do país', NULL, '379 R', NULL, 'São Paulo', '***', 'n.81', NULL, '2005', NULL, NULL, 'ex.1', 'p.53', 1, NULL, 1, '003.', NULL, 1),
(2355, '2022-01-01', 'Senado Federal', 'Relatório da viagem do Senador Eduardo Matarazzo Suplicy ao Iraque: Uma renda baixa para democratizar e pacificar o iraque', NULL, '036.9 R', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2008', NULL, 'v.1', 'ex.1', 'p.43', 1, NULL, 1, '003.', NULL, 1),
(2356, '2022-01-01', 'SÃO PAULO, Secretaria de Segurança Pública de', 'Ouvidoria da Política do Estado de São Paulo: Relatório Anual de preservação de contas 1997', NULL, '351 R', NULL, 'São Paulo', 'Secretarias ', NULL, NULL, '1997', NULL, NULL, 'ex.1', 'p.243', 1, NULL, 1, '003.', NULL, 1),
(2357, '2022-01-01', 'SÃO PAULO, Câmara Municipal de', 'SP2030: Relatório do Ciclo de debate realizado pela Escola do Parlemanto da Câmara de São Paulo', NULL, '320.6 R', NULL, 'São Paulo', 'Câmara ', NULL, NULL, '2016', NULL, 'v.1', 'ex.2', 'p.107', 1, NULL, 1, '003.', NULL, 1),
(2358, '2022-01-01', 'Emir Antônio Khair', 'Gestão Fiscal responsável- Simples municipal - Guia de orientação para as Prefeituras', NULL, '352.16 R', NULL, 'Brasília-DF', 'Ministérios', NULL, NULL, '2001', NULL, 'v.1', 'ex.2', 'p.206', 1, NULL, 1, '003.', NULL, 1),
(2359, '2022-01-01', '***', 'Manual de administração jurídica, contábil e financeira para organizações não-governamentais', NULL, '658.048 R', '85.7596008.3', 'São Paulo', 'Peirópolis', NULL, NULL, '2003', NULL, 'v.1', 'ex.1', 'p.203', 1, NULL, 1, '003.', NULL, 1),
(2360, '2022-01-01', 'Unesp - José Murari Bovo (Org.)', 'Relatorio Unesp: A contribuição da Unesp para o dinamismo econômico dos municípios', NULL, '036.9 R', '978.85.88910.08.9', 'São Paulo', 'Universidades', NULL, NULL, '2013', NULL, 'v.1', 'ex.1', 'p.75', 1, NULL, 1, '003.', NULL, 1),
(2361, '2022-01-01', 'Intituto de Pesquias Aplicadas (IPEA)', 'Brasil em desenvolvimento 2011: Estado, Planejamento e políticas públicas - Volume 1', NULL, '036.9 R', '978.85.7811.140.3', 'Brasília-DF', 'IPEA', NULL, NULL, '2012', NULL, 'v.1', 'ex.1', 'p.230', 1, NULL, 1, '003.', NULL, 1),
(2362, '2022-01-01', 'Intituto de Pesquias Aplicadas (IPEA)', 'Brasil em desenvolvimento 2011: Estado, Planejamento e políticas públicas - Volume 2', NULL, '036.9 R', '978.85.78811.141.0', 'Brasília-DF', 'IPEA', NULL, NULL, '2012', NULL, 'v.2', 'ex.2', 'p.532', 1, NULL, 1, '003.', NULL, 1),
(2363, '2022-01-01', 'Brasil - Minsitério da Saúde', 'Relatório: Parto e nascimento domiciliar assistido por parteiras tradicionais: o programa trabalhando cm parteiras', NULL, '036.9 R', '978.85.334.1855.4', 'Brasília-DF', 'Ministérios', NULL, NULL, '2012', NULL, NULL, 'ex.1', 'p.90', 1, NULL, 1, '003.', NULL, 1),
(2364, '2022-01-01', 'Brasil - Ministério da Justiça', 'Relatório: Justiça comunitária- uma experiência', NULL, '036.9 R', NULL, 'Brasília-DF', 'Ministérios', NULL, NULL, '2006', NULL, NULL, 'ex.1', 'p.123', 1, NULL, 1, '003.', NULL, 1),
(2365, '2022-01-01', 'Brasil - Comissão especial de pacto federativo ', 'Pacto Federativo: uma alternativa para o crescimento do Brasil', NULL, '036.9 R', NULL, 'Brasília-DF', 'Câmara dos Deputados', NULL, NULL, '2015', NULL, NULL, 'ex.1', 'p.115', 1, NULL, 1, '003.', NULL, 1),
(2366, '2022-01-01', 'Senado Federal', 'Contas Abertas: Relatório de Gestão - Presidente  do Senado Renan Calheiros ', NULL, '036.9 R', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2014', NULL, NULL, 'ex.1', 'p.177', 1, NULL, 1, '003.', NULL, 1),
(2367, '2022-01-01', 'Heraldo da Costa Reis', 'Gestão Fiscal Responsável: simpes municipal: Caderno IBAM 2 - Impacto da LRF sobre a Lei nº 4.320', NULL, '352.16 R', NULL, '***', 'Instituto Brasileiro de Administração Municipal - IBAM', NULL, NULL, '2001', NULL, 'v.2', 'ex.1', 'p.36', 1, NULL, 1, '003.', NULL, 1),
(2368, '2022-01-01', 'Claudio Nascimento', 'Gestão Fiscal Responsável: simpes municipal: Caderno IBAM 4 - Elaboração das diretrizes orçamentárias e do Orçamento', NULL, '352.16 R', NULL, '***', 'Instituto Brasileiro de Administração Municipal - IBAM', NULL, NULL, '2001', NULL, 'v.4', 'ex.1', 'p.75', 1, NULL, 1, '003.', NULL, 1),
(2369, '2022-01-01', 'Claudio Nascimento', 'Gestão Fiscal Responsável: simpes municipal: Caderno IBAM 5- Acompanhamento da Execução Orçamentária', NULL, '352.16 R', NULL, '***', 'Instituto Brasileiro de Administração Municipal - IBAM', NULL, NULL, '2001', NULL, 'v.5', 'ex.1', 'p.72', 1, NULL, 1, '003.', NULL, 1),
(2370, '2022-01-01', 'Alcides Redondo Rodrigues (Org.)', 'Gestão Fiscal Responsável: simpes municipal: Caderno IBAM 7 - O papel da Câmara municipal na gestão fiscal ', NULL, '352.16 R', NULL, '***', 'Instituto Brasileiro de Administração Municipal - IBAM', NULL, NULL, '2001', NULL, 'v.7', 'ex.1', 'p.32', 1, NULL, 1, '003.', NULL, 1),
(2371, '2022-01-01', 'Ari Vainer, Josélia Albuquerque e Sol Garson', 'Gestão Fiscal responsável- Simples municipal - Leis de diretrizes orçamentárias - Manual de Elaboração', NULL, '352.16 R', NULL, 'Brasília-DF', 'Instituto Brasileiro de Administração Municipal - IBAM', NULL, NULL, '2001', NULL, NULL, 'ex.2', 'p.95', 1, NULL, 1, '003.', NULL, 1),
(2372, '2022-01-01', 'Cid Heraclito de Queiroz', 'Gestão Fiscal responsável- Simples municipal - IBAM 1 - A lei de responsabilidade fiscal no contexto da Reforma do Estado', NULL, '352.16 R', NULL, 'Brasília-DF', 'Instituto Brasileiro de Administração Municipal - IBAM', NULL, NULL, '2001', NULL, 'v.1', 'ex.1', 'p.56', 1, NULL, 1, '003.', NULL, 1),
(2373, '2022-01-01', 'Universidadade de São Paulo ', '4º Relatório Nacional sobre os Dirietos Humanos no Brasil 2010', NULL, '036.9 R', NULL, 'Brasília-DF', 'Universidades', NULL, NULL, '2010', NULL, NULL, 'ex.1', 'p.437', 1, NULL, 1, '003.', NULL, 1),
(2374, '2022-01-01', 'Faculdade de Ciências Médicas da Santa Casa de São Paulo', 'Balanço social 2012 ', NULL, '036.9 R', NULL, 'São Paulo', 'Universidades', NULL, NULL, '2012', NULL, NULL, 'ex.1', 'p.69', 1, NULL, 1, '003.', NULL, 1),
(2375, '2022-01-01', 'Ministério da Ciência e Tecnologia e Inovação - MCTI', 'Estratégias Nacionais de Ciência, Tecnologia e Inovação - Balanço das Atividades Estruturantes 2011', NULL, '036.9 R', NULL, 'Brasília-DF', 'Ministérios', NULL, NULL, '2011', NULL, NULL, 'ex.1', 'p.212', 1, NULL, 1, '003.', NULL, 1),
(2376, '2022-01-01', 'Evaldo Luiz Gaeta Espínola e Edson Wendland', 'Relatório de Pesquisa e Extensão da Usp - PPG-SEA: trajetórias e perspectivas de um curso multidisciplinar - Volume 4', NULL, '036.9 R', NULL, 'São Carlos -SP', 'RIMI - Gráfica e Editora', NULL, NULL, '2005', NULL, 'V.4', 'ex.1', 'p.462', 1, NULL, 1, '003.', NULL, 1),
(2377, '2022-01-01', 'CONAE - Conferência Nacional de Educação', 'Construindo o sistema nacional articulado de educação: o plano nacional de educação, diretrizes e estratégias de ação - 2010', NULL, '371 R', NULL, 'Brasília-DF', 'Ministérios', NULL, NULL, '2010', NULL, NULL, 'ex.1', 'p.334', 1, '10.00', 0, '003.', NULL, 1),
(2378, '2022-01-01', 'Secretaria Estadual de Educação SP ', 'Preconceito e discriminação no contexto escolar', NULL, '379.81 R', NULL, 'São Paulo', 'Secretarias ', NULL, NULL, '2009', NULL, NULL, 'ex.1', 'p.101', 1, NULL, 1, '003.', NULL, 1),
(2379, '2022-01-01', 'Assembleia Legislativa do Mato Grosso', 'Acordo Ortográfico da Língua Portuguesa', NULL, '415 R', NULL, 'Cuiabá-MG', 'Assembleias Legislativas', NULL, NULL, '2016', NULL, NULL, 'ex.1', 'p.78', 1, NULL, 1, '003.', NULL, 1),
(2380, '2022-01-01', 'Assembleia Legislativa do Mato Grosso', 'Manual de Redação Oficial', NULL, '469.0469 R', NULL, 'Cuiabá-MG', 'Assembleias Legislativas', NULL, NULL, '2016', NULL, NULL, 'ex.1', 'p.98', 1, NULL, 1, '003.', NULL, 1),
(2381, '2022-01-01', 'Willian B. Martin ', 'Serviço de qualidade para atendimento ao cliente', NULL, '658.4 R', '1.56052.203.8', '***', '***', NULL, NULL, '1993', NULL, NULL, 'ex.1', 'p.89', 1, '10.00', 0, '003.', NULL, 1),
(2382, '2022-01-01', 'Senado Federal', 'Manual de padronização de textos: Normas de editoração para elaboração de originais, composição e revisão', NULL, '415 R', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, NULL, NULL, NULL, 'ex.1', 'p.137', 1, NULL, 1, '003.', NULL, 1),
(2383, '2022-01-01', 'Federação Brasileira das Sociedades de Ginecologia e Obstetrícia', 'Manual de Orientação de Anteconcepção', NULL, '618.1 R', NULL, '***', '***', NULL, NULL, '1997', NULL, NULL, '1/1', 'p.128', 1, '10.00', 0, '003.', NULL, 1),
(2384, '2022-01-01', 'Ricardo E. Caldas e Silvério Crestana', 'Políticas Públicas municipais de apoio às micro e pequenas empresas', NULL, '323 R', '85.7376.066.4', 'São Paulo', 'Sebrae', NULL, NULL, '2005', NULL, NULL, 'ex.1', 'p.101', 1, '10.00', 0, '003.', NULL, 1),
(2385, '2022-01-01', 'FAPESP', 'Pesquisa a serviço da sociedade - programa de pesquisa em políticas públicas', NULL, '323 R', NULL, 'São Paulo', 'Fapesp', NULL, NULL, '2012', NULL, NULL, 'ex.1', NULL, 1, '0.00', 1, '003.', NULL, 1),
(2386, '2022-01-01', '***', 'A descoberta da escrita', NULL, '028.5 R', '85.87507.78.8', 'São Paulo', 'Editora Educar', NULL, NULL, '2012', NULL, 'v.1', 'ex.1', 'p.32', 1, '0.00', 1, '003.', NULL, 1),
(2387, '2022-01-01', 'Ana Maria Prinheor - CENPEC', 'Navegar com segurança: protegendo seus filhos da pedofilia e da pornografia infanto-juvenil na internet', NULL, '363.4702854678 R', '978.85.85786.63.2', 'São Paulo', 'CENPEC', NULL, NULL, '2006', NULL, 'v.1', 'ex.1', 'p.44 ', 1, '0.00', 1, '003.', NULL, 1),
(2388, '2022-01-01', 'Tribunal Regional Eleitoral', 'Caratilha do jovem eleitor', NULL, '342.07', NULL, 'São Paulo', 'TRE-SP', NULL, NULL, NULL, NULL, NULL, 'ex.1', 'p.26', 1, '0.00', 1, '003.', NULL, 1),
(2389, '2022-01-01', 'Ziraldo', 'Eu Senadoro um passeio', NULL, '028.5 R', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2009', NULL, NULL, 'ex.5', 'p.33', 1, '0.00', 1, '003.', NULL, 1),
(2390, '2022-01-01', 'Ziraldo', 'Eu Senadoro um passeio', NULL, '028.5 R', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2009', NULL, NULL, 'ex.6', 'p.33', 1, '0.00', 1, '003.', NULL, 1),
(2391, '2022-01-01', 'Câmara Municipal de Barueri', 'Cartilha do Sistema de Gestão Ambiental', NULL, '028.5 R', NULL, 'Barueri-SP', 'Câmara', NULL, NULL, NULL, NULL, NULL, 'ex.2', 'p.15', 1, '0.00', 1, '003.', NULL, 1),
(2392, '2022-01-01', 'Senado Federal', 'Vamos conversar sobre Bullyng e Cyberbullying?', NULL, '028.5 R', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2017', NULL, NULL, 'ex.1', 'p.40', 1, '0.00', 1, '003.', NULL, 1),
(2393, '2022-01-01', 'Câmara dos Deputados', 'Verdade ou consequencia: deputados e cidadãos', NULL, '028.5 R', NULL, 'Brasília-DF', 'Câmara', NULL, NULL, NULL, NULL, NULL, 'ex.2', 'p.42', 1, '0.00', 1, '003.', NULL, 1),
(2394, '2022-01-01', 'Assembleia Legislativa de Minas', 'O  poder do cidadão (passatempo)', NULL, '028.5 R', NULL, 'Minas Gerais', 'Assembleias Legislativas', NULL, NULL, NULL, NULL, NULL, 'ex.1', 'p.19', 1, '0.00', 1, '003.', NULL, 1),
(2395, '2022-01-01', 'Tribunal Regional Eleitoral', 'Carta de Serviços ao cidadão', NULL, '342.07 R', NULL, 'São Paulo', 'TRE-SP', NULL, NULL, '2013', NULL, NULL, 'ex.1', 'p.30', 1, '0.00', 1, '003.', NULL, 1),
(2396, '2022-01-01', 'Câmara Municipal de Morungaba - SP', 'Conhecendo nossa história', NULL, '921  R', NULL, 'Morungaba-SP', 'Cãmara', 'n.1', 'agosto', '2018', NULL, NULL, 'ex.1', 'p.43', 1, '0.00', 1, '003.', NULL, 1),
(2397, '2022-01-01', 'Abrarec - Associação Brasileira das Relações', 'Guia de ouvidorias do Brasil', NULL, '362 R', NULL, 'São Paulo', 'Grupo Padrão', NULL, NULL, '2001', NULL, NULL, 'ex.1', 'p.66', 1, '0.00', 1, '003.', NULL, 1),
(2398, '2022-01-01', 'Câmara dos deputados', 'A Câmara e o cidadão: um guia para conhecer e participar do processo legislativo', NULL, '342.71 R', NULL, 'Brasília-DF', 'Câmara ', NULL, NULL, '2011', NULL, NULL, 'ex.1', 'p. 51', 1, '0.00', 1, '003.', NULL, 1),
(2399, '2022-01-01', 'MIGUEL, Prof. Jorge ', 'Cartilha do Vereador', NULL, '342.05 R', NULL, 'São Paulo', 'União dos Vereadores de São Paulo', NULL, NULL, NULL, NULL, NULL, 'ex.1', 'p.107', 1, '0.00', 1, '003.', NULL, 1),
(2400, '2022-01-01', 'AZEVEDO, Maria Ramos de', 'Viva vida: estudos sociais', NULL, '372.83', NULL, 'São Paulo', 'FTD', NULL, NULL, '1994', NULL, NULL, 'ex.1', 'p.78', 1, '10.00', 0, '003.', NULL, 1),
(2401, '2022-01-01', 'Controladoria Geral da União (CGU)', 'Coleção Olho vivo no dinheiro público: Controle social - Organizações aos cidadãos para a participação na gestão pública e exercío', NULL, '352.8 R', '978.85.61770.07.5', 'Brasília-DF', 'CGU', NULL, NULL, '2011', NULL, NULL, 'ex.1', 'p.45', 1, '7.00', 0, '003.', NULL, 1),
(2402, '2022-01-01', 'GARCIA, Edson Gabriel ', 'De olhos bem abertos: a política presente em nosso cotidiano', NULL, '372.832 R', '85.322.5669.4', 'São Paulo', 'FTD', NULL, NULL, '2005', NULL, NULL, 'ex.1', 'p.63', 1, '30.00', 0, '003.', NULL, 1),
(2403, '2022-01-01', 'GARCIA, Edson Gabriel ', 'Conversas sobre cidadania - o jeito de cada um: iguais e diferentes', NULL, '372.832 R', '85.322.4779.2', 'São Paulo', 'FTD', NULL, NULL, '2001', NULL, NULL, 'ex.1', 'p.47', 1, '30.00', 0, '003.', NULL, 1);
INSERT INTO `librarycollection` (`id`, `registrationDate`, `author`, `title`, `cdu`, `cdd`, `isbn`, `local`, `publisher_edition`, `number`, `month`, `year`, `edition`, `volume`, `copyNumber`, `pageNumber`, `typeAcquisitionId`, `price`, `prohibitedSale`, `provider`, `exclusionInfoTerm`, `registeredByUserId`) VALUES
(2404, '2022-01-01', 'Assossiação brasileira  das empresas de cartões de crédito e serviços', 'Cartão - a dica é saber usar', NULL, '330 R', NULL, '**', '**', NULL, NULL, NULL, NULL, NULL, 'ex.1', 'p.38', 1, '10.00', 0, '003.', NULL, 1),
(2405, '2022-01-01', 'Câmara de Varginha', 'Panfleto da Escola do parlamento de Varginha', NULL, '342', NULL, 'Varginha-SP', 'Câmara', NULL, NULL, NULL, NULL, NULL, 'ex.1', 'p.10', 1, '0.00', 1, '004.', NULL, 1),
(2406, '2022-01-01', 'Acita', 'Projeto Memória: Itapevi resgata a sua história - Caderno de Exercícios ', NULL, '921  R', NULL, 'Itapevi-SP', 'imprensa oficial', NULL, NULL, '1997', NULL, NULL, 'ex.1', 'p.34', 1, '0.00', 1, '004.', NULL, 1),
(2407, '2022-01-01', 'Konrad-Adenauer-Stiftung', 'XVIII Conferência de Segurança Nacional: Ausencia de guerras significa paz? Estratégias de segurança internacional em uma nova ordem geopolítica mundial', NULL, '327.2  R', '2176-297x', 'São Paulo', 'Konrad Adenauer', NULL, NULL, '2021', NULL, NULL, 'ex.1', 'p.277', 1, '0.00', 1, '004.', NULL, 1),
(2408, '2022-01-01', 'Juan Pablo Cardenal', 'El arte de hacer amigos/ A arte de fazer amigos: como o partido comunista chinês seduz os partidos políticos na América Latina', NULL, '327.2  R', NULL, 'São Paulo', 'Konrad Adenauer', NULL, NULL, '2021', NULL, NULL, 'ex.1', 'p.20', 1, '0.00', 1, '004.', NULL, 1),
(2409, '2022-01-01', 'Comitê de Gestão da internet no brasil', 'TIC Governo Eletrônico 2013', NULL, '004.60 R', NULL, 'Brasília-DF', 'Cetic.br', NULL, NULL, '2013', NULL, NULL, 'ex.1', 'p.399', 1, '20.00', 0, '003.', NULL, 1),
(2410, '2022-01-01', 'Konrad-Adenauer-Stiftung', 'Cultura de debate e democracia: pontes de entendimento com diálogo - Nº. 4', NULL, '321.8 R', '1519-0951', 'Rio de Janeiro', 'Fundação Adenauer', 'n.4', 'dez', 'XX - 2019', NULL, NULL, 'ex.1', 'p.91', 1, '0.00', 1, '004.', NULL, 1),
(2411, '2022-01-01', 'Câmra de Itapevi', 'Escola no Legislativo Edição 2011', NULL, '028.5 R', NULL, 'Itapevi-SP', 'Câmara', NULL, NULL, '2011', NULL, NULL, 'ex.1', 'p.23', 3, '0.00', 1, '**', NULL, 1),
(2412, '2022-01-01', 'Câmra de Itapevi', 'Escola no Legislativo Edição 2011', NULL, '028.5 R', NULL, 'Itapevi-SP', 'Câmara', NULL, NULL, '2011', NULL, NULL, 'ex.2', 'p.23', 3, '0.00', 1, '**', NULL, 1),
(2413, '2022-01-01', 'Câmra de Itapevi', 'Escola no Legislativo Edição 2011', NULL, '028.5 R', NULL, 'Itapevi-SP', 'Câmara', NULL, NULL, '2011', NULL, NULL, 'ex.3', 'p.23', 3, '0.00', 1, '**', NULL, 1),
(2414, '2022-01-01', 'Câmra de Itapevi', 'Escola no Legislativo Edição 2011', NULL, '028.5 R', NULL, 'Itapevi-SP', 'Câmara', NULL, NULL, '2011', NULL, NULL, 'ex.4', 'p.23', 3, '0.00', 1, '**', NULL, 1),
(2415, '2022-01-01', 'Câmra de Itapevi', 'Escola no Legislativo Edição 2011', NULL, '028.5 R', NULL, 'Itapevi-SP', 'Câmara', NULL, NULL, '2011', NULL, NULL, 'ex.5', 'p.23', 3, '0.00', 1, '**', NULL, 1),
(2416, '2022-01-01', 'Câmra de Itapevi', 'Atividades Escola no Legislativo', NULL, '028.5 R', NULL, 'Itapevi-SP', 'Câmara', NULL, NULL, '2015', NULL, NULL, 'ex.1', 'p.27', 3, '0.00', 1, '**', NULL, 1),
(2417, '2022-01-01', 'Câmra de Itapevi', 'Atividades Escola no Legislativo', NULL, '028.5 R', NULL, 'Itapevi-SP', 'Câmara', NULL, NULL, '2015', NULL, NULL, 'ex.2', 'p.27', 3, '0.00', 1, '**', NULL, 1),
(2418, '2022-01-01', 'Câmra de Itapevi', 'Atividades Escola no Legislativo', NULL, '028.5 R', NULL, 'Itapevi-SP', 'Câmara', NULL, NULL, '2015', NULL, NULL, 'ex.3', 'p.27', 3, '0.00', 1, '**', NULL, 1),
(2419, '2022-01-01', 'Câmra de Itapevi', 'Atividades Escola no Legislativo', NULL, '028.5 R', NULL, 'Itapevi-SP', 'Câmara', NULL, NULL, '2015', NULL, NULL, 'ex.4', 'p.27', 3, '0.00', 1, '**', NULL, 1),
(2420, '2022-01-01', 'Câmra de Itapevi', 'Atividades Escola no Legislativo', NULL, '028.5 R', NULL, 'Itapevi-SP', 'Câmara', NULL, NULL, '2015', NULL, NULL, 'ex.5', 'p.27', 3, '0.00', 1, '**', NULL, 1),
(2421, '2022-01-01', 'Câmra de Itapevi', 'Pel - Programa Escola no Legislativo - Cartilha', NULL, '028.5 R', NULL, 'Itapevi-SP', 'Câmara', NULL, NULL, '2017', NULL, NULL, 'ex.1', 'p.23', 3, '0.00', 1, '**', NULL, 1),
(2422, '2022-01-01', 'Câmra de Itapevi', 'Pel - Programa Escola no Legislativo - Cartilha', NULL, '028.5 R', NULL, 'Itapevi-SP', 'Câmara', NULL, NULL, '2017', NULL, NULL, 'ex.3', 'p.23', 3, '0.00', 1, '**', NULL, 1),
(2423, '2022-01-01', 'Câmra de Itapevi', 'Pel - Programa Escola no Legislativo - Cartilha', NULL, '028.5 R', NULL, 'Itapevi-SP', 'Câmara', NULL, NULL, '2017', NULL, NULL, 'ex.4', 'p.23', 3, '0.00', 1, '**', NULL, 1),
(2424, '2022-01-01', 'Câmra de Itapevi', 'Pel - Programa Escola no Legislativo - Cartilha', NULL, '028.5 R', NULL, 'Itapevi-SP', 'Câmara', NULL, NULL, '2017', NULL, NULL, 'ex.5', 'p.23', 3, '0.00', 1, '**', NULL, 1),
(2425, '2022-01-01', 'Escola do Parlamento da Câmara de São Paulo', 'Revista do Parlamento e sociedade - Vol.3 / n.4', NULL, '328  R', '2318-4248', 'São Paulo', 'Câmara', 'n.4', 'jan-jun', '2015', NULL, 'v.3', 'ex.1', 'p.1-120', 1, '0.00', 1, '003.', NULL, 1),
(2426, '2022-01-01', 'Escola do Parlamento da Câmara de São Paulo', 'Revista do Parlamento e sociedade - Vol.3 / n.5', NULL, '328  R', '2318-4248', 'São Paulo', 'Câmara', 'n.5', 'jul-dez', '2015', NULL, 'v.3', 'ex.1', 'p.1-128', 1, '0.00', 1, '003.', NULL, 1),
(2427, '2022-01-01', 'Escola do Parlamento da Câmara de São Paulo', 'Revista do Parlamento e sociedade - Vol.5/ n.8', NULL, '328  R', '2318-4248', 'São Paulo', 'Câmara', 'n.8', 'jan-jun', '2017', NULL, 'v.5', 'ex.1', 'p.1-124', 1, '0.00', 1, '003.', NULL, 1),
(2428, '2022-01-01', '**', 'Crescendo sem drogas - Um guia de prevenção para pais e educadores', NULL, '028.5 R', NULL, 'São Paulo', 'Câmara', NULL, NULL, NULL, NULL, NULL, 'ex.1', 'p.58', 1, '0.00', 1, '003.', NULL, 1),
(2429, '2022-01-01', 'Associação dos Advogados de São Paulo', 'Revista do Advogado', NULL, '340 R', NULL, 'São Paulo', 'AASP', 'n.151', 'set', '2021', NULL, NULL, 'ex.1', 'p.152', 1, '0.00', 1, '003.', NULL, 1),
(2430, '2022-01-01', 'UNICEF', 'O enfrentamento da exclusão escolar no Brasil', NULL, '306.43 R', '978.85.87685.36.0', 'Brasília-DF', 'UNICEF', NULL, NULL, '2014', NULL, NULL, 'ex.1', 'p.192', 1, '0.00', 1, '003.', NULL, 1),
(2431, '2022-01-01', 'Soluções em Gestão Pública - SAM', 'Revista SAM: Soluções em Direito Administrativo e Municipal - Ano I/ n.1', NULL, '342 R', '2674-6522', 'São Paulo', 'SGP Soluçõe em gestão púbica', 'n.1', 'jul', 'I - 2019', NULL, NULL, 'ex.1', 'p.204', 1, '0.00', 1, '003.', NULL, 1),
(2432, '2022-01-01', 'Soluções em Gestão Pública - SAM', 'Revista SAM: Soluções em Direito Administrativo e Municipal- Ano I/ n.5', NULL, '342 R', '2674-6522', 'São Paulo', 'SGP Soluçõe em gestão púbica', 'n.5', 'nov', 'I - 2019', NULL, NULL, 'ex.1', 'p.153', 1, '0.00', 1, '003.', NULL, 1),
(2433, '2022-01-01', 'Soluções em Gestão Pública - SAM', 'Revista SAM: Soluções em Direito Administrativo e Municipal- Ano I/ n.6', NULL, '342 R', '2674-6522', 'São Paulo', 'SGP Soluçõe em gestão púbica', 'n.6', 'dez', 'I - 2019', NULL, NULL, 'ex.1', 'p.156', 1, '0.00', 1, '003.', NULL, 1),
(2434, '2022-01-01', 'Soluções em Gestão Pública - SAM', 'Revista SAM: Soluções em Direito Administrativo e Municipal- Ano 2/ n.7', NULL, '342 R', '2674-6522', 'São Paulo', 'SGP Soluçõe em gestão púbica', 'n.7', 'jan', 'II - 2020', NULL, NULL, 'ex.1', 'p.145', 1, '0.00', 1, '003.', NULL, 1),
(2435, '2022-01-01', 'Soluções em Gestão Pública - SAM', 'Revista SAM: Soluções em Direito Administrativo e Municipal- Ano 2/ n.8', NULL, '342 R', '2674-6522', 'São Paulo', 'SGP Soluçõe em gestão púbica', 'n.8', 'fev', 'II - 2020', NULL, NULL, 'ex.1', 'p.164', 1, '0.00', 1, '003.', NULL, 1),
(2436, '2022-01-01', 'Soluções em Gestão Pública - SAM', 'Revista SAM: Soluções em Direito Administrativo e Municipal- Ano 2/ n.9', NULL, '342 R', '2674-6522', 'São Paulo', 'SGP Soluçõe em gestão púbica', 'n.9', 'mar', 'II - 2020', NULL, NULL, 'ex.1', 'p.171', 1, '0.00', 1, '003.', NULL, 1),
(2437, '2022-01-01', 'Soluções em Gestão Pública - SAM', 'Revista SAM: Soluções em Direito Administrativo e Municipal- Ano 2/ n.10', NULL, '342 R', '2674-6522', 'São Paulo', 'SGP Soluçõe em gestão púbica', 'n.10', 'abril', 'II - 2020', NULL, NULL, 'ex.1', 'p.140', 1, '0.00', 1, '003.', NULL, 1),
(2438, '2022-01-01', 'Soluções em Gestão Pública - SAM', 'Revista SAM: Soluções em Direito Administrativo e Municipal- Ano 2/ n.11', NULL, '342 R', '2674-6522', 'São Paulo', 'SGP Soluçõe em gestão púbica', 'n.11', 'maio', 'II - 2020', NULL, NULL, 'ex.1', 'p.123', 1, '0.00', 1, '003.', NULL, 1),
(2439, '2022-01-01', 'Soluções em Gestão Pública - SAM', 'Revista SAM: Soluções em Direito Administrativo e Municipal- Ano 2/ n.12', NULL, '342 R', '2674-6522', 'São Paulo', 'SGP Soluçõe em gestão púbica', 'n.12', 'jun', 'II - 2020', NULL, NULL, 'ex.1', 'p.127', 1, '0.00', 1, '003.', NULL, 1),
(2440, '2022-01-01', 'Soluções em Gestão Pública - SAM', 'Revista SAM: Soluções em Direito Administrativo e Municipal- Ano 2/ n.13', NULL, '342 R', '2674-6522', 'São Paulo', 'SGP Soluçõe em gestão púbica', 'n.13', 'jul', 'II - 2020', NULL, NULL, 'ex.1', 'p.134', 1, '0.00', 1, '003.', NULL, 1),
(2441, '2022-01-01', 'Soluções em Gestão Pública - SAM', 'Revista SAM: Soluções em Direito Administrativo e Municipal- Ano 2/ n.17', NULL, '342 R', '2674-6522', 'São Paulo', 'SGP Soluçõe em gestão púbica', 'n.17', 'nov', 'II - 2020', NULL, NULL, 'ex.1', 'p.132', 1, '0.00', 1, '003.', NULL, 1),
(2442, '2022-01-01', 'Senado Federal', 'Partidos Políticos brasileiros: programas e diretrizes doutrinárias', NULL, '324.2 R', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2014', NULL, NULL, 'ex.2', 'p.389', 1, '0.00', 1, '003.', NULL, 1),
(2443, '2022-01-01', 'CIEE - Centro de integração empresa-escola', 'Coleção: CIEE -A qualidade dos estágios e sua importancia sócio-profissional (Volume 79)', NULL, '379 R', '371.133', 'São Paulo', 'CIEE', 'n.79', NULL, '2005', NULL, 'v.79', 'ex.1', 'p.80', 1, '0.00', 1, '003.', NULL, 1),
(2444, '2022-01-01', 'CIEE - Márcio Fernando Elias Rosa ', 'Coleção CIEE - 128: O poder de investigação do Ministério Público ', NULL, '379 R', '347.963', 'São Paulo', 'CIEE', 'n.128', NULL, '2012', NULL, NULL, 'ex.1', 'p.74', 1, '0.00', 1, '003.', NULL, 1),
(2445, '2022-01-01', 'Ministério da Saúde', 'Atenção integral para mulheres e adolescentes em situação de violência doméstica e sexual', NULL, '362.1 R', NULL, 'DOAÇÃO', '**', NULL, NULL, '2011', NULL, NULL, 'ex.1', NULL, 0, '0.00', 1, '003.', NULL, 1),
(2446, '2022-01-01', 'WATERS, Lindsay', 'Inimigos da esperança : Publicar, perecer e o eclipse da erudição', NULL, '070.52', '654.413', 'DOAÇÃO', '**', NULL, NULL, '2011', NULL, NULL, 'ex.1', NULL, 0, '0.00', 1, '003.', NULL, 1),
(2447, '2022-01-01', 'Soluções em Gestão Pública - SGP', 'Revista SGP: Soluções em Licitações e contratos', NULL, '352 R', '2595-1947', 'São Paulo', 'SGP Soluçõe em gestão púbica', 'n.18', NULL, '2019', NULL, NULL, 'ex.1', 'p.162', 1, '0.00', 1, '003.', NULL, 1),
(2448, '2022-01-01', 'Elsbeth Léia Spode Becker', 'O estudo da paisagem na microbacia do Arroio do Veado através do sensoriamento remoto e do sintema de informção geográfica', '528.8', '550 TC', NULL, 'Santa Maria- RS', 'UNIVERSIDADE FEDERAL DE SANTA MARIA', NULL, NULL, '1999', NULL, NULL, 'ex.1', 'p. 133', 1, '0.00', 1, '003.', NULL, 1),
(2449, '2022-01-01', 'Lindsay Waters', 'Inimigos da esperança: publicar, perecer eo eclipse da erudição', '654.413', '070.52 TC', '85.7139.687.6', 'São Paulo', 'UNESP - Universidade Estadual Paulista', NULL, NULL, '2006', NULL, NULL, 'ex.1', 'p.95', 1, '0.00', 1, '003.', NULL, 1),
(2450, '2022-01-01', 'Claudio Luiz Ribeiro ', 'Curso de Petrologia', NULL, '550 TC', NULL, '***', '***', NULL, NULL, NULL, NULL, NULL, 'ex.1', 'p. 70', 1, '0.00', 1, '003.', NULL, 1),
(2451, '2022-01-01', '***', 'Curso de Mineralogia', NULL, '050 TC', NULL, '***', '***', NULL, NULL, NULL, NULL, NULL, 'ex.1', 'p. 92', 1, '0.00', 1, '003.', NULL, 1),
(2452, '2022-01-01', 'Thiago Santos Teófilo', 'Extensão da cultura da cana-de-açúcar confrontada com o zoneamento agroambiental utilizando análise supervisionada de imagem', NULL, ' 338.17TC', NULL, 'Botucatu-SP', 'UNESP - Universidade Estadual Paulista', NULL, NULL, '2012', NULL, NULL, 'ex.1', 'p. 69', 1, '0.00', 1, '003.', NULL, 1),
(2453, '2022-01-01', 'Alessandra Fagioli da Silva', 'Análise Multivariada de dados espaciais na classificação interpretativa de solos', NULL, '338.17 TC', NULL, 'Botucatu-SP', 'UNESP - Universidade Estadual Paulista', NULL, NULL, '2014', NULL, NULL, 'ex.1', 'p. 93', 1, '0.00', 1, '003.', NULL, 1),
(2454, '2022-01-01', 'Valéria Cazetta', 'Práticas educativas, processos de mapeamento e fotografias aéreas verticais: passagens e constituição de sabares', NULL, '338.17 TC', NULL, 'Rio Claro-SP', 'UNESP - Universidade Estadual Paulista', NULL, NULL, '2005', NULL, NULL, 'ex.1', 'p. 164', 1, '0.00', 1, '003.', NULL, 1),
(2455, '2022-01-01', 'BARRETO, Vicente Costa Pithon', ' Política Externa Independente (1961-1964): o parlamento e o caso do colonialismo português na África -  Coleções de Teses, Dissertações e Monografias de Servidores do Senado Federal 2011 ', NULL, '327.81 TC', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p. 110', 1, '0.00', 1, '003.', NULL, 1),
(2456, '2022-01-01', 'HEUSI, Érika de Castro ', ' Emendas individuais dos senadores ao orçamento anual: uma análise dos exercícios de 2008 e 2009 - Coleções de Teses, Dissertações e Monografias de Servidores do Senado Federal 2011 ', NULL, '328.378 TC', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p. 64', 1, '0.00', 1, '003.', NULL, 1),
(2457, '2022-01-01', 'NOVAES, Diogo Macedo de', ' A imunidade recíproca e o fenômeno da repercussão tributária - Coleções de Teses, Dissertações e Monografias de Servidores do Senado Federal 2011', NULL, '341.394.52 TC', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p.96', 1, '0.00', 1, '003.', NULL, 1),
(2458, '2022-01-01', 'ABDALA, Iza Beatriz Barreto', ' Gênero, adolescentia e políticas de ressocialização: um estudo com as internas do centro de Atendimento juvenil especializado - Caje, do Distrito Federal - Coleções de Teses, Dissertações e Monografias de Servidores do Senado Federal 2011', NULL, '305.235 TC', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p.160', 1, '0.00', 1, '003.', NULL, 1),
(2459, '2022-01-01', 'BARRETO, Vicente Costa Pithon', 'Política Externa Independente (1961-1964): o parlamento e o caso do colonialismo português na África - Coleções de Teses, Dissertações e Monografias de Servidores do Senado Federal 2011 ', NULL, '327.81 TC', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p.110', 1, '0.00', 1, '003.', NULL, 1),
(2460, '2022-01-01', 'HEISIN, Érika de Castro ', ' Emandas individuais dos senadores ao orçamento anual: uma análise dos exercícios de 2008 e 2009  - Coleções de Teses, Dissertações e Monografias de Servidores do Senado Federal 2011 ', NULL, '328.378 TC', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p.64', 1, '0.00', 1, '003.', NULL, 1),
(2461, '2022-01-01', 'PERIZINO, Luiz Fernando de Mello', ' A preponderância do poder executivo no processo orçamentário - Coleções de Teses, Dissertações e Monografias de Servidores do Senado Federal 2011 ', NULL, '341.383 TC', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p. 124', 1, '0.00', 1, '003.', NULL, 1),
(2462, '2022-01-01', 'MARTINS, André Ricardo Nunes', 'A polêmica construída, Racismo e discurso da imprensa sobre a política de cotas para negros  - Coleções de Teses, Dissertações e Monografias de Servidores do Senado Federal 2011 ', NULL, '378.198.29 TC', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p. 280', 1, '0.00', 1, '003.', NULL, 1),
(2463, '2022-01-01', 'CARVALHO, Edna de Souza ', ' O impacto da gestão de documentos no processo de produção digital da tv senado - Coleções de Teses, Dissertações e Monografias de Servidores do Senado Federal 2011 ', NULL, '342.05 TC', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p. 265', 1, '0.00', 1, '003.', NULL, 1),
(2464, '2022-01-01', 'BRANDÃO, Gulherme ', ' Da Constitucionalidade e legirimidade da iniciativa popular para a reforma da constituição Coleções de Teses, Dissertações e Monografias de Servidores do Senado Federal 2011 ', NULL, '341.234 TC', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2011', '1 ed.', NULL, 'ex.1', 'p. 103', 1, '0.00', 1, '003.', NULL, 1),
(2465, '2022-01-01', 'AGUIAR, Danilo Augusto Barbosa de', ' Tributação pró-compensativa: critérios especiais de tributação e desequilíbrios da concorrência - Coleções de Teses, Dissertações e Monografias de Servidores do Senado Federal 2011 ', NULL, '341.396 TC', '978.85.7018.527.3', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2013', '1 ed.', NULL, 'ex.1', 'p. 136', 1, '0.00', 1, '003.', NULL, 1),
(2466, '2022-01-01', 'Breno Gomes da Silva Mesquita', ' Auditoria interna de recursos humanos: temas de trabalho e perspectivas de atuação - Coleções de Teses, Dissertações e Monografias de Servidores do Senado Federal 2011 ', NULL, '658.3 TC', '978.85.7018.529.7', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2013', '1 ed.', NULL, 'ex.1', 'p. 49', 1, '0.00', 1, '003.', NULL, 1),
(2467, '2022-01-01', 'SILVA, Rafael Silveira e ', ' Construindo e gerenciando estrategicamente a agenda legislativa do Executivo: o fenômeno da Apropriação - Coleção de Teses, Dissertações e Monografias de Serviço do Senando Federal', NULL, '320.609.81 TC', '978.85.7018.531.0', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2013', '1 ed.', NULL, 'ex.1', 'p. 433', 1, '0.00', 1, '003.', NULL, 1),
(2468, '2022-01-01', 'ODON, Tiago Ivo', ' Construindo e gerenciando estrategicamente a agenda legislativa do Executivo: o fenômeno da Apropriação - Coleção de Teses, Dissertações e Monografias de Serviço do Senando Federal', NULL, '303.330.981 TC', '978.85.7018.526.6', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2013', '1 ed.', NULL, 'ex.1', 'p. 420', 1, '0.00', 1, '003.', NULL, 1),
(2469, '2022-01-01', 'TORRES, Luiz Eduardo da Silva', ' O (des)alinhamento das agendas políticas e burocrática no Senado: uma análise à luz da teoria da agância, sob o ponto de vista do agente - Coleção de Teses, Dissertações e Monografias de Serviço do Senando Federal', NULL, '342.05 TC', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2015', '1 ed.', NULL, 'ex.1', 'p. 404', 1, '0.00', 1, '003.', NULL, 1),
(2470, '2022-01-01', 'BARBOSA, Leonardo Garcia', 'Sociedade Anônima Simplificada -Coleção de Teses, Dissertações e Monografias de Serviço do Senando Federal', NULL, '306 TC', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2015', '1 ed.', NULL, 'ex.1', 'p. 176', 1, '0.00', 1, '003.', NULL, 1),
(2471, '2022-01-01', 'BARBOSA, Thiago de Azevedo', ' Da influência dos valores culturais na percepção e prática da corrupção: de perspectiva teóricas a evidências empíricas - ', NULL, '328 TC', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2015', '1 ed.', NULL, 'ex.1', 'p. 166', 1, '0.00', 1, '003.', NULL, 1),
(2472, '2022-01-01', 'ROMANINI, Roberta ', ' Interações entre coalizão e oposição nas comissões da Câmara dos Deputados e do Senado Federal - Coleção de Teses, Dissertações e Monografias de Serviço do Senando Federal', NULL, '342.05 TC', NULL, 'Brasília-DF', 'Senado Federal', NULL, NULL, '2015', '1 ed.', NULL, 'ex.1', 'p. 204', 1, '0.00', 1, '003.', NULL, 1),
(2473, '2022-01-01', 'CRUZ, Carlos Eduardo Rodrigues ', ': Sistema de Controle interno integrado da União (manuscrito) necessidade ou simplesmente obrigatoriedade? - Coleção de Teses, Dissertações e Monografias de Servidores do Senado Federal', NULL, '340 TC', '978.85.7018.335.4', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p. 136', 1, '0.00', 1, '003.', NULL, 1),
(2474, '2022-01-01', 'COELHO, Thales Chagas Machado', ' O princípio da Moderação e a Legitimação do controle judicial de constitucionalidade das leis - Coleção de Teses, Dissertações e Monografias de Servidores do Senado Federal', NULL, '340 TC', '978.85.7018.333.0', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p. 169', 1, '0.00', 1, '003.', NULL, 1),
(2475, '2022-01-01', 'CRUZ, Walesca Borges da Cunha e', 'Projeto de visita do parlamento brasileiro: construção de uma nova imagemColeção de Teses, Dissertações e Monografias de Servidores do Senado Federal', NULL, '320 TC', '978.85.7018.335.4', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p. 76', 1, '0.00', 1, '003.', NULL, 1),
(2476, '2022-01-01', 'NOVELLI, Ana Lucia Coelho Romero ', ' Imagens Cruzadas: A opinião pública e o Congresso Nacional  - Coleção de Teses, Dissertações e Monografias de Servidores do Senado Federal', NULL, '320 TC', '978.85.7018.335.4', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p. 235', 1, '0.00', 1, '003.', NULL, 1),
(2477, '2022-01-01', 'MAGALHÃES, Flávia Cristina Mascarenhas ', ' A judicialização da política e o Direito eleitoral brasileiro no preríodo 2002-2008 - Coleção de Teses, Dissertações e Monografias de Servidores do Senado Federal', NULL, ' 340 TC', '978.85.7018.330.9', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p. 97', 1, '0.00', 1, '003.', NULL, 1),
(2478, '2022-01-01', 'BERNARDES, Mellina Motta de Paula ', 'Análise da ocorrência de interfaces entre as competências do Senado Federal: um estudo de caso sobre o projeto de Resolução sugerido pelo relatório final da CPI dos títulos públilcos - Coleção de Teses, Dissertações e Monografias de Servidores do Senado Federal', NULL, '340 TC', '978.85.7018.334.7', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p. 188', 1, '0.00', 1, '003.', NULL, 1),
(2479, '2022-01-01', 'DRUMMOND, Maria Claudia ', 'A democracia desconstruída. O déficit democrático nas relações internacionais e os parlamentos da integração - Coleção de Teses, Dissertações e Monografias de Servidores do Senado Federal', NULL, '320 TC', '978.85.7018.332.3', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p. 415', 1, '0.00', 1, '003.', NULL, 1),
(2480, '2022-01-01', 'FREITAS, Luiz Carlos Santana de ', ' O controle normativo da mídia do congresso nacional: critério de noticiabilidade e garantia do uso republicsno dos veículos legislativos de comunicação de massa - Coleção de Teses, Dissertações e Monografias de Servidores do Senado Federal: ', NULL, ' 340 TC', '978.85.7018.331.6', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2010', '1 ed.', NULL, 'ex.1', 'p. 95', 1, '0.00', 1, '003.', NULL, 1),
(2481, '2022-01-01', 'SOUZA, Paulo Fernando Mohn e', ' A subsidiariedade como princípio de organização do Estado e sua aplicação no federalismo - Coleção de Teses, Dissertações e Monografias de Servidores do Senado Federal', NULL, '340 TC', '978.85.7018.337.8', 'Brasília-DF', 'Senado Federal', NULL, NULL, '2010', '1 ed.', NULL, 'ex.2', 'p. 319', 1, '0.00', 1, '003.', NULL, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `libraryreservations`
--

CREATE TABLE `libraryreservations` (
  `id` int NOT NULL,
  `publicationId` int NOT NULL,
  `libUserId` int NOT NULL,
  `reservationDatetime` datetime NOT NULL,
  `borrowedPubId` int DEFAULT NULL,
  `invalidatedDatetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='Tabela de reservas para empréstimo de publicações da bibl.';

--
-- Extraindo dados da tabela `libraryreservations`
--

INSERT INTO `libraryreservations` (`id`, `publicationId`, `libUserId`, `reservationDatetime`, `borrowedPubId`, `invalidatedDatetime`) VALUES
(1, 4, 3, '2022-10-01 17:56:55', 5, '2022-10-01 17:58:40');

-- --------------------------------------------------------

--
-- Estrutura da tabela `libraryusers`
--

CREATE TABLE `libraryusers` (
  `id` int NOT NULL,
  `name` varbinary(140) NOT NULL,
  `CMI_Department` varbinary(140) DEFAULT NULL,
  `CMI_RegNumber` varbinary(140) DEFAULT NULL,
  `telephone` varbinary(100) DEFAULT NULL,
  `email` varbinary(140) DEFAULT NULL,
  `typeId` int NOT NULL,
  `agreesWithConsentForm` tinyint(1) NOT NULL DEFAULT '0',
  `consentFormTermId` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='Usuários da biblioteca';

--
-- Extraindo dados da tabela `libraryusers`
--

INSERT INTO `libraryusers` (`id`, `name`, `CMI_Department`, `CMI_RegNumber`, `telephone`, `email`, `typeId`, `agreesWithConsentForm`, `consentFormTermId`) VALUES
(1, 0x730b2cc39ae3db8aaa75251a6353a6035fcdfe260fb9a4559a462808536c830b806cea5792fa87a305843f8d8eaee9cc, 0xab77bc5376f7387c2480341b366a06b10003cfadb6491a8cbac29fd1b1a5ec4f, 0x296ff5bcc3ed627e24bcd93820077ffa, 0x7f65d87c3c8e376a1822877b8c567fafd07843060ebb44dada515699da1442bd, 0xd0aababa1ee486358ea1606d4d8a3bf43b37edcb8d6b9549e0a50b30d9cad42c, 2, 1, 2),
(3, 0x3c0f9a02d8b944923189910d0b93b91dd07843060ebb44dada515699da1442bd, 0xd07843060ebb44dada515699da1442bd, 0xd07843060ebb44dada515699da1442bd, 0xa46da4fda0dd98931b3bddf97ba0e52e, 0x59156c65b1150e8b8a89ef7fca5ec6047b0f991e1b52e693b3eae35df4167ad5, 4, 1, 1),
(4, 0xfc5df2449d5eed247d6918971a7481fb, 0xd07843060ebb44dada515699da1442bd, 0xd07843060ebb44dada515699da1442bd, 0x2c154379ce0a2bf8a1caac45f26ee12f, 0x419ac6dce523d295b5bc27e3c23d1b49, 5, 1, 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `mailing`
--

CREATE TABLE `mailing` (
  `id` int UNSIGNED NOT NULL,
  `email` varbinary(140) NOT NULL,
  `name` varbinary(140) NOT NULL,
  `eventId` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='Lista de e-mails';

--
-- Extraindo dados da tabela `mailing`
--

INSERT INTO `mailing` (`id`, `email`, `name`, `eventId`) VALUES
(1, 0x28fae367060eb0be260fa074b53ed700019964d75705021bee716092d0ed9895, 0x8d9425b5f3be7dc92fbd59b214a28426, 2),
(2, 0x0990e649d1af757995d6f2ce5b829f26c242737db1b1956abb7b3c20161fe862, 0x6caa5501b326811efa09a29ebdbc2267, 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `permissions`
--

CREATE TABLE `permissions` (
  `permMod` varchar(5) NOT NULL,
  `permId` int NOT NULL,
  `permDesc` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Extraindo dados da tabela `permissions`
--

INSERT INTO `permissions` (`permMod`, `permId`, `permDesc`) VALUES
('ARTM', 1, 'Museu de Arte: Ver obras de arte'),
('ARTM', 2, 'Museu de Arte: Criar obras de arte'),
('ARTM', 3, 'Museu de Arte: Editar obras de arte'),
('ARTM', 4, 'Museu de Arte: Excluir obras de arte'),
('CALEN', 1, 'Agenda: Visualizar mês'),
('CALEN', 2, 'Agenda: Visualizar dia'),
('CALEN', 3, 'Agenda: Criar data/evento'),
('CALEN', 4, 'Agenda: Editar data/evento'),
('CALEN', 5, 'Agenda: Excluir data/evento'),
('CHKLS', 1, 'Checklists: Criar/Editar modelo de checklist'),
('CHKLS', 2, 'Checklists: Excluir modelo de checklist'),
('CHKLS', 3, 'Checklists: Ver checklists'),
('CHKLS', 4, 'Checklists: Preencher checklists'),
('ENUM', 1, 'Editar enumerador: Tipos de evento'),
('ENUM', 2, 'Editar enumeradores: Inscrição em eventos'),
('ENUM', 3, 'Editar enumeradores: Biblioteca'),
('EVENT', 1, 'Eventos: Criar eventos'),
('EVENT', 2, 'Eventos: Editar eventos'),
('EVENT', 3, 'Eventos: Excluir eventos'),
('EVENT', 4, 'Eventos: Ver eventos'),
('EVENT', 5, 'Eventos: Excluir presença das listas de presença'),
('EVENT', 6, 'Eventos: Excluir inscrição de evento'),
('EVENT', 7, 'Eventos: Alterar nome de inscritos'),
('EVENT', 8, 'Eventos: Marcar presença manualmente'),
('EVENT', 9, 'Eventos: Alterar link para o termo de consentimento do uso de dados pessoais'),
('EVENT', 10, 'Eventos: Editar outras configurações gerais'),
('EVENT', 11, 'Eventos: Ver certificados emitidos'),
('EVENT', 12, 'Eventos: Criar inscrição'),
('EVENT', 13, 'Eventos: Editar locais de eventos'),
('LIBR', 2, 'Biblioteca: Ver publicações'),
('LIBR', 3, 'Biblioteca: Editar publicações'),
('LIBR', 4, 'Biblioteca: Excluir publicações'),
('LIBR', 5, 'Biblioteca: Ver usuários'),
('LIBR', 6, 'Biblioteca: Editar usuários'),
('LIBR', 7, 'Biblioteca: Excluir usuários'),
('LIBR', 8, 'Biblioteca: Criar usuários'),
('LIBR', 9, 'Biblioteca: Criar publicações'),
('LIBR', 10, 'Biblioteca: Ver empréstimos'),
('LIBR', 11, 'Biblioteca: Finalizar empréstimos'),
('LIBR', 12, 'Biblioteca: Ver reservas'),
('LIBR', 13, 'Biblioteca: Criar empréstimos'),
('LIBR', 14, 'Biblioteca: Excluir reservas'),
('LIBR', 15, 'Biblioteca: Criar reservas'),
('LIBR', 16, 'Biblioteca: Alterar link para o termo de consentimento de uso de dados pessoais'),
('MAIL', 1, 'Mailing: Ver mailing'),
('MAIL', 2, 'Mailing: Excluir e-mail do mailing'),
('PROFE', 1, 'Docentes: Ver docentes'),
('PROFE', 2, 'Docentes: Editar docentes'),
('PROFE', 3, 'Docentes: Excluir docentes'),
('PROFE', 4, 'Docentes: Alterar link para o termo de consentimento do uso de dados pessoais'),
('PROFE', 5, 'Docentes: Ver propostas de trabalho'),
('PROFE', 6, 'Docentes: Aprovar ou rejeitar propostas de trabalho'),
('PROFE', 7, 'Docentes: Editar propostas de trabalho'),
('PROFE', 8, 'Docentes: Criar proposta de trabalho'),
('PROFE', 9, 'Docentes: Excluir proposta de trabalho'),
('PROFE', 10, 'Docentes: Criar ficha de trabalho'),
('PROFE', 11, 'Docentes: Editar ficha de trabalho'),
('PROFE', 12, 'Docentes: Excluir ficha de trabalho'),
('PROFE', 13, 'Docentes: Ver ficha de trabalho'),
('SOCN', 1, 'Redes sociais: Alterar endereços de redes sociais'),
('SRVEY', 1, 'Pesquisas de Satisfação: Criar modelo de pesquisa'),
('SRVEY', 2, 'Pesquisas de Satisfação: Editar modelo de pesquisa'),
('SRVEY', 3, 'Pesquisas de Satisfação: Excluir modelo de pesquisa'),
('SRVEY', 4, 'Pesquisas de Satisfação: Ver pesquisas preenchidas'),
('SRVEY', 5, 'Pesquisas de Satisfação: Excluir pesquisas preenchidas'),
('TERMS', 1, 'Termos: Visualizar termos'),
('TERMS', 2, 'Termos: Criar termos'),
('TERMS', 3, 'Termos: Editar termos'),
('TERMS', 4, 'Termos: Excluir termos'),
('TRAIT', 1, 'Traços: Ver traços'),
('TRAIT', 2, 'Traços: Criar traços'),
('TRAIT', 3, 'Traços: Editar traços'),
('TRAIT', 4, 'Traços: Excluir traços'),
('USERS', 1, 'Gerenciar usuários e permissões'),
('VMLEG', 1, 'Vereador Mirim: Ver legislaturas'),
('VMLEG', 2, 'Vereador Mirim: Criar legislaturas'),
('VMLEG', 3, 'Vereador Mirim: Editar legislaturas'),
('VMLEG', 4, 'Vereador Mirim: Excluir legislaturas'),
('VMPAR', 1, 'Vereador Mirim: Ver responsáveis'),
('VMPAR', 2, 'Vereador Mirim: Criar responsáveis'),
('VMPAR', 3, 'Vereador Mirim: Editar responsáveis'),
('VMPAR', 4, 'Vereador Mirim: Excluir responsáveis'),
('VMPTY', 1, 'Vereador Mirim: Ver partidos'),
('VMPTY', 2, 'Vereador Mirim: Criar partidos'),
('VMPTY', 3, 'Vereador Mirim: Editar partidos'),
('VMPTY', 4, 'Vereador Mirim: Excluir partidos'),
('VMSTU', 1, 'Vereador Mirim: Ver vereadores'),
('VMSTU', 2, 'Vereador Mirim: Criar vereadores'),
('VMSTU', 3, 'Vereador Mirim: Editar vereadores'),
('VMSTU', 4, 'Vereador Mirim: Excluir vereadores');

-- --------------------------------------------------------

--
-- Estrutura da tabela `presencerecords`
--

CREATE TABLE `presencerecords` (
  `id` int UNSIGNED NOT NULL,
  `eventId` int UNSIGNED NOT NULL,
  `eventDateId` int UNSIGNED NOT NULL,
  `subscriptionId` int UNSIGNED DEFAULT NULL,
  `email` varbinary(140) DEFAULT NULL,
  `name` varbinary(140) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='Lista global de presenças, sempre associadas a uma data de evento';

--
-- Extraindo dados da tabela `presencerecords`
--

INSERT INTO `presencerecords` (`id`, `eventId`, `eventDateId`, `subscriptionId`, `email`, `name`) VALUES
(1, 2, 3, 1, NULL, NULL),
(2, 2, 4, 1, NULL, NULL),
(5, 5, 23, 3, NULL, NULL),
(6, 2, 3, 4, NULL, NULL),
(11, 14, 22, NULL, 0x28fae367060eb0be260fa074b53ed700019964d75705021bee716092d0ed9895, 0x3c0f9a02d8b944923189910d0b93b91dd07843060ebb44dada515699da1442bd),
(12, 2, 10, 1, NULL, NULL),
(13, 2, 7, 1, NULL, NULL),
(14, 2, 4, 4, NULL, NULL),
(15, 2, 6, 4, NULL, NULL),
(16, 2, 10, 4, NULL, NULL),
(17, 2, 6, 1, NULL, NULL),
(18, 2, 10, 5, NULL, NULL),
(19, 2, 10, 10, NULL, NULL),
(20, 2, 10, 11, NULL, NULL),
(21, 2, 7, 10, NULL, NULL),
(22, 2, 6, 10, NULL, NULL),
(23, 2, 4, 10, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `professorcertificates`
--

CREATE TABLE `professorcertificates` (
  `id` int NOT NULL,
  `workSheetId` int NOT NULL,
  `dateTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='Certificados de docentes emitidos.';

--
-- Extraindo dados da tabela `professorcertificates`
--

INSERT INTO `professorcertificates` (`id`, `workSheetId`, `dateTime`) VALUES
(1, 7, '2022-08-16 19:43:26'),
(2, 6, '2022-08-27 17:50:26');

-- --------------------------------------------------------

--
-- Estrutura da tabela `professordocsattachments`
--

CREATE TABLE `professordocsattachments` (
  `id` int NOT NULL,
  `professorId` int UNSIGNED NOT NULL,
  `docType` varchar(30) NOT NULL,
  `fileName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='Tabela de registro de documentos pessoais de docentes.';

--
-- Extraindo dados da tabela `professordocsattachments`
--

INSERT INTO `professordocsattachments` (`id`, `professorId`, `docType`, `fileName`) VALUES
(40, 1, 'address', 'Endereco.jpg'),
(42, 1, 'rg', 'image.jpg');

-- --------------------------------------------------------

--
-- Estrutura da tabela `professors`
--

CREATE TABLE `professors` (
  `id` int UNSIGNED NOT NULL,
  `name` varbinary(140) NOT NULL,
  `email` varbinary(140) NOT NULL,
  `telephone` varbinary(140) NOT NULL,
  `schoolingLevel` varbinary(140) NOT NULL,
  `topicsOfInterest` varbinary(320) DEFAULT NULL,
  `lattesLink` varbinary(140) DEFAULT NULL,
  `collectInss` tinyint(1) DEFAULT NULL,
  `personalDocsJson` varbinary(3900) DEFAULT NULL,
  `homeAddressJson` varbinary(3900) DEFAULT NULL,
  `miniResumeJson` varbinary(3900) DEFAULT NULL,
  `bankDataJson` varbinary(3900) DEFAULT NULL,
  `agreesWithConsentForm` tinyint(1) NOT NULL COMMENT 'Concordância com o termo de consentimento para tratamento de dados pessoais.',
  `consentForm` varchar(120) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'Versão do termo de consentimento com o qual concordou.',
  `registrationDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='Tabela de docentes/palestrantes';

--
-- Extraindo dados da tabela `professors`
--

INSERT INTO `professors` (`id`, `name`, `email`, `telephone`, `schoolingLevel`, `topicsOfInterest`, `lattesLink`, `collectInss`, `personalDocsJson`, `homeAddressJson`, `miniResumeJson`, `bankDataJson`, `agreesWithConsentForm`, `consentForm`, `registrationDate`) VALUES
(1, 0x730b2cc39ae3db8aaa75251a6353a6035fcdfe260fb9a4559a462808536c830bb2f9687be229069deddc30a3318751bb, 0xd0aababa1ee486358ea1606d4d8a3bf43b37edcb8d6b9549e0a50b30d9cad42c, 0x7f65d87c3c8e376a1822877b8c567fafd07843060ebb44dada515699da1442bd, 0xc9c704b56f21ef92d162d25b8319a41e, NULL, NULL, 1, 0xa1b8cc3a37e98c2f9c065c292974446eaed1d238cdc14c629d62d3e303a8e153cfcb99fd05a348450b7d731d4829c68c7826e35671ec12209a6c5f142503ced629daf4533a539824af9daf080ae906220480c86f6150da67aaa9eea9f5899228, 0xca4c879905f70330e1084b7872ed73b53df9f84257188b7d2ed5ca1dcb2c2b24ffbc0d7c43931c71128e350a170b3f7a307e486aae85efe62556d9a680d7cc8beeefcd82dd6ae32e69cc68a517850023f4fffb4f69d3b1f1f4b83e9c0528088a4a50fdd4af1bbabb1a3fb8e749e46fbff8a4d32c8550bceb5bdb873440d4e768, 0x0fea4ae95a04eea81060e29f49ee9c4fb0381bb93c85edaae8698da16871f7cf48c0ddba4f1d5dfcb5bfc707012dc03c0fa5271b5d4df982a5df66972dbae5d52825a06a76108954e05b571ce2e5b9d9, 0xbd8626b6f42799bc59414086197be888ddd1e0975190f704c7f235cb587b589ffd695e5c8b518b0ec0d558114c93bed23b78c76d8aa4a7cd8c06ae205c725391edb1b513930bf5b4da6de12dbe9af0be515dc8e530da37909c016a9156ab7a7232bddfb3da35e0b8514c47a62b438d62, 1, '2', '2022-02-26 21:35:22'),
(2, 0x3c0f9a02d8b944923189910d0b93b91dd07843060ebb44dada515699da1442bd, 0x28fae367060eb0be260fa074b53ed700019964d75705021bee716092d0ed9895, 0xe24f1c833102e72d984cad5f0a7504a73c2f1cea6ccb1e14befe08e34ca5d19c, 0xc9c704b56f21ef92d162d25b8319a41e, NULL, NULL, 0, 0x0d729a945f61b33b00fd951aafcebc5700d29bd88a0d5123bf2d173c4226ac9035c92eb46061a291b9c69329877de226882d8b0ef1230a1aa028cd3fa04bb514, 0x76b89adbbb125b943ce1c0243df408b8ee840786e97a3b6b3b80fde96abfeaebd9fe6a86765198073b4421f86078b43bf90a8892caee663248aa36bf2a8d34ad3c8f068ed0de0c19abda8f5416d33931d07843060ebb44dada515699da1442bd, 0xd7ff000950efb276ce7494ef0722061a613b39ebd23b49fb83c1f7ac04866cb42e8c294a72c3b5c4b05cc278bf09468bed397733740e70f43433428374d4a48f, 0x2d1256c1be4506ef894cca66aed96afccc9c3cdfd85d57cb8d12d41a7bb024ff9dd0cf1477ccc3389f08bdf9d5c32f9fb8de5bdd875ca468a8f53e015ec6943f, 1, '1', '2022-02-26 21:37:56'),
(4, 0xa042a6be5273474b32a0aa5da784a1be3b0567ee438216090d243ea36ddf8edcb6342eb5246b8811299dd3b6ee97c759, 0xb7a44a87cec48eaeccded25b3383cb2788bd49198f078d9c9c11ed22d63d63aa, 0x1b278ca0c77933265a3b36836a8486cd, 0x55fb084277aa990d1e5fa43dfdc0c9db, NULL, NULL, 0, 0x0d729a945f61b33b00fd951aafcebc5700d29bd88a0d5123bf2d173c4226ac9035c92eb46061a291b9c69329877de226882d8b0ef1230a1aa028cd3fa04bb514, 0x76b89adbbb125b943ce1c0243df408b8ee840786e97a3b6b3b80fde96abfeaebd9fe6a86765198073b4421f86078b43bf90a8892caee663248aa36bf2a8d34ad3c8f068ed0de0c19abda8f5416d33931d07843060ebb44dada515699da1442bd, 0xd7ff000950efb276ce7494ef0722061a613b39ebd23b49fb83c1f7ac04866cb42e8c294a72c3b5c4b05cc278bf09468bed397733740e70f43433428374d4a48f, 0x2d1256c1be4506ef894cca66aed96afccc9c3cdfd85d57cb8d12d41a7bb024ff9dd0cf1477ccc3389f08bdf9d5c32f9fb8de5bdd875ca468a8f53e015ec6943f, 0, '1', '2022-06-23 19:13:49');

-- --------------------------------------------------------

--
-- Estrutura da tabela `professorsotps`
--

CREATE TABLE `professorsotps` (
  `id` int UNSIGNED NOT NULL,
  `professorId` int UNSIGNED NOT NULL,
  `oneTimePassword` varchar(300) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `expiryDateTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='OTPs de docentes ao fazerem login';

-- --------------------------------------------------------

--
-- Estrutura da tabela `professorworkdocsignatures`
--

CREATE TABLE `professorworkdocsignatures` (
  `id` int NOT NULL,
  `workSheetId` int DEFAULT NULL,
  `docSignatureId` int UNSIGNED NOT NULL,
  `professorId` int UNSIGNED DEFAULT NULL,
  `signatureDateTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='Assinaturas de docentes nas documentações de empenho';

--
-- Extraindo dados da tabela `professorworkdocsignatures`
--

INSERT INTO `professorworkdocsignatures` (`id`, `workSheetId`, `docSignatureId`, `professorId`, `signatureDateTime`) VALUES
(10, 7, 1, 1, '2022-08-16 19:44:22'),
(11, 7, 2, 1, '2022-08-16 19:44:22'),
(12, 7, 3, 1, '2022-08-16 19:44:22'),
(13, 7, 4, 1, '2022-08-16 19:44:22');

-- --------------------------------------------------------

--
-- Estrutura da tabela `professorworkproposals`
--

CREATE TABLE `professorworkproposals` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `infosFields` json DEFAULT NULL,
  `moreInfos` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `ownerProfessorId` int UNSIGNED DEFAULT NULL,
  `fileExtension` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `isApproved` tinyint(1) DEFAULT NULL,
  `feedbackMessage` text,
  `registrationDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Extraindo dados da tabela `professorworkproposals`
--

INSERT INTO `professorworkproposals` (`id`, `name`, `infosFields`, `moreInfos`, `ownerProfessorId`, `fileExtension`, `isApproved`, `feedbackMessage`, `registrationDate`) VALUES
(3, 'Proposta teste', '{\"contents\": \"bbbb\", \"resources\": \"dddd\", \"evaluation\": \"eeee\", \"objectives\": \"aaaa1111\", \"procedures\": \"cccc\"}', 'bbbb', 2, NULL, 1, '', '2022-08-26 19:58:55'),
(4, 'Proposta teste 2', NULL, 'stgdhfg gh ghj fghk hgj khj ', 1, 'pdf', 1, NULL, '2022-08-16 18:59:29'),
(5, 'Proposta enviada pelo docente', NULL, 'Lorem1 ipsum dolor sit amet, consectetur adipiscing elit. Cras vitae dui eget justo rhoncus condimentum. Donec vitae sem ullamcorper, sollicitudin mi et, ornare lorem. Nulla facilisi. Vestibulum vel velit nulla. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce at nulla id tellus ullamcorper gravida quis non ligula. In at urna ligula. Donec ut vulputate enim. Etiam aliquet, neque ac bibendum pretium, odio sapien consequat eros, eget vulputate massa nisi a eros.', 1, 'pdf', 1, 'Ok!', '2022-08-17 20:27:16'),
(8, 'fgdhdhdgh11223333', '{\"contents\": \"\", \"resources\": \"\", \"evaluation\": \"gfjfhjf\", \"objectives\": \"\", \"procedures\": \"cccc\"}', NULL, 1, NULL, 1, 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', '2022-08-26 20:39:12');

-- --------------------------------------------------------

--
-- Estrutura da tabela `professorworksheets`
--

CREATE TABLE `professorworksheets` (
  `id` int NOT NULL,
  `professorId` int UNSIGNED DEFAULT NULL,
  `professorWorkProposalId` int UNSIGNED DEFAULT NULL,
  `eventId` int UNSIGNED DEFAULT NULL,
  `paymentInfosJson` json NOT NULL,
  `professorTypeId` int NOT NULL,
  `paymentTableId` int NOT NULL,
  `paymentLevelId` int NOT NULL,
  `classTime` decimal(10,5) NOT NULL,
  `paymentSubsAllowanceTableId` int DEFAULT NULL,
  `paymentSubsAllowanceLevelId` int DEFAULT NULL,
  `paymentSubsAllowanceClassTime` decimal(10,5) DEFAULT NULL,
  `participationEventDataJson` json DEFAULT NULL,
  `professorCertificateText` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `certificateBgFile` varchar(255) COLLATE utf32_unicode_ci NOT NULL,
  `professorDocTemplateId` int DEFAULT NULL,
  `signatureDate` date NOT NULL,
  `referenceMonth` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci COMMENT='Tabela para fichas de trabalho de docentes';

--
-- Extraindo dados da tabela `professorworksheets`
--

INSERT INTO `professorworksheets` (`id`, `professorId`, `professorWorkProposalId`, `eventId`, `paymentInfosJson`, `professorTypeId`, `paymentTableId`, `paymentLevelId`, `classTime`, `paymentSubsAllowanceTableId`, `paymentSubsAllowanceLevelId`, `paymentSubsAllowanceClassTime`, `participationEventDataJson`, `professorCertificateText`, `certificateBgFile`, `professorDocTemplateId`, `signatureDate`, `referenceMonth`) VALUES
(1, 1, NULL, 10, '{\"companies\": [{\"cnpj\": \"204101211\", \"name\": \"stdfdfhghdthyjhg111\", \"wage\": \"3100\", \"collectedInss\": \"200\"}, {\"cnpj\": \"\", \"name\": \"\", \"wage\": \"\", \"collectedInss\": \"\"}, {\"cnpj\": \"\", \"name\": \"\", \"wage\": \"\", \"collectedInss\": \"\"}, {\"cnpj\": \"\", \"name\": \"\", \"wage\": \"\", \"collectedInss\": \"\"}], \"collectInss\": \"1\", \"inssPeriodEnd\": \"2021-10-02\", \"professorTypes\": [{\"name\": \"Avaliador\", \"paymentMultiplier\": 1}, {\"name\": \"Conteudista\", \"paymentMultiplier\": 1}, {\"name\": \"Coordenador\", \"paymentMultiplier\": 0.7}, {\"name\": \"Facilitador de Aprendizagem\", \"paymentMultiplier\": 1}, {\"name\": \"Orientador\", \"paymentMultiplier\": 1}], \"inssPeriodBegin\": \"2021-10-02\", \"paymentLevelTables\": [{\"levels\": [{\"name\": \"Sem titulação/graduado\", \"classTimeValue\": 101.1}, {\"name\": \"Especialista\", \"classTimeValue\": 134.82}, {\"name\": \"Mestre\", \"classTimeValue\": 168.5}, {\"name\": \"Doutor\", \"classTimeValue\": 188.75}], \"tableName\": \"Quadro de índice de valoração da hora-aula presencial\", \"tableTypeTagName\": \"presencial\"}, {\"levels\": [{\"name\": \"Sem titulação/graduado\", \"classTimeValue\": 50.55}, {\"name\": \"Especialista\", \"classTimeValue\": 67.41}, {\"name\": \"Mestre\", \"classTimeValue\": 84.25}, {\"name\": \"Doutor\", \"classTimeValue\": 94.37}], \"tableName\": \"Quadro de índice de valoração da hora-aula remota (lives, webinar, cursos EAD em geral\", \"tableTypeTagName\": \"on-line\"}]}', 3, 0, 2, '2.00000', 0, 2, '2.00000', '{\"dates\": \"10 de julho de 2022\", \"times\": \"10h às 12h\", \"workTime\": \"2h\", \"activityName\": \"Live teste teste11\"}', 'Ministrou palestra durante a Live teste teste, promovida pela Câmara Municipal de Itapevi, por meio da Escola do Parlamento \"Doutor Osmar de Souza\", com o tema ***********, no dia 10 de julho de 2022, das 10h às 12h, com carga horária de 2 horas.', 'certbg2021.jpg', 1, '2022-08-01', '2022-08-01'),
(3, 1, NULL, NULL, '{\"companies\": [{\"cnpj\": \"\", \"name\": \"\", \"wage\": \"\", \"collectedInss\": \"\"}, {\"cnpj\": \"\", \"name\": \"\", \"wage\": \"\", \"collectedInss\": \"\"}, {\"cnpj\": \"\", \"name\": \"\", \"wage\": \"\", \"collectedInss\": \"\"}, {\"cnpj\": \"\", \"name\": \"\", \"wage\": \"\", \"collectedInss\": \"\"}], \"collectInss\": \"0\", \"inssPeriodEnd\": \"\", \"professorTypes\": [{\"name\": \"Avaliador\", \"paymentMultiplier\": 1}, {\"name\": \"Conteudista\", \"paymentMultiplier\": 1}, {\"name\": \"Coordenador\", \"paymentMultiplier\": 0.7}, {\"name\": \"Facilitador de Aprendizagem\", \"paymentMultiplier\": 1}, {\"name\": \"Orientador\", \"paymentMultiplier\": 1}], \"inssPeriodBegin\": \"\", \"paymentLevelTables\": [{\"levels\": [{\"name\": \"Sem titulação/graduado\", \"classTimeValue\": 101.1}, {\"name\": \"Especialista\", \"classTimeValue\": 134.82}, {\"name\": \"Mestre\", \"classTimeValue\": 168.5}, {\"name\": \"Doutor\", \"classTimeValue\": 188.75}], \"tableName\": \"Quadro de índice de valoração da hora-aula presencial\", \"tableTypeTagName\": \"presencial\"}, {\"levels\": [{\"name\": \"Sem titulação/graduado\", \"classTimeValue\": 50.55}, {\"name\": \"Especialista\", \"classTimeValue\": 67.41}, {\"name\": \"Mestre\", \"classTimeValue\": 84.25}, {\"name\": \"Doutor\", \"classTimeValue\": 94.37}], \"tableName\": \"Quadro de índice de valoração da hora-aula remota (lives, webinar, cursos EAD em geral\", \"tableTypeTagName\": \"on-line\"}]}', 2, 1, 1, '2.00000', NULL, NULL, NULL, '{\"dates\": \"\", \"times\": \"\", \"workTime\": \"\", \"activityName\": \"Curso YYYY231\"}', NULL, 'certbg2021.jpg', 1, '2022-07-30', '2022-07-01'),
(4, 1, 3, 5, '{\"collectInss\": \"1\", \"inssPercent\": \"11\", \"professorTypes\": [{\"name\": \"Avaliador\", \"paymentMultiplier\": 1}, {\"name\": \"Conteudista\", \"paymentMultiplier\": 1}, {\"name\": \"Coordenador\", \"paymentMultiplier\": 0.7}, {\"name\": \"Facilitador de Aprendizagem\", \"paymentMultiplier\": 1}, {\"name\": \"Orientador\", \"paymentMultiplier\": 1}], \"paymentLevelTables\": [{\"levels\": [{\"name\": \"Sem titulação/graduado\", \"classTimeValue\": 101.1}, {\"name\": \"Especialista\", \"classTimeValue\": 134.82}, {\"name\": \"Mestre\", \"classTimeValue\": 168.5}, {\"name\": \"Doutor\", \"classTimeValue\": 188.75}], \"tableName\": \"Quadro de índice de valoração da hora-aula presencial\"}, {\"levels\": [{\"name\": \"Sem titulação/graduado\", \"classTimeValue\": 50.55}, {\"name\": \"Especialista\", \"classTimeValue\": 67.41}, {\"name\": \"Mestre\", \"classTimeValue\": 84.25}, {\"name\": \"Doutor\", \"classTimeValue\": 94.37}], \"tableName\": \"Quadro de índice de valoração da hora-aula remota (lives, webinar, cursos EAD em geral\"}]}', 3, 0, 1, '3.00000', NULL, NULL, NULL, '{\"dates\": \"\", \"times\": \"\", \"workTime\": \"\", \"activityName\": \"Lorem ãpsum dolor sit amet123\"}', NULL, 'certbg2021.jpg', NULL, '2022-07-30', '2022-07-01'),
(6, 1, 4, 2, '{\"companies\": [{\"cnpj\": \"2041012\", \"name\": \"stdfdfhghdthyjhg111\", \"wage\": \"3100\", \"collectedInss\": \"200\"}, {\"cnpj\": \"\", \"name\": \"\", \"wage\": \"\", \"collectedInss\": \"\"}, {\"cnpj\": \"\", \"name\": \"\", \"wage\": \"\", \"collectedInss\": \"\"}, {\"cnpj\": \"\", \"name\": \"\", \"wage\": \"\", \"collectedInss\": \"\"}], \"collectInss\": \"1\", \"inssPeriodEnd\": \"2022-06-23\", \"professorTypes\": [{\"name\": \"Avaliador\", \"paymentMultiplier\": 1}, {\"name\": \"Conteudista\", \"paymentMultiplier\": 1}, {\"name\": \"Coordenador\", \"paymentMultiplier\": 0.7}, {\"name\": \"Facilitador de Aprendizagem\", \"paymentMultiplier\": 1}, {\"name\": \"Orientador\", \"paymentMultiplier\": 1}], \"inssPeriodBegin\": \"2021-09-13\", \"paymentLevelTables\": [{\"levels\": [{\"name\": \"Sem titulação/graduado\", \"classTimeValue\": 101.1}, {\"name\": \"Especialista\", \"classTimeValue\": 134.82}, {\"name\": \"Mestre\", \"classTimeValue\": 168.5}, {\"name\": \"Doutor\", \"classTimeValue\": 188.75}], \"tableName\": \"Quadro de índice de valoração da hora-aula presencial\", \"tableTypeTagName\": \"presencial\"}, {\"levels\": [{\"name\": \"Sem titulação/graduado\", \"classTimeValue\": 50.55}, {\"name\": \"Especialista\", \"classTimeValue\": 67.41}, {\"name\": \"Mestre\", \"classTimeValue\": 84.25}, {\"name\": \"Doutor\", \"classTimeValue\": 94.37}], \"tableName\": \"Quadro de índice de valoração da hora-aula remota (lives, webinar, cursos EAD em geral\", \"tableTypeTagName\": \"on-line\"}]}', 2, 0, 0, '10.00000', 0, 2, '2.00000', '{\"dates\": \"10 de julho de 2022\", \"times\": \"10h às 12h\", \"workTime\": \"2h\", \"activityName\": \"Curso YYYY231\"}', ' dtghfg hjfghj  dtghfg hjfghj  dtghfg hjfghj  dtghfg hjfghj  dtghfg hjfghj  dtghfg hjfghj  dtghfg hjfghj  dtghfg hjfghj  dtghfg hjfghj  dtghfg hjfghj  dtghfg hjfghj  dtghfg hjfghj  dtghfg hjfghj  dtghfg hjfghj  dtghfg hjfghj  dtghfg hjfghj  dtghfg hjfghj  dtghfg hjfghj  dtghfg hjfghj  dtghfg hjfghj  dtghfg hjfghj  dtghfg hjfghj  dtghfg hjfghj  dtghfg hjfghj  dtghfg hjfghj  dtghfg hjfghj  dtghfg hjfghj  dtghfg hjfghj  dtghfg hjfghj  dtghfg hjfghj  dtghfg hjfghj  ', 'certbg2021.jpg', 1, '2022-08-16', '2022-08-01'),
(7, 1, 4, 6, '{\"companies\": [{\"cnpj\": \"\", \"name\": \"\", \"wage\": \"\", \"collectedInss\": \"\"}, {\"cnpj\": \"\", \"name\": \"\", \"wage\": \"\", \"collectedInss\": \"\"}, {\"cnpj\": \"\", \"name\": \"\", \"wage\": \"\", \"collectedInss\": \"\"}, {\"cnpj\": \"\", \"name\": \"\", \"wage\": \"\", \"collectedInss\": \"\"}], \"collectInss\": \"0\", \"inssPeriodEnd\": \"2021-09-16\", \"professorTypes\": [{\"name\": \"Avaliador\", \"paymentMultiplier\": 1}, {\"name\": \"Conteudista\", \"paymentMultiplier\": 1}, {\"name\": \"Coordenador\", \"paymentMultiplier\": 0.7}, {\"name\": \"Facilitador de Aprendizagem\", \"paymentMultiplier\": 1}, {\"name\": \"Orientador\", \"paymentMultiplier\": 1}], \"inssPeriodBegin\": \"2021-09-16\", \"paymentLevelTables\": [{\"levels\": [{\"name\": \"Sem titulação/graduado\", \"classTimeValue\": 101.1}, {\"name\": \"Especialista\", \"classTimeValue\": 134.82}, {\"name\": \"Mestre\", \"classTimeValue\": 168.5}, {\"name\": \"Doutor\", \"classTimeValue\": 188.75}], \"tableName\": \"Quadro de índice de valoração da hora-aula presencial\", \"tableTypeTagName\": \"presencial\"}, {\"levels\": [{\"name\": \"Sem titulação/graduado\", \"classTimeValue\": 50.55}, {\"name\": \"Especialista\", \"classTimeValue\": 67.41}, {\"name\": \"Mestre\", \"classTimeValue\": 84.25}, {\"name\": \"Doutor\", \"classTimeValue\": 94.37}], \"tableName\": \"Quadro de índice de valoração da hora-aula remota (lives, webinar, cursos EAD em geral\", \"tableTypeTagName\": \"on-line\"}]}', 0, 0, 0, '5.00000', NULL, NULL, NULL, '{\"dates\": \"10 de julho de 2022\", \"times\": \"10h às 12h\", \"workTime\": \"2h\", \"activityName\": \"Palestra Teste11\"}', 'asdfsdfgdsg dfh dghfgh ', 'certbg2021.jpg', 1, '2022-08-16', '2022-08-01'),
(8, 1, 5, 14, '{\"companies\": [{\"cnpj\": \"2041012\", \"name\": \"stdfdfhghd\", \"wage\": \"3100\", \"collectedInss\": \"250\"}, {\"cnpj\": \"\", \"name\": \"\", \"wage\": \"\", \"collectedInss\": \"\"}, {\"cnpj\": \"\", \"name\": \"\", \"wage\": \"\", \"collectedInss\": \"\"}, {\"cnpj\": \"\", \"name\": \"\", \"wage\": \"\", \"collectedInss\": \"\"}], \"collectInss\": \"1\", \"inssPeriodEnd\": \"2022-06-18\", \"professorTypes\": [{\"name\": \"Avaliador\", \"paymentMultiplier\": 1}, {\"name\": \"Conteudista\", \"paymentMultiplier\": 1}, {\"name\": \"Coordenador\", \"paymentMultiplier\": 0.7}, {\"name\": \"Facilitador de Aprendizagem\", \"paymentMultiplier\": 1}, {\"name\": \"Orientador\", \"paymentMultiplier\": 1}], \"inssPeriodBegin\": \"2022-05-17\", \"paymentLevelTables\": [{\"levels\": [{\"name\": \"Sem titulação/graduado\", \"classTimeValue\": 101.1}, {\"name\": \"Especialista\", \"classTimeValue\": 134.82}, {\"name\": \"Mestre\", \"classTimeValue\": 168.5}, {\"name\": \"Doutor\", \"classTimeValue\": 188.75}], \"tableName\": \"Quadro de índice de valoração da hora-aula presencial\", \"tableTypeTagName\": \"presencial\"}, {\"levels\": [{\"name\": \"Sem titulação/graduado\", \"classTimeValue\": 50.55}, {\"name\": \"Especialista\", \"classTimeValue\": 67.41}, {\"name\": \"Mestre\", \"classTimeValue\": 84.25}, {\"name\": \"Doutor\", \"classTimeValue\": 94.37}], \"tableName\": \"Quadro de índice de valoração da hora-aula remota (lives, webinar, cursos EAD em geral\", \"tableTypeTagName\": \"on-line\"}]}', 4, 0, 0, '3.00000', NULL, NULL, NULL, '{\"dates\": \"\", \"times\": \"\", \"workTime\": \"\", \"activityName\": \"Live teste teste\"}', NULL, 'certbg2021.jpg', 1, '2022-08-16', '2022-08-01');

-- --------------------------------------------------------

--
-- Estrutura da tabela `settings`
--

CREATE TABLE `settings` (
  `name` varchar(80) NOT NULL,
  `value` varchar(1200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='Tabela de configurações gerais';

--
-- Extraindo dados da tabela `settings`
--

INSERT INTO `settings` (`name`, `value`) VALUES
('LIBRARY_USERS_CONSENT_FORM_TERM_ID', '2'),
('PROFESSORS_CONSENT_FORM_TERM_ID', '2'),
('PROFESSORS_DOCUMENT_TYPES', '{\n\"rg\":{ \"label\": \"Documento de identidade\", \"expiresAfterDays\": null },\n\"address\": { \"label\": \"Comprovante de endereço\", \"expiresAfterDays\": 1 },\n\"certificate\": { \"label\": \"Comprovante de situação acadêmica\", \"expiresAfterDays\": null },\n\"other\": { \"label\": \"Outro\", \"expiresAfterDays\": null }\n}'),
('PROFESSORS_TYPES_AND_PAYMENT_TABLES', '{\n	\"paymentLevelTables\":\n	[\n		{\n			\"tableName\": \"Quadro de índice de valoração da hora-aula presencial\",\n			\"tableTypeTagName\": \"presencial\",\n			\"levels\":\n			[\n				{ \"name\": \"Sem titulação/graduado\", \"classTimeValue\": 101.10 },\n				{ \"name\": \"Especialista\", \"classTimeValue\": 134.82 },\n				{ \"name\": \"Mestre\", \"classTimeValue\": 168.50 },\n				{ \"name\": \"Doutor\", \"classTimeValue\": 188.75 }\n			]\n		},\n		{\n			\"tableName\": \"Quadro de índice de valoração da hora-aula remota (lives, webinar, cursos EAD em geral\",\n			\"tableTypeTagName\": \"on-line\",\n			\"levels\":\n			[\n				{ \"name\": \"Sem titulação/graduado\", \"classTimeValue\": 50.55 },\n				{ \"name\": \"Especialista\", \"classTimeValue\": 67.41 },\n				{ \"name\": \"Mestre\", \"classTimeValue\": 84.25 },\n				{ \"name\": \"Doutor\", \"classTimeValue\": 94.37 }\n			]\n		}\n	],\n	\n	\"professorTypes\":\n	[\n		{ \"name\": \"Avaliador\", \"paymentMultiplier\": 1 },\n		{ \"name\": \"Conteudista\", \"paymentMultiplier\": 1 },\n		{ \"name\": \"Coordenador\", \"paymentMultiplier\": 0.7 },\n		{ \"name\": \"Facilitador de Aprendizagem\", \"paymentMultiplier\": 1 },\n		{ \"name\": \"Orientador\", \"paymentMultiplier\": 1 }\n	]\n}'),
('SOCIAL_MEDIA_URL_FACEBOOK', 'fb'),
('SOCIAL_MEDIA_URL_INSTAGRAM', 'inst'),
('SOCIAL_MEDIA_URL_LINKEDIN', 'lkdn'),
('SOCIAL_MEDIA_URL_TWITTER', 'tw'),
('SOCIAL_MEDIA_URL_YOUTUBE', 'yout'),
('STUDENTS_CONSENT_FORM', '/sisepi/public/consentForms/Termo-Alunos_v2.pdf'),
('STUDENTS_CONSENT_FORM_VERSION', '2'),
('STUDENTS_CURRENT_CERTIFICATE_BG_FILE', 'certbg2021.jpg'),
('STUDENTS_MIN_PRESENCE_PERCENT', '75'),
('STUDENTS_SUBSCRIPTION_POLICY_LINK', 'http://www.camaraitapevi.sp.gov.br/escola/wp-content/uploads/2018/08/Pol%C3%ADtica-de-Controle-atualizada-2018.pdf');

-- --------------------------------------------------------

--
-- Estrutura da tabela `subscriptionstudentsnew`
--

CREATE TABLE `subscriptionstudentsnew` (
  `id` int UNSIGNED NOT NULL,
  `eventId` int UNSIGNED NOT NULL,
  `name` varbinary(300) NOT NULL,
  `email` varbinary(300) NOT NULL,
  `subscriptionDataJson` varbinary(4000) NOT NULL,
  `subscriptionDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='Inscrições de evento, nova versão.';

--
-- Extraindo dados da tabela `subscriptionstudentsnew`
--

INSERT INTO `subscriptionstudentsnew` (`id`, `eventId`, `name`, `email`, `subscriptionDataJson`, `subscriptionDate`) VALUES
(1, 2, 0x3c0f9a02d8b944923189910d0b93b91dd07843060ebb44dada515699da1442bd, 0x28fae367060eb0be260fa074b53ed700019964d75705021bee716092d0ed9895, 0xb40d0e0e95b19893eae16345bc252eb4d2e77a2501e683458b97a275b205c0125dc6ed81a653ffdce5d38ee457569535032ae8566e0415685cef79f02739692a1708c2bebc42d701e5d7f9e2b644b20a245e0ed014faecb7f774039d9db7b5d41faee39b7109563f7f08f33ab061b292f1c15102ace1f16927e5dfc5d410dfc3c069b42de673820514b3f0779e6bf54319e1974bfb659b5eda0dbca86a463be026bc372b6dbdf2de8861c19cec15353a9c477d7968448a6f69260b37175b56510f201fd5f5e8d9a56af9f3185923fd4631ec6e545d59c4d20b02ff47d5a70dadf012c232e2117c25f4c025880b1d2356c22e64329c43d21a93b6ffb00a0da8370ab75e84a5bd537115c2684d53ce0c7bc912735d5803bf8467fe3368573282f460744d08a33e923f125379e2f16b00d18b70a24a349596a57fa0d49766a31e480c8f92d7f472d1958649d4644f630a64ab3779780c25664529ed64c563e971629c8e1f48ceaf2328b14f18bb09f4e8e6e32023a74f8040db68897d81417fe0e5efb705a07211de9b822cc9e2689f546591db1dfccd17c69b4de81c5edb30b71219f03a6a7d8f514f0aaa334ae804d69785951e718e0f5ee77b36fd4ea66d3a7250a7fd737c42b0aaad18307ee1cac8ce160abcea62f5bfb30b5b03451b8b61680e4df55975ed0bcbd0081ce05f0481b7439e0afb23e0cebdddff8ab1d12f463fa400cb4cd49cc18e55c23cf0803098e115cbc98087af7dd2cf7cfe1f8be9121a7f604f780fc6d1b23cbf7b1aca3a98e3d1c224b3809c069559c47bb9acf199e6e1ba1a6c9c1375575ab0fbba8877f1681bfe4f9e985bb0760097601b0fbac58eca89a3347b5950809b938b8de364b0ad95909180b5dbda4b5559e759285c3bd1c8543049efec56354e566ec09e159664f27f992a8dccefd375b9a0013cc8bf73187e9a59cd4090d3479240a1e62b77e629435c60777b69d8f5f4c3d425e7b8707fa864bfb1975e1a5689442a616fbf21da39bb2589d5f9289a0d43c8a95895cc6eb0a962349a0025600e826b659db7d57d72d5f100737def47bcdc3e9b5bd7ad08f2d55865d00158132775710b0797000bb3f35f25cb76d32afb9385a0446a92cfa8b32dec3ceceab9c902b5a962d65b0e1cf889f762411195108f3745d5b73dfdda95121e076a73dfd8c4b152ef42b02cb352ddcaab54656eee7e8a10531b9143928ceb9ce1fe2b725d5a1e663697d6138f737e317f3ba063589118a4928cb32ffd549ca6ef6b1e3ba56b338c669b0a8f3a53e2ff210770f2d5cff670d690025799805c012ebfecabf2b566f2eaf4682a8d773d55c335b804bcca22483e988268879492c48b5e5c806de593e661a0c260628cfb00dda607716e88716168d472ef9d52e8d342b7fb09aded87f7a75e1cc744595a95e9fc9083898645a5c6fdbcfc04dd3b86e6bbce5e4aead31a8e090c416f3357766a0e187561d1b6a1dd97ad62312812ad5c1503c336f7cf5af299a8647886de48ff15a9c64b12b8f447e7761a6b9bd89ded62a8f0f00ae54d7fdbb2fbbd52b15d2e0db6b29568a65b23f2be7b3b9d014470d9053ce234c2a9978f4e2932a9a26ccf57420c29697853897985133c0860d66dd73c7aa9c2bcd1ab8c09bb2283e559fb6bc49aa6bf3a5415fb7dc5eb0a30e0d051269df70a3cba29057c641ca944f69ad33bb2ec408c956624f146538303022fe884d30cb2d0a2c89b63695bee6482f762e915c561af0a70c70e1a2678246762f4cd949f2d35cb9717584c233ed33f1ea466e88149a77c4b8c660c22def27b678ad460e12fb5125ce1078e1792454cec24bb2200cb9258e09a3ba040af2ea105be23e80e67257c41c8d7d5d2d2b9f7116938d5f755938322ebb17d72d5f100737def47bcdc3e9b5bd7ad37b5c5c0741f667a836795587207f481f613dcbcd72326dbff7d94c2d66c24662d8767afc81353c3c13dced6a9049484523b99775d03cdc5e01b4c2f613f0d3dfbdb81b6aa42342d5b93aedacaa2d5423ff14ce2f176fc3d22d5ff22e2e9b3c9b0af4feeee4220226186fa1369294385a040af2ea105be23e80e67257c41c8d7c8d7ce22c88014a8ccdd669ac8ffaf1311ed4b40d2f595040d11cc9553a9a6627a8c902cceae84698f547221d1f8f0000b34a63d3cd25d5ea858beb8425b44fa2851c99c4606d31253fa34411a833628c74246553e6042ef060008011ecf251dfc04dd3b86e6bbce5e4aead31a8e090c9b9f7d2df58c682aa05935edfeb629a248b56c93e3d6b7163357b1c099b7e7b3297f6e3e1e842ad647ffe58e1ed694c11a6b9bd89ded62a8f0f00ae54d7fdbb2b4d020b4dfab1ec6e0de5a040d9b65b69ef41d0f68ab77569ae476e620a2a56abcafb9d3a83bbbab4e88a9210a1f744cc1d3df0f0c6a80af2411c9fd858c076a63f32feb790cea2e05deacdaf9a68a6af6bfeb6441ef64da7f10a113c9aca2dcd34688e6e65fa849a9422b769836ca006d60419b98d4f4fe453aa4d6a902635dc2e6684b3084c4a7edc4626dd10a54dee70755a9fa4d64f0725f967af5eb327500eb53b855897d3149694b05d91e9cada489cbdc7ae9bea54f08963feb2114683fb487f70ac4cc1ae1af6cd83850f57a9dfa79742a9130c5b0d324d18ff782a6ee525ec52f2c61b2572446c8d79ba77f84092bfff4809468ecf64618575b38a4ab807754d3808edff319def269ec5bf5423accd12b0c9d582ac88d9c8770825dc6743fc197f20e2853745b0530c8c5b3f67ce259d9e5f1371bd092de29cb119f892cf07fbce8ab39cae01ab48c46fcceb9306bc3637c58b92e52f2d96ea753063381e1cf24c9d1b8009053b18efa963161630d2eed47cb2eb21482253c0c88c9ed1164ecfbc8b9230fc8efd1be80bfd07808ae97913c03a5a8c11db33ff5785358ccba6f3f81b1f46af1d302e6ed86970005c19890dc06d35ac5372ff55f75e868788ccdd5027371d71f4a462c64a41f2fb9eb039fd03b8e20eeb5e3cfe6765cb5923ad26f5abed21725fc9a80c124526538165c6bd3438ca0906aab340d8be3c8c836e736435ec9c566537c49ec5fc732592e6d5257d9a3e43b6bb425e6befce65c9fb539200c7f71056f380bc1269ef71497a7946f4b77c3242daa0eaa2cc88ba92e50100d77f7802f8af50573f9e2d848c9f48c3cbf15a2273cd883d516f96234b3e4f4440ba8c18686dfca03b5f74cf63cc62bff9f8922f3f1b29dde1a26d303e10683ee12c8424c4d186be7163a21e9827944c8eeb97bf818df31b23b3f10e0c18f7898f1dfb5eecbc8c9d0ba2a4cbdc2d1bd1acfaa7f51c6d9b3cc2618cd0f65b59f6ba3d6659102b9461d63f4f28e6fdaaaaef38fa21b44ae88eea9fb08d5ec0b624e67861bed5a164afe1fbd483398c564a8c424f056353cfda64de09303d9f94ab3c6ef7133196b4702aae657690d4b1364013675e57b25311d29d3b5745d64e2e56c3ed5bb9196d44ac6c56be28f3cc4e868e53cd0b151329fbcd7, '2022-02-26 22:04:28'),
(2, 5, 0xa042a6be5273474b32a0aa5da784a1bed07843060ebb44dada515699da1442bd, 0xb7a44a87cec48eaeccded25b3383cb2788bd49198f078d9c9c11ed22d63d63aa, 0xb40d0e0e95b19893eae16345bc252eb4d2e77a2501e683458b97a275b205c0125dc6ed81a653ffdce5d38ee457569535032ae8566e0415685cef79f02739692a1708c2bebc42d701e5d7f9e2b644b20a245e0ed014faecb7f774039d9db7b5d41faee39b7109563f7f08f33ab061b292f1c15102ace1f16927e5dfc5d410dfc3c069b42de673820514b3f0779e6bf54319e1974bfb659b5eda0dbca86a463be026bc372b6dbdf2de8861c19cec15353a9c477d7968448a6f69260b37175b56510f201fd5f5e8d9a56af9f3185923fd4631ec6e545d59c4d20b02ff47d5a70dadf012c232e2117c25f4c025880b1d2356c22e64329c43d21a93b6ffb00a0da8370ab75e84a5bd537115c2684d53ce0c7bc912735d5803bf8467fe3368573282f460744d08a33e923f125379e2f16b00d18b70a24a349596a57fa0d49766a31e480c8f92d7f472d1958649d4644f630a64ab3779780c25664529ed64c563e971629c8e1f48ceaf2328b14f18bb09f4e8e6e32023a74f8040db68897d81417fe0e5efb705a07211de9b822cc9e2689f546591db1dfccd17c69b4de81c5edb30b71219f03a6a7d8f514f0aaa334ae804d69785951e718e0f5ee77b36fd4ea66d3a7250a7fd737c42b0aaad18307ee1cac8ce160abcea62f5bfb30b5b03451b8b61680e4df55975ed0bcbd0081ce05f0481b7439e0afb23e0cebdddff8ab1d12f463fa400cb4cd49cc18e55c23cf0803098e115cbc98087af7dd2cf7cfe1f8be9121a7f604f780fc6d1b23cbf7b1aca3a98e3d1c224b3809c069559c47bb9acf199e6e1ba1a6c9c1375575ab0fbba8877f1681bfe4f9e985bb0760097601b0fbac58eca89a3347b5950809b938b8de364b0ad95909180b5dbda4b5559e759285c3bd1c8543049efec56354e566ec09e159664f27f992a8dccefd375b9a0013cc8bf73187e9a59cd4090d3479240a1e62b77e629435c60777b69d8f5f4c3d425e7b8707fa864bfb1975e1a5689442a616fbf21da39bb2589d5f9289a0d43c8a95895cc6eb0a962349a0025600e826b659db7d57d72d5f100737def47bcdc3e9b5bd7ad08f2d55865d00158132775710b0797000bb3f35f25cb76d32afb9385a0446a92cfa8b32dec3ceceab9c902b5a962d65b0e1cf889f762411195108f3745d5b73dfdda95121e076a73dfd8c4b152ef42b02cb352ddcaab54656eee7e8a10531b9143928ceb9ce1fe2b725d5a1e663697d6fb3360a3cee2254dd9920751150c7e99328a8dfe907ac7fedca7a08d067c3c660d7323e5739c0b5f3f3a023690f2375ecb2e6e73084ec1967700ddf6cdba692fd4edd68408dca99f6ba114eb54d48027488aa1201de6d652bdf7ed291d21adc83f6e76f40e4ee091c09639bd3da85e9dc87b6844448d8db45debd8d11d8cd68358ccba6f3f81b1f46af1d302e6ed86976294f086b1f3dc9872094d982313771314df37385700077fbcf4ce58e9ab81888545a5b6a72fe1c8ebefdf202d7d55ae9077d8386da05de0c9d620900ad41fb9576f2d200c8ad6834db7100840e28df51566d7d2e770b08f07a37091bd5a02e5b78888c22870f08a99a7cfab36ddada16ba5435779935c29f07df2f6bede59dc46808c4a4b99cb5b2d29f9222b851cf4e70755a9fa4d64f0725f967af5eb3275d104ab44bda1d83f1889847d026cd3c7cf41842f69372e8fdd9f5297ac7e5253334cec3846230f053ed68446de0fcde39dfa79742a9130c5b0d324d18ff782a64ed96fbe7415f1342d4605d51196df8cdb55e8367de6fba4bf431c535555bd7e4ed7ecfd902c3019dd8f14e3ea809eac736cecea26907e0027aa6e892ec46d1715a09c7368f3ab1e598c6598a68da90067abd7c334fa096fedb87a742fc12834cd395ec4c6dd870e9f4d17c312021679dbc3ec2aa4aa0405b8aa93264680caf09dfa79742a9130c5b0d324d18ff782a62084007886b3236c473ea372151ca590466781603cdb7ee5471d75a3e2c016452811b38b66bd46268f7cc638d22f4dcceea2461cea9d15ab1a77d0db803e367dddbc594d0bf617ab50e092be45bb95dd7e5cee3f56e39ee183bd993d583c63589b2e11c3513492ba43df281c4b54923f1a2678246762f4cd949f2d35cb97175821e922a9219d7d09628341ccc7437008c085250d5cfbc3787640e639e5b244ca8078538655d36198cf0fccda61d63422b397724a14d6d2d59c6ef53d8ea32383cbdb6b320cbda77282b9de12973c3a7b5893609cb3e66a62b0ea582f84b5596781b74d363bc64faf5c29d2f5d9b739be0e187879815b1d246eed533bc9830f52523b99775d03cdc5e01b4c2f613f0d3d52b82482c233f6fb5d0a69615785ae1c69d65a0d2b6dfab1e20eca62fcbd747508100a891f2c2281bfedfba873f81ed829620c370cbca62049a0e95b2ff4ba8124c8cab87e620283d82c4a76c0e61938641ca944f69ad33bb2ec408c956624f14fbe6ab783204235190952f70b14d0631c37f12befdee4eaff924ea5ffbf747b1a2678246762f4cd949f2d35cb971758df0376057b351f2d6fe9249032fc460cd8c997e74ad37b22a0f8b0fc6744f7fb0e98023084a129afa9797f434333b0f114df37385700077fbcf4ce58e9ab81888545a5b6a72fe1c8ebefdf202d7d55ae599818db681ae4302e4735b644647a8630eb7c19c7ef18959d657947fd5bcb46fea43923568d6e926279c5be3359fdb4113c13fc2eaa5decf4f48e3fe48d58dc2851c99c4606d31253fa34411a833628db4363b639b4bbdf95408bea3b1c247b0fae29e3b50680a4d0b07c5644be8667961f06b847605fdc268ab3f1f9ae46f173c4ee15bf479d49159260810ae75a9c588565b3c5dcfcdf046be06c53a2e86b326943d75c258d56b02a364376da73661693daa56355fd45925026eb06dad8e5cde09ccd6d0e8dab3c2c74ade7efb90801d04d1896b8ed7aaa373a41edfc6d9607a4e7c411b008410da60ee403a452abb550160cc2db98fa2c10f1798376cdb53bd2b2eeb46c24d2962b4323f4a847c4a95a347bcc709138911cbebc32c0798e38c1e607070adeda980a1a86feabe0b92c0e3365244129d9116b1a55f7d5566ff49e2347d157dfc88634d12a958d96102edde26f00aac01f7c90d2c30996b4a0328a8dfe907ac7fedca7a08d067c3c660d7323e5739c0b5f3f3a023690f2375ee32db87b4bfcd0e404a3861f8492d7d5397545f8e85cc0d06a1e316c3704b1563fb302732f0fb6ab8235c4b1bb911aaeb916904090d5a034841882aca10df79a5b174fb69f820da0b88dbecf0462e86866b051996c5b132cc5d3079736189893daf7e7564a1f14033eecdd23cef84923f4c49d356110a2bc35f59cc3d9aef179652d5ac1177453d9d0fd6545c1325bd3, '2022-06-04 18:13:58'),
(3, 2, 0x730b2cc39ae3db8aaa75251a6353a6035fcdfe260fb9a4559a462808536c830b806cea5792fa87a305843f8d8eaee9cc, 0xd0aababa1ee486358ea1606d4d8a3bf43b37edcb8d6b9549e0a50b30d9cad42c, 0xb40d0e0e95b19893eae16345bc252eb4d2e77a2501e683458b97a275b205c0125dc6ed81a653ffdce5d38ee457569535032ae8566e0415685cef79f02739692a1708c2bebc42d701e5d7f9e2b644b20a245e0ed014faecb7f774039d9db7b5d41faee39b7109563f7f08f33ab061b292f1c15102ace1f16927e5dfc5d410dfc3c069b42de673820514b3f0779e6bf54319e1974bfb659b5eda0dbca86a463be026bc372b6dbdf2de8861c19cec15353a9c477d7968448a6f69260b37175b56510f201fd5f5e8d9a56af9f3185923fd4631ec6e545d59c4d20b02ff47d5a70dadf012c232e2117c25f4c025880b1d2356c22e64329c43d21a93b6ffb00a0da8370ab75e84a5bd537115c2684d53ce0c7bc912735d5803bf8467fe3368573282f460744d08a33e923f125379e2f16b00d18b70a24a349596a57fa0d49766a31e480c8f92d7f472d1958649d4644f630a64ab3779780c25664529ed64c563e971629c8e1f48ceaf2328b14f18bb09f4e8e6e32023a74f8040db68897d81417fe0e5efb705a07211de9b822cc9e2689f546591db1dfccd17c69b4de81c5edb30b71219f03a6a7d8f514f0aaa334ae804d69785951e718e0f5ee77b36fd4ea66d3a7250a7fd737c42b0aaad18307ee1cac8ce160abcea62f5bfb30b5b03451b8b61680e4df55975ed0bcbd0081ce05f0481b7439e0afb23e0cebdddff8ab1d12f463fa400cb4cd49cc18e55c23cf0803098e115cbc98087af7dd2cf7cfe1f8be9121a7f604f780fc6d1b23cbf7b1aca3a98e3d1c224b3809c069559c47bb9acf199e6e1ba1a6c9c1375575ab0fbba8877f1681bfe4f9e985bb0760097601b0fbac58eca89a3347b5950809b938b8de364b0ad95909180b5dbda4b5559e759285c3bd1c8543049efec56354e566ec09e159664f27f992a8dccefd375b9a0013cc8bf73187e9a59cd4090d3479240a1e62b77e629435c60777b69d8f5f4c3d425e7b8707fa864bfb1975e1a5689442a616fbf21da39bb2589d5f9289a0d43c8a95895cc6eb0a962349a0025600e826b659db7d57d72d5f100737def47bcdc3e9b5bd7ad08f2d55865d00158132775710b0797000bb3f35f25cb76d32afb9385a0446a92cfa8b32dec3ceceab9c902b5a962d65b0e1cf889f762411195108f3745d5b73dfdda95121e076a73dfd8c4b152ef42b02cb352ddcaab54656eee7e8a10531b9143928ceb9ce1fe2b725d5a1e663697d6fb3360a3cee2254dd9920751150c7e99328a8dfe907ac7fedca7a08d067c3c660d7323e5739c0b5f3f3a023690f2375ecb2e6e73084ec1967700ddf6cdba692fd4edd68408dca99f6ba114eb54d48027488aa1201de6d652bdf7ed291d21adc83f6e76f40e4ee091c09639bd3da85e9dc87b6844448d8db45debd8d11d8cd68358ccba6f3f81b1f46af1d302e6ed86976294f086b1f3dc9872094d982313771314df37385700077fbcf4ce58e9ab81888545a5b6a72fe1c8ebefdf202d7d55ae9077d8386da05de0c9d620900ad41fb9576f2d200c8ad6834db7100840e28df51566d7d2e770b08f07a37091bd5a02e5b78888c22870f08a99a7cfab36ddada16ba5435779935c29f07df2f6bede59dc46808c4a4b99cb5b2d29f9222b851cf4e70755a9fa4d64f0725f967af5eb3275d104ab44bda1d83f1889847d026cd3c7cf41842f69372e8fdd9f5297ac7e5253334cec3846230f053ed68446de0fcde39dfa79742a9130c5b0d324d18ff782a64ed96fbe7415f1342d4605d51196df8cdb55e8367de6fba4bf431c535555bd7e4ed7ecfd902c3019dd8f14e3ea809eac736cecea26907e0027aa6e892ec46d1715a09c7368f3ab1e598c6598a68da90067abd7c334fa096fedb87a742fc12834cd395ec4c6dd870e9f4d17c312021679dbc3ec2aa4aa0405b8aa93264680caf09dfa79742a9130c5b0d324d18ff782a62084007886b3236c473ea372151ca590466781603cdb7ee5471d75a3e2c016452811b38b66bd46268f7cc638d22f4dcceea2461cea9d15ab1a77d0db803e367dddbc594d0bf617ab50e092be45bb95dd7e5cee3f56e39ee183bd993d583c63589b2e11c3513492ba43df281c4b54923f1a2678246762f4cd949f2d35cb97175821e922a9219d7d09628341ccc7437008c085250d5cfbc3787640e639e5b244ca8078538655d36198cf0fccda61d63422b397724a14d6d2d59c6ef53d8ea32383cbdb6b320cbda77282b9de12973c3a7b5893609cb3e66a62b0ea582f84b5596781b74d363bc64faf5c29d2f5d9b739be0e187879815b1d246eed533bc9830f52523b99775d03cdc5e01b4c2f613f0d3d52b82482c233f6fb5d0a69615785ae1c69d65a0d2b6dfab1e20eca62fcbd747508100a891f2c2281bfedfba873f81ed829620c370cbca62049a0e95b2ff4ba8124c8cab87e620283d82c4a76c0e61938641ca944f69ad33bb2ec408c956624f14fbe6ab783204235190952f70b14d0631c37f12befdee4eaff924ea5ffbf747b1a2678246762f4cd949f2d35cb971758df0376057b351f2d6fe9249032fc460cd8c997e74ad37b22a0f8b0fc6744f7fb0e98023084a129afa9797f434333b0f114df37385700077fbcf4ce58e9ab81888545a5b6a72fe1c8ebefdf202d7d55ae599818db681ae4302e4735b644647a8630eb7c19c7ef18959d657947fd5bcb46fea43923568d6e926279c5be3359fdb4113c13fc2eaa5decf4f48e3fe48d58dc2851c99c4606d31253fa34411a833628db4363b639b4bbdf95408bea3b1c247b0fae29e3b50680a4d0b07c5644be8667961f06b847605fdc268ab3f1f9ae46f173c4ee15bf479d49159260810ae75a9c588565b3c5dcfcdf046be06c53a2e86b326943d75c258d56b02a364376da73661693daa56355fd45925026eb06dad8e5cde09ccd6d0e8dab3c2c74ade7efb90801d04d1896b8ed7aaa373a41edfc6d9607a4e7c411b008410da60ee403a452abb550160cc2db98fa2c10f1798376cdb53bd2b2eeb46c24d2962b4323f4a847c4a95a347bcc709138911cbebc32c0798e38c1e607070adeda980a1a86feabe0b92c0e3365244129d9116b1a55f7d5566ff49e2347d157dfc88634d12a958d96102edde26f00aac01f7c90d2c30996b4a0328a8dfe907ac7fedca7a08d067c3c660d7323e5739c0b5f3f3a023690f2375ee32db87b4bfcd0e404a3861f8492d7d5397545f8e85cc0d06a1e316c3704b1563fb302732f0fb6ab8235c4b1bb911aaeb916904090d5a034841882aca10df79a5b174fb69f820da0b88dbecf0462e86866b051996c5b132cc5d3079736189893daf7e7564a1f14033eecdd23cef84923f4c49d356110a2bc35f59cc3d9aef179652d5ac1177453d9d0fd6545c1325bd3, '2022-06-17 15:34:11'),
(4, 2, 0xa042a6be5273474b32a0aa5da784a1bed07843060ebb44dada515699da1442bd, 0xb7a44a87cec48eaeccded25b3383cb2788bd49198f078d9c9c11ed22d63d63aa, 0xaed01315afb01dbc3fd8a380408b0bcaed97620611cc853b4179a26ba1da0ad882dabbedf142e1b5cc331de0134df464d0904358dc0adb2ab2009227ee1f8a59a6baebc7dd96aa0c2b1017547bbec5be163ce0d4e9f1b3d6937750ff12fa154bbce09d78db9b12a3faa3498ee9483394a0beca240a72b5e620bb76b484f6aba7038d8f5832c37e684f7a6ab57c894edae4dc6a2da5639a159036537248f331b1f8fbb74254f229640e0697e1890a339cc01e84eb1b86ed0f5a7e8a89d7ad1c01233bd1d8bc274fe02b5bd365a5952cc801fa02fcf98d7d7dfbce3e110ab1cb2cd663d201a17a90c4b385959a028a61dc40e06bb33915d6b522455895bce0abd9087dc6cd25c00f8909b649164918aa9df175e90a779fba981f7e42f8468030fb11c57539cfa3342baa899aca1f17fcfd452e98a440b66fbeff4b02f4e94c44b9618127aecec4fb986fc459a03b4a617fddf792cae02830f7d0015bb3285a1e1cad0bb1cabc1aa7ec188517b85b78e7dca06f6457de64bfbf564e019bb80855e645d3d885c5661733d508de6041799af1e3a2807665a62856b5a8a6505c49b2622b9013b4508529c841c032b7383f551ce905b4ab530c78697d2c1f2d41bb5a366a01047a25a544baafd896fff19cc93c569fb2c8a25d295270eda6605faa0f17764b01f9a293abcd90a1117d0628d012420a43831a55114d4f1248bb0cd024eb962a3a2a1e0702ea959fc021de6f2434b92733cbf12ff223050671dd97225f1d79cd2cb5d849495ca7cbb4445f98201d0d5abe6c93ccfb1ee3fc607dac6a0253d18368f1ec3af13534f958ded1258d145d57c0846179e19d26d0f79b5f184f60effdeeb9657985ec1f00e051f9563863e2ea6880f84e6b8c0cc9c98e02c84e9811e67ae051d85ce9f358d94de13764e295909180b5dbda4b5559e759285c3bd1c8543049efec56354e566ec09e159664f27f992a8dccefd375b9a0013cc8bf73b0abcf19de848ab6ae9d909b7d5b36993d924bb5a1384a1a0ffe182a1eada8b214a34587cddb6290b49bd7690ffd5ffde361aa8c8296233099ca830b23b4cc6164c546c503311c9f1e125e6022663e7061c167b3180e86d8eef981426a0dc7657b8a26f3bb36287ec3a95d4f487f1480e48c4d13661d7127d91f871e34f1548938c88dec0e2b647c67997905505ce5924b54c67b30c535798c2ab409ee69985031f3f72eff7963c8b4eab0e84c2b22f80702261237ce2cdd98a57e183375f517fb7fa747a74740356d9e4dd4ba61d877c1abb59abed3ba9d31703886172722576ce79a18b76b7b95a7f9ad27c664eb2209185d2dae50cece340b4bd95c2d12b568879492c48b5e5c806de593e661a0c2aecad0314ee68cd075c3807b3b35af1da5199125986e30c18b4c107b3713ac82d577a794c051fdf43c23e297db745dbb5f38d4de7cab6603e091ab69e20fa7c09ff678f68853bf0cb283c2112bb21b27837578562921d00a30e6606de93869bca9dcafa8240ab83c77ea6c54fe2dcfc8da6461d2a64a2cb82582611c94f40cc4955215acaf5ee0ed72148c71d1be9b847cb2427cc84b781d14d9d7b7133974fffa6aced157d08ce0276f743645639638cc85020a397a1c46b27664bb74f1ff3d7be4b5e33ae90327a93fc150522eed60236b3a8e1f605e6dbe4dd2224b8a736572c88de06c438f5e2ded90e5d6d8941bc16499cbb9c2e791d0e0654e1d14b182848dcefa1c7139912ca218e5f3ca21257756f46bd9ad66b6fb8b0c1684693871323374ee22555e80245c6d7dcd332b2442122c67352680b0080b3bdaf37c657dc0acce36c36ee7e84a84a5846306c51ff4523505ba34dde1b646f56e41689db3da934aa9e8e8038811e2b165c87f8b167e6b75e3ac73f78cad807d3bee271d83a72a1a90371a3ab7db4c0b1780bb4248f0230ab76a0c7ecd7d0f610691bb4f6136c8e4c19aa49770fc61f4bb395083d28a9c2d13e963be719e979075f99929d882c3b469da95a9c44e8fcf6be5550509b1a8b007dcbca5ac51c4e350cab6f76e7758dee7dfa619c51cdfc9657a1f0f3f8adf726889fd96c5d30ed7424f8a8fd2498328d32e1e32bbc42bdc8acee30cf59652dc69ea9ea23c83497cc1ca8c3f2baaefdb014786389924e9b7d5d3f1c618bf79230e577c4f83608e89c8fa15a6760b612b45c00eb9ebb11e7d92a6905ba906a030eb47f469d658ff9d913125413cb6a8265bb3586ca3655f5715b49a5fd43b5d7e19d42b9ce49b97fe61133344a301b86c400f9701d32b5378a3eafdf633c9b2eda4fd853705b7124a7057edfc9ef6dfc769fc58e796267f81f4327542ef0456e5c245d95bf7ce8c4413e29aa8d7746fa1065a3f101525753318e95a66257ec3f74b6a73ee4d156333ea00f8c5d9bd679e2575e5262b02a13c5b19175b78dd594de63f94976b83f97f3b3e0e0325a7d0b015b86fe2bb823ea17e27692b139747a646a8e2de90ca0004a4cf6bc6c765e24280d2f37c7a00241bb25e058fb5ee0ff7218117479e05fc109a1afb71125f837379c2b4b508ae66fbd04ec77387f2b7b6acfe3bf4a7ea77a4a1825dc7f4bf9c1cdda86b7e69d0aefc8c89d9a8a1d17233959e2abbd34463448333fb610df86a94b9edc3ec9d378acd4165906f0d915ee60f9df6454cb93695dbbfe8b73b01b86c400f9701d32b5378a3eafdf633c9b2eda4fd853705b7124a7057edfc9eb9b95d2823e50dc938e0193d88df08f6b189f473a28f6aef8aad81a0ccf506949dc7a51c1c7e48bcd14ee9a247cf14c2b0979b36e2af20915e81b0f8882082f4bcca9dac53d5b934f0b40bd6431c568d43f4fe1e14929b6ff9935a041e31b58c4c48df41405cf73135e04ae0907621d6529d0b0f08ca3fceb71cc6a75f2e96adbc79ef02b79c3d21a7e87c3e892fef3f039cd804a29def4ee1d99427ae7a36fe1693daa56355fd45925026eb06dad8e5cde09ccd6d0e8dab3c2c74ade7efb90801d04d1896b8ed7aaa373a41edfc6d9607a4e7c411b008410da60ee403a452abb550160cc2db98fa2c10f1798376cdb5fc36699c540de5b83b15fa80f52ffe81a3c0ba36bea4484088a5a2b04f7217dab4c1a877e0680ad71e36698f38a89934b074d406ba7188a961a796a06818559243ecdf0569683138adcb5752f33c529a36fec39a5f31c99b4889c3596f285ed55557ab11d6931c85945b21aae3f9b9293afb96db2f2ff21921ef9375e3ecb37200b3494624661050d17dc6275d2b8b7da06e6b00b2fbb2d38dca6b9a2d7c92d0efc5282767a9616433a9f17e6edfb77a81552ec4c8a466cd0e4d2b75bf735f95d0f524725115b04a2691e99499c7def70496e2b57a9dc54f35660a65698212e3b5745d64e2e56c3ed5bb9196d44ac6c56be28f3cc4e868e53cd0b151329fbcd7, '2022-06-23 19:06:43'),
(5, 2, 0xb62e3c2931c14a57d99431c6d8864432, 0xc2dbab5b972f7ec7be48dfb88b10cd39173094a412e1334229d52e80b70d7f73, 0xb40d0e0e95b19893eae16345bc252eb4d2e77a2501e683458b97a275b205c0125dc6ed81a653ffdce5d38ee457569535032ae8566e0415685cef79f02739692a1708c2bebc42d701e5d7f9e2b644b20a245e0ed014faecb7f774039d9db7b5d41faee39b7109563f7f08f33ab061b292f1c15102ace1f16927e5dfc5d410dfc3c069b42de673820514b3f0779e6bf54319e1974bfb659b5eda0dbca86a463be026bc372b6dbdf2de8861c19cec15353a9c477d7968448a6f69260b37175b56510f201fd5f5e8d9a56af9f3185923fd4631ec6e545d59c4d20b02ff47d5a70dadf012c232e2117c25f4c025880b1d2356c22e64329c43d21a93b6ffb00a0da8370ab75e84a5bd537115c2684d53ce0c7bc912735d5803bf8467fe3368573282f460744d08a33e923f125379e2f16b00d18b70a24a349596a57fa0d49766a31e480c8f92d7f472d1958649d4644f630a64ab3779780c25664529ed64c563e971629c8e1f48ceaf2328b14f18bb09f4e8e6e32023a74f8040db68897d81417fe0e5efb705a07211de9b822cc9e2689f546591db1dfccd17c69b4de81c5edb30b71219f03a6a7d8f514f0aaa334ae804d69785951e718e0f5ee77b36fd4ea66d3a7250a7fd737c42b0aaad18307ee1cac8ce160abcea62f5bfb30b5b03451b8b61680e4df55975ed0bcbd0081ce05f0481b7439e0afb23e0cebdddff8ab1d12f463fa400cb4cd49cc18e55c23cf0803098e115cbc98087af7dd2cf7cfe1f8be9121a7f604f780fc6d1b23cbf7b1aca3a98e3d1c224b3809c069559c47bb9acf199e6e1ba1a6c9c1375575ab0fbba8877f1681bfe4f9e985bb0760097601b0fbac58eca89a3347b5950809b938b8de364b0ad95909180b5dbda4b5559e759285c3bd1c8543049efec56354e566ec09e159664f27f992a8dccefd375b9a0013cc8bf73187e9a59cd4090d3479240a1e62b77e629435c60777b69d8f5f4c3d425e7b8707fa864bfb1975e1a5689442a616fbf21da39bb2589d5f9289a0d43c8a95895cc7eded716e0e6ca279cb1c524ce4d8e2a0a05c7d81cbb4d93ff2df5387e5a2cb0ef4ff7483cb730467e3fd15335504ef0ffe7fece0c95b1aad1c458f23abaec5c94551df447fb16e84da4f30231c00929acdeee1dffb057a70f252db44937ad30da4101ab6bd442b3720d96041d61d706d7e816870bc846bb3e3997b0c815f23be6348108698b031820ea363bd6810c49d3b7f29b03e4ba340432d9017fe6f2fb009e61aaba7c508948a2375db4c8f4973d887bf097e1af0f094acde631737520123ac0b53115ebeb91198704d65d6aec254d2c6e9ec80ef8b7d4813995ded59a6f97d99f0cf05f6ccaa62850940fe35c752a354ef915947bf568a9645ac5c44bae2e2b205d073a197a296615818908dc52814c2c56a5ad2489a2b91c649166ee56dacf4a2b891dc3f42820a529babfbb108a365c2bfaed833117d43917212c75ef73cb536a46d6307da859ce9fade2bd315f688fc43d2cb48c7deba784fb3e3cbc8067ddc5661aad431d9f0f7f2993d30e3d87d4b4e8143151baf3d6b69cde48dfa2f18929f033139484a558c0b7423b85e10723b5e6294d83dd2cd507804796ac32f40cc59e7bd7d2b13f332e5d1b53f414939e36040632271b054f5949a0f948095d0d91cc69b3be75dfa79c1526ab1b61a718179b7d484fd0fb70b4df7d8ab99d6ed4511f10b9246cdfdb4b85af078bb03ab2dfc441b05a51a5a0e476e8beedea941f949db4b41e9a69f33072fe1158ccba6f3f81b1f46af1d302e6ed86976c68e18886ff2fac8a7f4458bd1dc083eea2461cea9d15ab1a77d0db803e367dddbc594d0bf617ab50e092be45bb95dd7e5cee3f56e39ee183bd993d583c63584ca316558a4806ef9671242d5c0017fdd151ab358934e4dd9d0f02b68b1238888bb03ab2dfc441b05a51a5a0e476e8be5608be9dc9a149fcbb36a6e15961ab9f58ccba6f3f81b1f46af1d302e6ed8697e9be352003c7a2813f943b2ee8a76e4d14df37385700077fbcf4ce58e9ab81888545a5b6a72fe1c8ebefdf202d7d55ae599818db681ae4302e4735b644647a8630eb7c19c7ef18959d657947fd5bcb46b06e0961c628361ed5f099a43d0fd1643e018524f3a87274a052bf9403d42f94d0e0bf85c09d5b7a0d05585d687b7e95b32bba938afdcada933f9ccf2ab14bb9252e2fa4c7c4e9625889c1fd025b2f9d6e25a1e943009867407223fdfcb46c716a55c533341997757c8a9038b4ffbf9e38d8db24dc65a87bf32623ac7b2a3d201a717be47b6ce250d48021d739880b271a2678246762f4cd949f2d35cb971758145649fbb345a317ec6df68cba4f8606aa7933dd0471f312b5538a776a2cb86ffcaaf389ac321dac557166234db27bc16df478a77bed2b3276de1558d90bab4a629383b1225dcddcc6246341e9faf2f67d72d5f100737def47bcdc3e9b5bd7ad922b205b023c0b947915c78f0794f7a99bb07a75fc129f18c0dcfe81d07c53ab228d795f2e179ed94fa5db5e3a95f0253e018524f3a87274a052bf9403d42f9441d40c95bb972a11d5c278d3824d52b2686e1122eeb03610c5b1bc1b3bb01e4a21769c45f82f4a2759c7edcb0cd6334aef73cb536a46d6307da859ce9fade2bd0ab76f5d6cae9ae862039c342a294595e2bfc6e4dabd601afb6c41525205b974f454bdc078d13fe844a00f3b83a8364552676c6b6304860ff861bb47c0db031d9c6d368857790f328301160b0031e3f68df2577bca8ec619ab1fa5d46ea87ed1b715a55106caef5f3f309619db148a1e332a231129c19d8373dca4929910b5379151dee17df00a709a0be6be2dde7a4968ea857f019fb0a1b5577c4c0326cace5e97d2b32d45d9cc34e556b27ac22d23fb7763a6a19c012e171450f20235c1eef5ba534c2fdaad9d2cbc5362a094e891418a2eddb2e3db018c98aaad6060bdf36742ea33df0cd8930d67923453715ee84996d06dc8de8fb2bd73c4aabc9cdc1f35749a269a489e0e8f1719dba11a8266be6a1d0bb65d9c272316b985d3054f2c3c81fddcddeccb2ade512bd81c77fb7db820031ed778ce04ed3d5f92f1e2a89146c52fba39d28a08218591e1cc471d4c7aa337d26f8b50caff0cb51f7c58c3a6009e61aaba7c508948a2375db4c8f4973d887bf097e1af0f094acde63173752026ed07049e95fa599f72d9954946bc4932104f59d9ea0a264177821a3baa4bbf44d75e19af917f9bea502a8556dcaaabd0d8214f1f0647fc2ba33a1680ac202b1dd33f78f05f4b185b1f2ee45222a984e3ecfb2b87fbaaa5fb47cc50bb5d5967448778edc8b45886c700b70c3c11e766062eb861af5052c213d45681ed194020249cc00762fda0b1ae75e3838834e0bb, '2022-06-23 19:26:51'),
(6, 2, 0x9b0fc0eb4486bf57d5f0843077aa7b8e, 0x536a675d6b50190fab4ea7f3da660a22533a9c9e16be4072eebd79e5e6b25f3e, 0xb40d0e0e95b19893eae16345bc252eb4d2e77a2501e683458b97a275b205c0125dc6ed81a653ffdce5d38ee457569535032ae8566e0415685cef79f02739692a1708c2bebc42d701e5d7f9e2b644b20a245e0ed014faecb7f774039d9db7b5d41faee39b7109563f7f08f33ab061b292f1c15102ace1f16927e5dfc5d410dfc3c069b42de673820514b3f0779e6bf54319e1974bfb659b5eda0dbca86a463be026bc372b6dbdf2de8861c19cec15353a9c477d7968448a6f69260b37175b56510f201fd5f5e8d9a56af9f3185923fd4631ec6e545d59c4d20b02ff47d5a70dadf012c232e2117c25f4c025880b1d2356c22e64329c43d21a93b6ffb00a0da8370ab75e84a5bd537115c2684d53ce0c7bc912735d5803bf8467fe3368573282f460744d08a33e923f125379e2f16b00d18b70a24a349596a57fa0d49766a31e480c8f92d7f472d1958649d4644f630a64ab3779780c25664529ed64c563e971629c8e1f48ceaf2328b14f18bb09f4e8e6e32023a74f8040db68897d81417fe0e5efb705a07211de9b822cc9e2689f546591db1dfccd17c69b4de81c5edb30b71219f03a6a7d8f514f0aaa334ae804d69785951e718e0f5ee77b36fd4ea66d3a7250a7fd737c42b0aaad18307ee1cac8ce160abcea62f5bfb30b5b03451b8b61680e4df55975ed0bcbd0081ce05f0481b7439e0afb23e0cebdddff8ab1d12f463fa400cb4cd49cc18e55c23cf0803098e115cbc98087af7dd2cf7cfe1f8be9121a7f604f780fc6d1b23cbf7b1aca3a98e3d1c224b3809c069559c47bb9acf199e6e1ba1a6c9c1375575ab0fbba8877f1681bfe4f9e985bb0760097601b0fbac58eca89a3347b5950809b938b8de364b0ad95909180b5dbda4b5559e759285c3bd1c8543049efec56354e566ec09e159664f27f992a8dccefd375b9a0013cc8bf73187e9a59cd4090d3479240a1e62b77e629435c60777b69d8f5f4c3d425e7b8707fa864bfb1975e1a5689442a616fbf21da39bb2589d5f9289a0d43c8a95895cc6eb0a962349a0025600e826b659db7d57d72d5f100737def47bcdc3e9b5bd7ad08f2d55865d00158132775710b0797000bb3f35f25cb76d32afb9385a0446a92cfa8b32dec3ceceab9c902b5a962d65b0e1cf889f762411195108f3745d5b73dfdda95121e076a73dfd8c4b152ef42b02cb352ddcaab54656eee7e8a10531b9143928ceb9ce1fe2b725d5a1e663697d6fb3360a3cee2254dd9920751150c7e99328a8dfe907ac7fedca7a08d067c3c660d7323e5739c0b5f3f3a023690f2375ecb2e6e73084ec1967700ddf6cdba692fd4edd68408dca99f6ba114eb54d48027488aa1201de6d652bdf7ed291d21adc83f6e76f40e4ee091c09639bd3da85e9dc87b6844448d8db45debd8d11d8cd68358ccba6f3f81b1f46af1d302e6ed86976294f086b1f3dc9872094d982313771314df37385700077fbcf4ce58e9ab81888545a5b6a72fe1c8ebefdf202d7d55ae9077d8386da05de0c9d620900ad41fb9576f2d200c8ad6834db7100840e28df51566d7d2e770b08f07a37091bd5a02e5b78888c22870f08a99a7cfab36ddada16ba5435779935c29f07df2f6bede59dc46808c4a4b99cb5b2d29f9222b851cf4e70755a9fa4d64f0725f967af5eb3275d104ab44bda1d83f1889847d026cd3c7cf41842f69372e8fdd9f5297ac7e5253334cec3846230f053ed68446de0fcde39dfa79742a9130c5b0d324d18ff782a64ed96fbe7415f1342d4605d51196df8cdb55e8367de6fba4bf431c535555bd7e4ed7ecfd902c3019dd8f14e3ea809eac736cecea26907e0027aa6e892ec46d1715a09c7368f3ab1e598c6598a68da90067abd7c334fa096fedb87a742fc12834cd395ec4c6dd870e9f4d17c312021679dbc3ec2aa4aa0405b8aa93264680caf09dfa79742a9130c5b0d324d18ff782a62084007886b3236c473ea372151ca590466781603cdb7ee5471d75a3e2c016452811b38b66bd46268f7cc638d22f4dcceea2461cea9d15ab1a77d0db803e367dddbc594d0bf617ab50e092be45bb95dd7e5cee3f56e39ee183bd993d583c63589b2e11c3513492ba43df281c4b54923f1a2678246762f4cd949f2d35cb97175821e922a9219d7d09628341ccc7437008c085250d5cfbc3787640e639e5b244ca8078538655d36198cf0fccda61d63422b397724a14d6d2d59c6ef53d8ea32383cbdb6b320cbda77282b9de12973c3a7b5893609cb3e66a62b0ea582f84b5596781b74d363bc64faf5c29d2f5d9b739be0e187879815b1d246eed533bc9830f52523b99775d03cdc5e01b4c2f613f0d3d52b82482c233f6fb5d0a69615785ae1c69d65a0d2b6dfab1e20eca62fcbd747508100a891f2c2281bfedfba873f81ed829620c370cbca62049a0e95b2ff4ba8124c8cab87e620283d82c4a76c0e61938641ca944f69ad33bb2ec408c956624f14fbe6ab783204235190952f70b14d0631c37f12befdee4eaff924ea5ffbf747b1a2678246762f4cd949f2d35cb971758df0376057b351f2d6fe9249032fc460cd8c997e74ad37b22a0f8b0fc6744f7fb0e98023084a129afa9797f434333b0f114df37385700077fbcf4ce58e9ab81888545a5b6a72fe1c8ebefdf202d7d55ae599818db681ae4302e4735b644647a8630eb7c19c7ef18959d657947fd5bcb46fea43923568d6e926279c5be3359fdb4113c13fc2eaa5decf4f48e3fe48d58dc2851c99c4606d31253fa34411a833628db4363b639b4bbdf95408bea3b1c247b0fae29e3b50680a4d0b07c5644be8667961f06b847605fdc268ab3f1f9ae46f173c4ee15bf479d49159260810ae75a9c588565b3c5dcfcdf046be06c53a2e86b326943d75c258d56b02a364376da73661693daa56355fd45925026eb06dad8e5cde09ccd6d0e8dab3c2c74ade7efb90801d04d1896b8ed7aaa373a41edfc6d9607a4e7c411b008410da60ee403a452abb550160cc2db98fa2c10f1798376cdb53bd2b2eeb46c24d2962b4323f4a847c4a95a347bcc709138911cbebc32c0798e38c1e607070adeda980a1a86feabe0b92c0e3365244129d9116b1a55f7d5566ff49e2347d157dfc88634d12a958d96102edde26f00aac01f7c90d2c30996b4a0328a8dfe907ac7fedca7a08d067c3c660d7323e5739c0b5f3f3a023690f2375ee32db87b4bfcd0e404a3861f8492d7d5397545f8e85cc0d06a1e316c3704b1563fb302732f0fb6ab8235c4b1bb911aaeb916904090d5a034841882aca10df79a5b174fb69f820da0b88dbecf0462e86866b051996c5b132cc5d3079736189893daf7e7564a1f14033eecdd23cef84923f4c49d356110a2bc35f59cc3d9aef179652d5ac1177453d9d0fd6545c1325bd3, '2022-06-23 19:34:13'),
(10, 2, 0xc273b26f19d7a3c5d921c0f61bf3ec90, 0x0990e649d1af757995d6f2ce5b829f26c242737db1b1956abb7b3c20161fe862, 0xaed01315afb01dbc3fd8a380408b0bcaed97620611cc853b4179a26ba1da0ad882dabbedf142e1b5cc331de0134df464d0904358dc0adb2ab2009227ee1f8a59a6baebc7dd96aa0c2b1017547bbec5be727543f99075e58ec15bd870bc884ac72e82a6b471df98116c9d1efed2859cbaadbb188320126b1246aa28433ac4f6beab17e2227038478593b5415efc477ff05a052a3c21e033fb0e071dc713126113df88f26c8c8648eb953cbe42a4a6007d8511627700d4b0cd0d089a3a446b5c9d98f662cc4be9c328a7b79bdd6719793cc8b98c81de42729e9ef14fec1ffa8b9939ea28903de74e0bb15a5162086839da2ce065f81b4bf5c0e145c216b0f55bd03ec1f991be83fa62fdf9092ae0880cf4c22e64329c43d21a93b6ffb00a0da8370ab75e84a5bd537115c2684d53ce0c7bc912735d5803bf8467fe3368573282f4c96c2dd20a9a65887522cc357a208b3f003fc105338fa390131f0cf726ae589f28fd08918cb9979ddb0dec632691b70da7be15b4143538c0b7d21bb99cb66389dd2f585b85235e8aa40a4aeef899c58df175e90a779fba981f7e42f8468030fb8489d31bcad72ef55c03d60cfbdf13f8b821c05ecd92392cd08fe98baf2868036e38c0db9e79be0b12daf78acf91007a6a875870afa461380e6b7bccab5a665182fcf45d7626580a998caa2553cf614367ddd91ef759339b042e0c08ffd8fa6f1c3098c989f668a04427383e6ab246407c73d62285a39ac370201790f50ec27855f45102a4f8ae4f39ede4cf4236988b009fd223fb97cda4ffd0d628d744e61b57d1a6dec125fd1cb92d1ad589960fa5dc098477d871edf3b7433fbc995dd05b1226f21577068b5505409d4bf0fe9daeb694c69587820d523a497f2a4d7087a58c53ec86aa806bf97942bbc9cf2c2fad632cb3180f73b1ee367f80d63dc1b50f910cf35fb1fc2586458b7fc3ed027d886d3a8c28a1e0be7c5effa2da41af1ceb6835e1c22a9932a0ae0fbdc88c77232c0ccc166fbc5bef01eca0b5cf59f227cd7cba6b0c41b75fef39c6eaea914c6e19ef2681fc14d0643aa39dc83fef6aba4a1d19647a8cee5c5fe908248ceb1931af5d36bc763b927a053020ba93d4de4aabf2c0880f6ab0b5b364e7a4a6b5e1f7ff57e1e85735e55469badfffc15e0ce1c0f78daca414a53ccb565241007667da240b1c2a746c1b45d1c26ee135c7a18631d8803e7ccff7c45619fc03b7a0d77ba203c9951a13d39544fbf66f866582214f99dac92ef790402a5f8bd6233dcdc560e05870291c365693db8022e65275ed02e741de42542ca19b5aac9e183750c7aa5cc92f498fd75fea22f664cbb1b053ebd4edd68408dca99f6ba114eb54d48027b502e910489916f9085407b4492a32cb64d0d28a5884e48c3e3f0f82c282635575682548e537ff3ebb880760ac5287311113cc2e79a66c13d740fa871f68d7f10cd0a4dcd06fa59501e0f616844484a70f477483913cdb6c77fe181c2081f97d2ffd549ca6ef6b1e3ba56b338c669b0a0ecc9e7b23836a1c8e258e60753e40eb7029791bb51bade900e47c809f76e6160125705ca2d920cdbf6d2bef98ff3055d156617bca9d76b9fc2bc53d98081987ca6364f016a886d332d4ff20e7159e041f002b284240d0a25496fc122ef053fa51afa7714e92a0a4fe75aeda1f0b5dc568482ff95e70039bd16ba2eff8d0bf8134b801d33e964bf33fd5aaa30fc9cd4e01b86c400f9701d32b5378a3eafdf633c9b2eda4fd853705b7124a7057edfc9ee9ccd7ddd12ac67bf13a941222bdf0ac2e0525c3fd5e62be0336ab3e8cc1421e80fa906f89e2c7a7c7d2ea66b906ed4a4bfb31689a5e14efe00dafa64c6606a4269a9e7344731dfd9e1449da656baedcd22f3fc5a61277bbf7059469025b462b8a51a6566e871747f925562a60c8aa03f15b66e6e0e7f02d6d5f57d3a44b046c40d9ed5ca551b4838553a75e1513d29d05dbdb70a8391d8d93b2541628693e12a0d0877ea55d491cfaa8d0e6e163584d2643b48227d290a4d88d5eed9df8955c972fe27d9faf8345c6dac1335f6e267b42b018dc9967f6ca54f9087b9ef071c6db68d1cab97bee22cd66fc789034e837989c4fc76ef6e21581bf50fdb82b465843fd1aaa1385aca8816b8eeb6bf520688c6304fce374189a81ecce4f21d49f1d62100fa01bf1d38cb6c470c8293266d440d9ed5ca551b4838553a75e1513d29d05dbdb70a8391d8d93b2541628693e12bda817f196e6f8db0013160fc391ff87848dcefa1c7139912ca218e5f3ca2125f8101dce6833c3093cc13c8dca3e5f32af51b8339ffe2985260e79830b921604b665693cada467142abb579293b8354647bd71bb8c8f0cd2bf5bc591da27949afb639af1d5f893476785c05bfebf5ae306346f6dcf282cf704d2739df5c9bec34becd320152c1ca7c622e5e0d79ad2cdae48fb3470e02891b3b254f6ae43150224ad2b6e354b078858f19d74dcef9b59f0230ab76a0c7ecd7d0f610691bb4f618c229203e7cd01e3c9bce5f1d686b733d42989ffb338208421368ac06cc112109c87d2e5c085168215218d5c9ed74b33e6be2eca033b33c1367cd583bb3b505bd1a438aee11f3017106c326a594e0e5e5543c75c85dbdf9d27d5ec7c6fb702e3a3a912918cc66fefd64e7ea6c7ec4c88ec885df5ff6372d2343d8e8360314e5a45555ece673934ecf9ac71d26adf20c335661c7a8fb30e8348be47dee704f647ce6d8fcfd0a4b6013e6e3533e909e35d94363b08a8a6bb224077dbc74643c7792cc902a7013903d3f4c62a5c78887599b2a2d09277f35e2d16c119ccc0889e2a47f63456757e3f50b25d339198bd9ed08532995a35e29f972afb9195cb80f25b60653b3e0df003a86efee0cdd7f1eaa6f6689ff30008f854faeaa15f736150d88adf726889fd96c5d30ed7424f8a8fd2c4205ea8d1454e73bfec1e2cbc28f374a9951fd38b013f08a8f2485507bca9d2e3e624c3df38986af8a7b83e857c375679b7caf4202c47d4c81244f0c006290e3f9dc4c0e5a3b3f23844e8a6a434cd4ed141108930169e0b78606f2e199beb0fce7c93648541583f4c15739f68956007d2fb3b71c12f13433a33309dd23bd90a713e62937afaffcad3b46b8a609e31f26773b604e1e6c29acb62f43f8b6268ddecc66b342331192a0e45fc042154b223584a883938fa83e34fb1e55b0fe490a924943485c9e3c9bd93f8d2f0e87a7fe9c42dc840cb7a3b6b87feb8f661efacb0da1a04d47e7241ade5d77ac151e6ccd547381cddee610722216a552024a8f1d513e9e7c5e2a81eac49ffb7f5e8019c4e1c90c535c215368b68101859c21ae3b7244ee4d9b7ea32ccdee86b0e70a9c8d2be555bb2d6c545308a14fc40ccf96c6f3b833f0b51508874926a0ad38d9f475f88f8d6af743659c7beb952bdde2c5e3568c530a6cf40401505e297d9e6a1a1d9ed3f2826a3d20a99ec2f40e45deecac1140e062be2cb99262d38e8b893b137d54cf044538855f103bcb60f9e835aa4c907e8c8aa004d206764bdd0b6ae7fc0d08d680795f086886a2e62ba27c7c87c6b3b1a62ef15f480cab5c53caa56644d123a687059c048c92e8cf0021e829a289121757cdf7714a79cab94c1a810f396e397273f70ffa4707ee3a8168bd9ff9b2504d691ea785124c0c46643375df4221ec56b52d94e4a85635ca25bacd3732e43080297366741aaf64d889a5d054254ec8125c8e8b425409b7ae9facbb8fd5155c6a9770c4168cbe6b5f81815414d6c6aab6b6a23dff9e4842a38d080d0b6786b37ebeef727e659afa692bc5cf8c4240fa6d18d17c884fe123bb4e74a619895c9d875bad1cab719c5f703323b73f430392039b2d7b1b0e8f14fceb26616c2880538cbc7badd263e93701f239dcbfc02f1f6689ff30008f854faeaa15f736150d8a01bcfb87c4fbb80e640d0b0483e3756cd03d1828ab7ad6999599b971c33df52af51b8339ffe2985260e79830b9216043dbb2f019eeeceeb2b016e190f1a2f63a32c2594bbc4f6c879180313fd0f0df7f435f32507abd972c0e708c31855932963253d8c68d62fb9315b0106c85dd8de, '2022-09-20 18:52:45'),
(11, 2, 0xf925266e46459ca9ffcd450c33c7bc20acd92d3bd2372213b22306bb81292053, 0xf88769eb1fe351fc652e66f821315e5a3cab351db9a18e606db0ac4b82c338cf, 0xaed01315afb01dbc3fd8a380408b0bcaed97620611cc853b4179a26ba1da0ad882dabbedf142e1b5cc331de0134df464d0904358dc0adb2ab2009227ee1f8a59a6baebc7dd96aa0c2b1017547bbec5be727543f99075e58ec15bd870bc884ac72e82a6b471df98116c9d1efed2859cbaadbb188320126b1246aa28433ac4f6beab17e2227038478593b5415efc477ff05a052a3c21e033fb0e071dc713126113df88f26c8c8648eb953cbe42a4a6007d8511627700d4b0cd0d089a3a446b5c9d98f662cc4be9c328a7b79bdd6719793cc8b98c81de42729e9ef14fec1ffa8b9939ea28903de74e0bb15a5162086839da2ce065f81b4bf5c0e145c216b0f55bd03ec1f991be83fa62fdf9092ae0880cf4c22e64329c43d21a93b6ffb00a0da8370ab75e84a5bd537115c2684d53ce0c7bc912735d5803bf8467fe3368573282f4c96c2dd20a9a65887522cc357a208b3f003fc105338fa390131f0cf726ae589f28fd08918cb9979ddb0dec632691b70da7be15b4143538c0b7d21bb99cb66389dd2f585b85235e8aa40a4aeef899c58df175e90a779fba981f7e42f8468030fb8489d31bcad72ef55c03d60cfbdf13f8b821c05ecd92392cd08fe98baf2868036e38c0db9e79be0b12daf78acf91007a6a875870afa461380e6b7bccab5a665182fcf45d7626580a998caa2553cf614367ddd91ef759339b042e0c08ffd8fa6f1c3098c989f668a04427383e6ab246407c73d62285a39ac370201790f50ec27855f45102a4f8ae4f39ede4cf4236988b009fd223fb97cda4ffd0d628d744e61b57d1a6dec125fd1cb92d1ad589960fa5dc098477d871edf3b7433fbc995dd05b1226f21577068b5505409d4bf0fe9daeb694c69587820d523a497f2a4d7087a58c53ec86aa806bf97942bbc9cf2c2fad632cb3180f73b1ee367f80d63dc1b50f910cf35fb1fc2586458b7fc3ed027d886d3a8c28a1e0be7c5effa2da41af1ceb6835e1c22a9932a0ae0fbdc88c77232c0ccc166fbc5bef01eca0b5cf59f227cd7cba6b0c41b75fef39c6eaea914c6e19ef2681fc14d0643aa39dc83fef6aba4a1d19647a8cee5c5fe908248ceb1931af5d36bc763b927a053020ba93d4de4aabf2c0880f6ab0b5b364e7a4a6b5e1f7ff57e1e85735e55469badfffc15e0ce1c0f78daca414a53ccb565241007667da240b1c2a746c1b45d1c26ee135c7a18631d8803e7ccff7c45619fc03b7a0d77ba203c9951a13d39544fbf66f866582214f9a062c25b3aad179254ac8f57311288fa8a8b483a783ac92655b44d7f66423b33afb96db2f2ff21921ef9375e3ecb372414f5974c682586ec091d528c968b636fdfae20b29b21468a00d8bbe07ba38577e0fc96bc7493b5f33774625b5d8b8da795be5683ff11b7c00f871459156a0483d7dded4862830a6584f1c7000e4b34c12df8b73ebea24e18beab7c45e59c8227c91963113667be665507af4407ecd3ae46d26627cc990821440930bed549220ec885df5ff6372d2343d8e8360314e5ad91d28a3cef8b428ee2c7f58774f558edb3688e8992700d9a4cb238823d8342b298120a3e93b829555f9b23d1b2be82d269a9e7344731dfd9e1449da656baedcd22f3fc5a61277bbf7059469025b462bf6645979dad394f629c316332cb430695fefe67464818b5ddf2aa7706bcd6add91fd5de94ac8b34e05c5b58d8deac84ec85684c5339ef8c877b64975fb3b76e385c51c7c7511810bc0c3cd1e1ffbc315410010d4ebbd2664b4a9bb7dab3692a0122efc0a82b983ecd126911e5fff479655e2d09793370b36caf119dbde26c0487da399b80f9e9870bf0df29fbca910d78f31a81e6e74b1f7738fd3e961bd5868535c44ce6fa2f285a7b5a67c94ec7836bb64acb4bc516b0d9876229f75c8c191236b3a8e1f605e6dbe4dd2224b8a736572c88de06c438f5e2ded90e5d6d8941b4e78285ce3dc4cce4884cd6e42c4454bb0979b36e2af20915e81b0f8882082f419cbda1f34f7a9c7b4164c8189568763c88d1bbed65162a31360934dfdee77459c87d2e5c085168215218d5c9ed74b33e6be2eca033b33c1367cd583bb3b505b139f7ecfafb40a087af3dc9ffd4df751ce3c72bc47cfb50f5292f7080c134732c9a5436fe3cd9dca7f19f7b53a75804d3225289a3839831da2c22a236ab56b69550950bf5f0d2ea4f619938153367f0b59eb509d39ba40530124c75b9af9b13d8d32432daf4ad2e23f1e63935edc91e7ce7c93648541583f4c15739f68956007d2fb3b71c12f13433a33309dd23bd90a55ab051586c38f8a4d18d46609a32e7a2f38ef2aa39f077cad19b1f10552124c01b86c400f9701d32b5378a3eafdf633c9b2eda4fd853705b7124a7057edfc9ef6dfc769fc58e796267f81f4327542ef0456e5c245d95bf7ce8c4413e29aa8d7746fa1065a3f101525753318e95a6625be3184dc3c429b15878dfdfa87738705732b6fd511d30feb1bc2dca5ba47c0a80cae82619e82a181337aa616d581a36a3869d1d028097a7e1ab102ba49e8b90602132705535b29379018c412383a9fcc91e3b591f8fe0f6126ec3c2c37a1508035ba9279843c8187442e955c2956748c9f531f2678cb3c13061abb6f94be045e182525d4de878dc8a6f73038a6886c83e930753ceae5bc1ddf5ffd2c1ecb6cb79cb0808dd480c304bbc0db6beb872246269a9e7344731dfd9e1449da656baedcd22f3fc5a61277bbf7059469025b462b089c02447e334bbebf2761b24d9137a7fde6c793c151d50c23b12084635f801201546d260e3d6a83768e0382e0a373776b58499763d2d9158ae1ba8fb828c58aef5e02abfad2620498a5b9eea40509b75dccf5c752da6bc9d9900e2c8a1c14404cd967a8b4ccb4283a9f98e005aded26f0230ab76a0c7ecd7d0f610691bb4f61c75f52f8b1c11d92281762a50ce1eec95f38eb94e60c72e644f927a9ab8593cc35a0f078a10ac88f381a9b1affa6f9878a9c2d13e963be719e979075f99929d87e0b32f4898157afd7d2fc740e0261dd9b3bdab2e11473819966b9900343bd7954af30d2fdedb07167bf9e0f1043f32cb8cc2d10389c7106bbaf505b8f68a891de5cd661bdcc42db8986da5a6a16973a2c0e3365244129d9116b1a55f7d5566f9201d3d0ab3fdbd8d588691de3a3de6aed4cf985ffb594490d162ed0236687930ba11d665a335b4a93666561105c6e8642a868379f26047aac9e52e59403220ec53ebe21e9c64350eda42b4bb9f71d658a74aa1ed5baf65246093cbc5f187dd48b81f8243faac08d178680767a4acc4ebf9562fe490719e3df2e8f56dc43a868c2906f74b41429c859a640288fee7b4701b86c400f9701d32b5378a3eafdf6333f62892f2560182472898cf3a259bb5bcd0f65b59f6ba3d6659102b9461d63f47cb3ddd1241ea4d717e355aeca0f4d487690929e1c516b2f2d09f08ccc7528eecd07ceb54714dc4abc80cadbd35c08c48ec8fa9f6c7eafe20af67765c3020b16608e38ec95e65727fd7cc55070ec4b4d64779f1510b3cce5700d1a4f5c02baab2f38ef2aa39f077cad19b1f10552124c01b86c400f9701d32b5378a3eafdf63323484619f231bc91fcb8981692e6795d2ffb9e53923bb45903b0ab617f3c30855d283be4bd376162b302778c60232e29cc02d85beface4e614c7ffa0bc5a3b2b8141c27c72b82938980788c3df1639db579b2dfc57fdcaa4d32fc03d7182f6b1e18f7c0f568138e778ae0e7e6fb207826ce79a18b76b7b95a7f9ad27c664eb228b23b36cc3cf9f84e37e69ac81390c073d9a2f1b7fb3508a3e96f4ca3d24f6ca96301b35455ff7c92fe6a98ca860ba36e6be2eca033b33c1367cd583bb3b505bdae2a65ae2c80efca8f3b89278011e3d03c935fd235a70ba7e0804fa82760dfe, '2022-09-20 19:00:20');

-- --------------------------------------------------------

--
-- Estrutura da tabela `terms`
--

CREATE TABLE `terms` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `version` int NOT NULL,
  `registrationDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabela para os termos (LGPD, política de inscrição etc.)';

--
-- Extraindo dados da tabela `terms`
--

INSERT INTO `terms` (`id`, `name`, `version`, `registrationDate`) VALUES
(2, 'Termo para alunos v1', 1, '2022-09-12 21:43:43');

-- --------------------------------------------------------

--
-- Estrutura da tabela `traits`
--

CREATE TABLE `traits` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `fileExtension` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Traços aplicáveis a eventos.';

--
-- Extraindo dados da tabela `traits`
--

INSERT INTO `traits` (`id`, `name`, `description`, `fileExtension`) VALUES
(1, 'Evento acessível', 'Evento com local acessível para PCD.', 'png'),
(3, 'Contato disponível1222', '', 'png');

-- --------------------------------------------------------

--
-- Estrutura da tabela `userpermissions`
--

CREATE TABLE `userpermissions` (
  `userId` int NOT NULL,
  `permMod` varchar(5) NOT NULL,
  `permId` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Extraindo dados da tabela `userpermissions`
--

INSERT INTO `userpermissions` (`userId`, `permMod`, `permId`) VALUES
(1, 'ARTM', 1),
(1, 'ARTM', 2),
(1, 'ARTM', 3),
(1, 'ARTM', 4),
(1, 'CALEN', 1),
(1, 'CALEN', 2),
(1, 'CALEN', 3),
(1, 'CALEN', 4),
(1, 'CALEN', 5),
(1, 'CHKLS', 1),
(1, 'CHKLS', 2),
(1, 'CHKLS', 3),
(1, 'CHKLS', 4),
(1, 'ENUM', 1),
(1, 'ENUM', 2),
(1, 'ENUM', 3),
(1, 'EVENT', 1),
(1, 'EVENT', 2),
(1, 'EVENT', 3),
(1, 'EVENT', 4),
(1, 'EVENT', 5),
(1, 'EVENT', 6),
(1, 'EVENT', 7),
(1, 'EVENT', 8),
(1, 'EVENT', 9),
(1, 'EVENT', 10),
(1, 'EVENT', 11),
(1, 'EVENT', 12),
(1, 'EVENT', 13),
(1, 'LIBR', 2),
(1, 'LIBR', 3),
(1, 'LIBR', 4),
(1, 'LIBR', 5),
(1, 'LIBR', 6),
(1, 'LIBR', 7),
(1, 'LIBR', 8),
(1, 'LIBR', 9),
(1, 'LIBR', 10),
(1, 'LIBR', 11),
(1, 'LIBR', 12),
(1, 'LIBR', 13),
(1, 'LIBR', 14),
(1, 'LIBR', 15),
(1, 'LIBR', 16),
(1, 'MAIL', 1),
(1, 'MAIL', 2),
(1, 'PROFE', 1),
(1, 'PROFE', 2),
(1, 'PROFE', 3),
(1, 'PROFE', 4),
(1, 'PROFE', 5),
(1, 'PROFE', 6),
(1, 'PROFE', 7),
(1, 'PROFE', 8),
(1, 'PROFE', 9),
(1, 'PROFE', 10),
(1, 'PROFE', 11),
(1, 'PROFE', 12),
(1, 'PROFE', 13),
(1, 'SOCN', 1),
(1, 'SRVEY', 1),
(1, 'SRVEY', 2),
(1, 'SRVEY', 3),
(1, 'SRVEY', 4),
(1, 'SRVEY', 5),
(1, 'TERMS', 1),
(1, 'TERMS', 2),
(1, 'TERMS', 3),
(1, 'TERMS', 4),
(1, 'TRAIT', 1),
(1, 'TRAIT', 2),
(1, 'TRAIT', 3),
(1, 'TRAIT', 4),
(1, 'USERS', 1),
(1, 'VMLEG', 1),
(1, 'VMLEG', 2),
(1, 'VMLEG', 3),
(1, 'VMLEG', 4),
(1, 'VMPAR', 1),
(1, 'VMPAR', 2),
(1, 'VMPAR', 3),
(1, 'VMPAR', 4),
(1, 'VMPTY', 1),
(1, 'VMPTY', 2),
(1, 'VMPTY', 3),
(1, 'VMPTY', 4),
(1, 'VMSTU', 1),
(1, 'VMSTU', 2),
(1, 'VMSTU', 3),
(1, 'VMSTU', 4),
(2, 'ARTM', 1),
(2, 'ENUM', 3),
(2, 'EVENT', 4),
(2, 'LIBR', 2),
(2, 'LIBR', 3),
(2, 'LIBR', 4),
(2, 'LIBR', 5),
(2, 'LIBR', 6),
(2, 'LIBR', 7),
(2, 'LIBR', 8),
(2, 'LIBR', 9),
(2, 'LIBR', 10),
(2, 'LIBR', 11),
(2, 'LIBR', 12),
(2, 'LIBR', 13),
(2, 'LIBR', 14),
(2, 'LIBR', 15),
(2, 'MAIL', 1),
(2, 'PROFE', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(80) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `passwordHash` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `name`, `passwordHash`) VALUES
(1, 'Victor', '$2y$10$/Y1jDB8PYpFfNkkgoF71Qu2W.Wr2.NvfH3H9XXY31e0JVZ5ND5b3C');

-- --------------------------------------------------------

--
-- Estrutura da tabela `vereadormirimlegislatures`
--

CREATE TABLE `vereadormirimlegislatures` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `begin` date NOT NULL,
  `end` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Legislaturas do programa Vereador Mirim';

--
-- Extraindo dados da tabela `vereadormirimlegislatures`
--

INSERT INTO `vereadormirimlegislatures` (`id`, `name`, `begin`, `end`) VALUES
(2, 'Legislatura 2022-2024', '2022-07-01', '2024-07-01');

-- --------------------------------------------------------

--
-- Estrutura da tabela `vereadormirimparents`
--

CREATE TABLE `vereadormirimparents` (
  `id` int UNSIGNED NOT NULL,
  `name` varbinary(400) NOT NULL,
  `email` varbinary(400) NOT NULL,
  `parentDataJson` varbinary(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `vereadormirimparties`
--

CREATE TABLE `vereadormirimparties` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `acronym` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `number` int NOT NULL,
  `moreInfos` text COLLATE utf8mb4_general_ci,
  `logoFileExtension` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `vereadormirimparties`
--

INSERT INTO `vereadormirimparties` (`id`, `name`, `acronym`, `number`, `moreInfos`, `logoFileExtension`) VALUES
(7, 'Partido Teste', 'PT', 14, NULL, 'jpg');

-- --------------------------------------------------------

--
-- Estrutura da tabela `vereadormirimschools`
--

CREATE TABLE `vereadormirimschools` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `numberOfVotingStudents` int NOT NULL,
  `email` varbinary(400) NOT NULL,
  `schoolDataJson` varbinary(1000) NOT NULL,
  `directorName` varbinary(400) NOT NULL,
  `directorDataJson` varbinary(1000) NOT NULL,
  `coordinatorName` varbinary(400) NOT NULL,
  `coordinatorDataJson` varbinary(1000) NOT NULL,
  `registrationDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `vereadormirimsignatures`
--

CREATE TABLE `vereadormirimsignatures` (
  `id` int NOT NULL,
  `vmStudentId` int UNSIGNED NOT NULL,
  `vmStudentSignatureDateTime` datetime DEFAULT NULL,
  `vmParentId` int UNSIGNED DEFAULT NULL,
  `vmParentSignatureDateTime1` datetime DEFAULT NULL,
  `vmParentSignatureDateTime2` datetime DEFAULT NULL,
  `vmSchoolId` int UNSIGNED DEFAULT NULL,
  `vmSchoolSignatureDateTime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='Assinaturas de alunos, pais e diretores de escola dos VMs.';

-- --------------------------------------------------------

--
-- Estrutura da tabela `vereadormirimstudentattachments`
--

CREATE TABLE `vereadormirimstudentattachments` (
  `id` int UNSIGNED NOT NULL,
  `vmStudentId` int UNSIGNED NOT NULL,
  `docType` char(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fileName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `vereadormirimstudents`
--

CREATE TABLE `vereadormirimstudents` (
  `id` int UNSIGNED NOT NULL,
  `name` varbinary(400) NOT NULL,
  `email` varbinary(400) NOT NULL,
  `studentDataJson` varbinary(1000) NOT NULL,
  `partyId` int UNSIGNED DEFAULT NULL,
  `vmParentId` int UNSIGNED DEFAULT NULL,
  `vmLegislatureId` int UNSIGNED NOT NULL,
  `registrationDate` datetime NOT NULL,
  `photoFileExtension` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `vereadormirimstudents`
--

INSERT INTO `vereadormirimstudents` (`id`, `name`, `email`, `studentDataJson`, `partyId`, `vmParentId`, `vmLegislatureId`, `registrationDate`, `photoFileExtension`) VALUES
(1, 0xa53ff02d905159fa69ba43c7a44e3db3, 0x3180597242e8e685a72e1a4c8e3fab6fdff7a39e5d151209504a8bc1973b247e, 0xbc25b45a2d8f7f7497f0bba1184a54e334564e2c9c0fadd6a4c8e7411304d31278f80f1577abdb55d618d71f348a8b9ead8674ffd273a2b1af5b8ca87a8190fc065a38299179f45c281afe3c8edf91ec35515478ffc1d141ef91d926e553980538838282a80b5a87dbe00ae4cb1c112ef74b419f36769783b25dfdc10f96d53de7acdfca6c6169ab9774b94e31ca6a44333c8f1b7ebbc864442ed3e92487f0d7ccb2666d45b24d16325926daa34c774d6703787ef6616b55a39ab1d14d72dc1ecbdce4806b20a5b9e751149b5c7b16b677949e7f6ad3e86682a344fa8fe43ce81b43a8afedbf7fe0c822b0a34749e1dad9ef0b300a8c614780fc26042707666f6c6c33651de2b79139a2dba2c5594faae342f38a368082dd0bf52fa2922804a5ffdef1a282eb200f63800c67a8e875f79bab76716880ff94d3e9b58acec9f2e17ee00e9cb10d3881cc2b131ad4d5a296df07af92211e8caccec18206e54a341c3ae24872d5c439998019f9be05aa33e6d07843060ebb44dada515699da1442bd, 7, NULL, 2, '2023-01-14 00:00:00', 'png');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `artattachments`
--
ALTER TABLE `artattachments`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `artpieces`
--
ALTER TABLE `artpieces`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `CMI_propertyNumber` (`CMI_propertyNumber`);
ALTER TABLE `artpieces` ADD FULLTEXT KEY `name` (`name`,`artist`,`technique`,`donor`,`location`,`description`);

--
-- Índices para tabela `calendardates`
--
ALTER TABLE `calendardates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parentId` (`parentId`),
  ADD KEY `date` (`date`);

--
-- Índices para tabela `calendardatestraits`
--
ALTER TABLE `calendardatestraits`
  ADD KEY `calendarDateId` (`calendarDateId`),
  ADD KEY `traitId` (`traitId`);

--
-- Índices para tabela `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `enums`
--
ALTER TABLE `enums`
  ADD PRIMARY KEY (`type`,`id`);

--
-- Índices para tabela `eventattachments`
--
ALTER TABLE `eventattachments`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `eventchecklists`
--
ALTER TABLE `eventchecklists`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `eventdates`
--
ALTER TABLE `eventdates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `locationId` (`locationId`);

--
-- Índices para tabela `eventdatesprofessors`
--
ALTER TABLE `eventdatesprofessors`
  ADD KEY `professorId` (`professorId`),
  ADD KEY `eventDateId` (`eventDateId`);

--
-- Índices para tabela `eventdatestraits`
--
ALTER TABLE `eventdatestraits`
  ADD KEY `eventDateId` (`eventDateId`),
  ADD KEY `traitId` (`traitId`);

--
-- Índices para tabela `eventlocations`
--
ALTER TABLE `eventlocations`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `checklistId` (`checklistId`),
  ADD KEY `surveyTemplateId` (`surveyTemplateId`),
  ADD KEY `subscriptionTemplateId` (`subscriptionTemplateId`);
ALTER TABLE `events` ADD FULLTEXT KEY `name` (`name`,`moreInfos`);

--
-- Índices para tabela `eventsurveys`
--
ALTER TABLE `eventsurveys`
  ADD PRIMARY KEY (`id`),
  ADD KEY `eventId` (`eventId`),
  ADD KEY `subscriptionId` (`subscriptionId`);

--
-- Índices para tabela `eventworkplanattachments`
--
ALTER TABLE `eventworkplanattachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `workPlanId` (`workPlanId`);

--
-- Índices para tabela `eventworkplans`
--
ALTER TABLE `eventworkplans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `eventId` (`eventId`);

--
-- Índices para tabela `jsontemplates`
--
ALTER TABLE `jsontemplates`
  ADD PRIMARY KEY (`id`,`type`) USING BTREE;
ALTER TABLE `jsontemplates` ADD FULLTEXT KEY `templateName` (`name`);

--
-- Índices para tabela `libraryborrowedpublications`
--
ALTER TABLE `libraryborrowedpublications`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `librarycollection`
--
ALTER TABLE `librarycollection`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `librarycollection` ADD FULLTEXT KEY `author` (`author`,`title`,`cdu`,`cdd`,`isbn`,`publisher_edition`,`provider`);

--
-- Índices para tabela `libraryreservations`
--
ALTER TABLE `libraryreservations`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `libraryusers`
--
ALTER TABLE `libraryusers`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `mailing`
--
ALTER TABLE `mailing`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`permMod`,`permId`);

--
-- Índices para tabela `presencerecords`
--
ALTER TABLE `presencerecords`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `professorcertificates`
--
ALTER TABLE `professorcertificates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `workSheetId` (`workSheetId`);

--
-- Índices para tabela `professordocsattachments`
--
ALTER TABLE `professordocsattachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `professorId` (`professorId`);

--
-- Índices para tabela `professors`
--
ALTER TABLE `professors`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `professorsotps`
--
ALTER TABLE `professorsotps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `professorId` (`professorId`);

--
-- Índices para tabela `professorworkdocsignatures`
--
ALTER TABLE `professorworkdocsignatures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `workSheetId` (`workSheetId`),
  ADD KEY `professorId` (`professorId`);

--
-- Índices para tabela `professorworkproposals`
--
ALTER TABLE `professorworkproposals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ownerProfessorId` (`ownerProfessorId`);
ALTER TABLE `professorworkproposals` ADD FULLTEXT KEY `name` (`name`,`moreInfos`);

--
-- Índices para tabela `professorworksheets`
--
ALTER TABLE `professorworksheets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `eventId` (`eventId`),
  ADD KEY `professorWorkProposalId` (`professorWorkProposalId`),
  ADD KEY `professorId` (`professorId`);

--
-- Índices para tabela `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`name`);

--
-- Índices para tabela `subscriptionstudentsnew`
--
ALTER TABLE `subscriptionstudentsnew`
  ADD PRIMARY KEY (`id`),
  ADD KEY `eventId` (`eventId`);

--
-- Índices para tabela `terms`
--
ALTER TABLE `terms`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `terms` ADD FULLTEXT KEY `name` (`name`);

--
-- Índices para tabela `traits`
--
ALTER TABLE `traits`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `traits` ADD FULLTEXT KEY `name` (`name`,`description`);

--
-- Índices para tabela `userpermissions`
--
ALTER TABLE `userpermissions`
  ADD PRIMARY KEY (`userId`,`permMod`,`permId`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Índices para tabela `vereadormirimlegislatures`
--
ALTER TABLE `vereadormirimlegislatures`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `vereadormirimlegislatures` ADD FULLTEXT KEY `name` (`name`);

--
-- Índices para tabela `vereadormirimparents`
--
ALTER TABLE `vereadormirimparents`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `vereadormirimparties`
--
ALTER TABLE `vereadormirimparties`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `vereadormirimparties` ADD FULLTEXT KEY `name` (`name`,`acronym`);

--
-- Índices para tabela `vereadormirimschools`
--
ALTER TABLE `vereadormirimschools`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `vereadormirimsignatures`
--
ALTER TABLE `vereadormirimsignatures`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vmStudentId` (`vmStudentId`);

--
-- Índices para tabela `vereadormirimstudentattachments`
--
ALTER TABLE `vereadormirimstudentattachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vmStudentId` (`vmStudentId`);

--
-- Índices para tabela `vereadormirimstudents`
--
ALTER TABLE `vereadormirimstudents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vmLegislatureId` (`vmLegislatureId`),
  ADD KEY `vmParentId` (`vmParentId`),
  ADD KEY `partyId` (`partyId`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `artattachments`
--
ALTER TABLE `artattachments`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=359;

--
-- AUTO_INCREMENT de tabela `artpieces`
--
ALTER TABLE `artpieces`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=352;

--
-- AUTO_INCREMENT de tabela `calendardates`
--
ALTER TABLE `calendardates`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de tabela `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `enums`
--
ALTER TABLE `enums`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `eventattachments`
--
ALTER TABLE `eventattachments`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de tabela `eventchecklists`
--
ALTER TABLE `eventchecklists`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de tabela `eventdates`
--
ALTER TABLE `eventdates`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de tabela `eventlocations`
--
ALTER TABLE `eventlocations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `events`
--
ALTER TABLE `events`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `eventsurveys`
--
ALTER TABLE `eventsurveys`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `eventworkplanattachments`
--
ALTER TABLE `eventworkplanattachments`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `eventworkplans`
--
ALTER TABLE `eventworkplans`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `jsontemplates`
--
ALTER TABLE `jsontemplates`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `libraryborrowedpublications`
--
ALTER TABLE `libraryborrowedpublications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `librarycollection`
--
ALTER TABLE `librarycollection`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2483;

--
-- AUTO_INCREMENT de tabela `libraryreservations`
--
ALTER TABLE `libraryreservations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `libraryusers`
--
ALTER TABLE `libraryusers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `mailing`
--
ALTER TABLE `mailing`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `presencerecords`
--
ALTER TABLE `presencerecords`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de tabela `professorcertificates`
--
ALTER TABLE `professorcertificates`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `professordocsattachments`
--
ALTER TABLE `professordocsattachments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de tabela `professors`
--
ALTER TABLE `professors`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `professorsotps`
--
ALTER TABLE `professorsotps`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT de tabela `professorworkdocsignatures`
--
ALTER TABLE `professorworkdocsignatures`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `professorworkproposals`
--
ALTER TABLE `professorworkproposals`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `professorworksheets`
--
ALTER TABLE `professorworksheets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `subscriptionstudentsnew`
--
ALTER TABLE `subscriptionstudentsnew`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `terms`
--
ALTER TABLE `terms`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `traits`
--
ALTER TABLE `traits`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `vereadormirimlegislatures`
--
ALTER TABLE `vereadormirimlegislatures`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `vereadormirimparents`
--
ALTER TABLE `vereadormirimparents`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `vereadormirimparties`
--
ALTER TABLE `vereadormirimparties`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `vereadormirimschools`
--
ALTER TABLE `vereadormirimschools`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `vereadormirimsignatures`
--
ALTER TABLE `vereadormirimsignatures`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `vereadormirimstudentattachments`
--
ALTER TABLE `vereadormirimstudentattachments`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `vereadormirimstudents`
--
ALTER TABLE `vereadormirimstudents`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `calendardates`
--
ALTER TABLE `calendardates`
  ADD CONSTRAINT `calendardates_ibfk_1` FOREIGN KEY (`parentId`) REFERENCES `calendardates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `calendardatestraits`
--
ALTER TABLE `calendardatestraits`
  ADD CONSTRAINT `calendardatestraits_ibfk_1` FOREIGN KEY (`calendarDateId`) REFERENCES `calendardates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `calendardatestraits_ibfk_2` FOREIGN KEY (`traitId`) REFERENCES `traits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `eventdates`
--
ALTER TABLE `eventdates`
  ADD CONSTRAINT `eventdates_ibfk_1` FOREIGN KEY (`locationId`) REFERENCES `eventlocations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limitadores para a tabela `eventdatesprofessors`
--
ALTER TABLE `eventdatesprofessors`
  ADD CONSTRAINT `eventdatesprofessors_ibfk_1` FOREIGN KEY (`eventDateId`) REFERENCES `eventdates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `eventdatesprofessors_ibfk_2` FOREIGN KEY (`professorId`) REFERENCES `professors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `eventdatestraits`
--
ALTER TABLE `eventdatestraits`
  ADD CONSTRAINT `eventdatestraits_ibfk_1` FOREIGN KEY (`eventDateId`) REFERENCES `eventdates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `eventdatestraits_ibfk_2` FOREIGN KEY (`traitId`) REFERENCES `traits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`checklistId`) REFERENCES `eventchecklists` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limitadores para a tabela `eventworkplanattachments`
--
ALTER TABLE `eventworkplanattachments`
  ADD CONSTRAINT `eventworkplanattachments_ibfk_1` FOREIGN KEY (`workPlanId`) REFERENCES `eventworkplans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `eventworkplans`
--
ALTER TABLE `eventworkplans`
  ADD CONSTRAINT `eventworkplans_ibfk_1` FOREIGN KEY (`eventId`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `professordocsattachments`
--
ALTER TABLE `professordocsattachments`
  ADD CONSTRAINT `professordocsattachments_ibfk_1` FOREIGN KEY (`professorId`) REFERENCES `professors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `professorsotps`
--
ALTER TABLE `professorsotps`
  ADD CONSTRAINT `professorsotps_ibfk_1` FOREIGN KEY (`professorId`) REFERENCES `professors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `professorworkdocsignatures`
--
ALTER TABLE `professorworkdocsignatures`
  ADD CONSTRAINT `professorworkdocsignatures_ibfk_1` FOREIGN KEY (`professorId`) REFERENCES `professors` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `professorworkdocsignatures_ibfk_2` FOREIGN KEY (`workSheetId`) REFERENCES `professorworksheets` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limitadores para a tabela `professorworkproposals`
--
ALTER TABLE `professorworkproposals`
  ADD CONSTRAINT `professorworkproposals_ibfk_1` FOREIGN KEY (`ownerProfessorId`) REFERENCES `professors` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limitadores para a tabela `professorworksheets`
--
ALTER TABLE `professorworksheets`
  ADD CONSTRAINT `professorworksheets_ibfk_1` FOREIGN KEY (`eventId`) REFERENCES `events` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `professorworksheets_ibfk_2` FOREIGN KEY (`professorWorkProposalId`) REFERENCES `professorworkproposals` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `professorworksheets_ibfk_3` FOREIGN KEY (`professorId`) REFERENCES `professors` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limitadores para a tabela `vereadormirimstudentattachments`
--
ALTER TABLE `vereadormirimstudentattachments`
  ADD CONSTRAINT `vereadormirimstudentattachments_ibfk_1` FOREIGN KEY (`vmStudentId`) REFERENCES `vereadormirimstudents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `vereadormirimstudents`
--
ALTER TABLE `vereadormirimstudents`
  ADD CONSTRAINT `vereadormirimstudents_ibfk_1` FOREIGN KEY (`vmLegislatureId`) REFERENCES `vereadormirimlegislatures` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vereadormirimstudents_ibfk_2` FOREIGN KEY (`vmParentId`) REFERENCES `vereadormirimparents` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `vereadormirimstudents_ibfk_4` FOREIGN KEY (`partyId`) REFERENCES `vereadormirimparties` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
