CREATE TRIGGER `check_quantite_before_insert` BEFORE INSERT ON `contient`
 FOR EACH ROW BEGIN
  DECLARE nb_personnes INT DEFAULT 0;
  SELECT nbpers INTO nb_personnes FROM COMMANDE WHERE numcom = NEW.numcom;
  IF NEW.quantite > nb_personnes THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'La quantité d’un plat commandé ne doit pas dépasser le nombre de personnes indiqué dans la commande.';
  END IF;
END

-- --------------------------------------------------------

CREATE TRIGGER `insert_into_auditer` AFTER INSERT ON `commande`
 FOR EACH ROW BEGIN
    DECLARE grade VARCHAR(20);

    SELECT s.grade
    INTO grade
    FROM SERVEUR s
             JOIN AFFECTER a ON s.numserv = a.numserv
    WHERE a.numtab = NEW.numtab
      AND a.dataff = NEW.datcom;

    IF grade = 'maitre hotel' AND (NEW.montcom / NEW.nbpers) < 15 THEN
        INSERT INTO AUDITER (numcom, numtab, datcom, nbpers, datpaie, montcom)
        VALUES (NEW.numcom, NEW.numtab, NEW.datcom, NEW.nbpers, NEW.datpaie, NEW.montcom);
    END IF;
END
