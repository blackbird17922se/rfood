------BUILD 4.5.1.0
ALTER TABLE `det_pedido` ADD `prod_pagado` INT(2) NOT NULL ;
ALTER TABLE `det_pedido` ADD `cant_cuenta_dividida` INT(200) NOT NULL ;
ALTER TABLE `det_pedido` ADD `prod_pagado` INT(11) NOT NULL ;
UPDATE `det_pedido` SET `cant_cuenta_dividida`=`det_cant` WHERE `cant_cuenta_dividida` = 0

-----------------------------OLD---------------------------------
-------------------------BUID 4.1-----------------------------------------------
ALTER TABLE `venta`
  DROP `id_mesero`,
  DROP `id_coc_lider`,
  DROP `id_mesa`;

ALTER TABLE `pedido` DROP `id_cajero`;
INSERT INTO `mesa` (`id_mesa`, `nom`, `disponible`) VALUES ('-1', 'Domicilio', '1');

------------------------done
ALTER TABLE `mesa` ADD `disponible` BOOLEAN NOT NULL DEFAULT TRUE AFTER `nom`;
ALTER TABLE `venta` ADD `id_mesa` INT(3) NOT NULL AFTER `id_orden`;



--para mesas
UPDATE `mesa` SET `disponible`=1