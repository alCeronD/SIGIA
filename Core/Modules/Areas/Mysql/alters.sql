--
-- Indices de la tabla `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`ar_cod`),
  ADD UNIQUE KEY `ar_nombre` (`ar_nombre`);

-- AUTO_INCREMENT de la tabla `areas`

ALTER TABLE `areas`
  MODIFY `ar_cod` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Código primario del area', AUTO_INCREMENT=10;
