DELIMITER $$
DROP PROCEDURE sp_add_costos$$
CREATE PROCEDURE `sp_add_costos`(
	IN `codigo` VARCHAR(20), IN `inicio` DATE, IN `hasta` DATE, IN `costo` DECIMAL(12,2), 
	IN `ipregistro` VARCHAR(60), IN `registrado` DATETIME, IN `ipmodifica` VARCHAR(60),
	OUT resultado BIGINT
) NOT DETERMINISTIC NO SQL SQL SECURITY DEFINER 
BEGIN
	INSERT INTO costos (codigo, fechadesde, fechahasta, costo, ipregistro, fecharegistro, ipmodifica) 
		VALUES (codigo, inicio, hasta, costo, ipregistro, registrado, ipmodifica);
	SET resultado = LAST_INSERT_ID();
END $$

DELIMITER ;

