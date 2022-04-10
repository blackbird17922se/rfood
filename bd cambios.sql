ALTER TABLE `mesa` ADD `disponible` BOOLEAN NOT NULL DEFAULT TRUE AFTER `nom`;
ALTER TABLE `venta` ADD `id_mesa` INT(3) NOT NULL AFTER `id_orden`;