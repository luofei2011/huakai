
-- 所有沂星【整车】的数据在该表中查询；其他公司的数据将来会再建新的表来存储
-- 沂星【整车】数据表 -----------------------------------------------------------------
DROP TABLE IF EXISTS `Vehicle_Data_YX`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Vehicle_Data_YX` (
  `Id` int(11) unsigned NOT NULL AUTO_INCREMENT, -- 无特殊意义，数据编号
  `VehicleId`	tinyint(3) unsigned NOT NULL, -- 电动车编号
  `Name` varchar(20) NOT NULL, -- 车辆名称
  `Day` date NOT NULL, -- 数据存储的日期，格式为 YYYY-MM-DD
  `Soc` text, -- 当天所有的SOC数据，当前默认50个，储存格式如下：100;99;98...；取出后以分号分割
  `Voltage` text, -- 当天所有的总电压数据，当前默认50个，储存格式同SOC
  `Temperature` text, -- 当天所有的温度数据，当前默认50个，储存格式同SOC
  `Current` text DEFAULT NULL, -- 当天所有的总电流数据，当前默认为NULL
  PRIMARY KEY (`Id`),
  KEY (`VehicleId`),
  KEY (`Day`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


-- 所有沂星【电池组】的温度数据在该表中查询；该组22条电压曲线的数据则根据Vechicle去Battery_Data_YX表中查询
-- 沂星【电池组】数据表-------------------------------------------------------------------
DROP TABLE IF EXISTS `Package_Data_YX`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Package_Data_YX` (
  `Id` int(11) unsigned NOT NULL AUTO_INCREMENT, -- 无特殊意义，数据编号
  `VehicleId` tinyint(3) unsigned NOT NULL, -- 识别该【电池组】属于哪辆电动车
  `Number` tinyint(3) unsigned NOT NULL, -- 该【电池组】的编号：1号电池组对应1-22号电池......
  `Day` date NOT NULL, -- 数据存储的日期，格式为 YYYY-MM-DD
  `Temperature1` text, -- 该组1号温度采集点当天所有的数据，当前默认50个，取出后以分号分割
  `Temperature2` text, -- 该组2号温度采集点当天所有的数据，当前默认50个，取出后以分号分割
  `Temperature3` text, -- 该组3号温度采集点当天所有的数据，当前默认50个，取出后以分号分割
  `Temperature4` text, -- 同上 
  `Temperature5` text, -- 同上
  `Temperature6` text, -- 同上
  PRIMARY KEY (`id`),
  KEY (`VehicleId`),
  KEY (`Number`),
  KEY (`Day`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;



-- 所有沂星【单体电池】的数据在该表中查询；同时该表可查询某台电动车任意一个电池组的22个电压数据
-- 沂星【单体电池】的【电池数据表】-----------------------------------------------------
DROP TABLE IF EXISTS `Battery_Data_YX`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Battery_Data_YX` (
  `Id` int(11) unsigned NOT NULL AUTO_INCREMENT, -- 无特殊意义，数据编号
  `VehicleId` tinyint(3) unsigned NOT NULL, -- 识别该【电池数据表】是哪辆电动车的数据
  `Day` date NOT NULL, -- 数据存储的日期，格式为 YYYY-MM-DD
  `Battery1` text, -- 该车1号【单体电池】当天所有的电压数据；当前默认50个，取出后以分号分割
  `Battery2` text, -- 该车2号【单体电池】当天所有的电压数据；当前默认50个，取出后以分号分割
  `Battery3` text, -- 该车3号【单体电池】当天所有的电压数据；当前默认50个，取出后以分号分割
  `Battery4` text, -- 同上
  `Battery5` text,
  `Battery6` text,
  `Battery7` text,
  `Battery8` text,
  `Battery9` text,
  `Battery10` text,
  `Battery11` text,
  `Battery12` text,
  `Battery13` text,
  `Battery14` text,
  `Battery15` text,
  `Battery16` text,
  `Battery17` text,
  `Battery18` text,
  `Battery19` text,
  `Battery20` text,
  `Battery21` text,
  `Battery22` text,
  `Battery23` text,
  `Battery24` text,
  `Battery25` text,
  `Battery26` text,
  `Battery27` text,
  `Battery28` text,
  `Battery29` text,
  `Battery30` text,
  `Battery31` text,
  `Battery32` text,
  `Battery33` text,
  `Battery34` text,
  `Battery35` text,
  `Battery36` text,
  `Battery37` text,
  `Battery38` text,
  `Battery39` text,
  `Battery40` text,
  `Battery41` text,
  `Battery42` text,
  `Battery43` text,
  `Battery44` text,
  `Battery45` text,
  `Battery46` text,
  `Battery47` text,
  `Battery48` text,
  `Battery49` text,
  `Battery50` text,
  `Battery51` text,
  `Battery52` text,
  `Battery53` text,
  `Battery54` text,
  `Battery55` text,
  `Battery56` text,
  `Battery57` text,
  `Battery58` text,
  `Battery59` text,
  `Battery60` text,
  `Battery61` text,
  `Battery62` text,
  `Battery63` text,
  `Battery64` text,
  `Battery65` text,
  `Battery66` text,
  `Battery67` text,
  `Battery68` text,
  `Battery69` text,
  `Battery70` text,
  `Battery71` text,
  `Battery72` text,
  `Battery73` text,
  `Battery74` text,
  `Battery75` text,
  `Battery76` text,
  `Battery77` text,
  `Battery78` text,
  `Battery79` text,
  `Battery80` text,
  `Battery81` text,
  `Battery82` text,
  `Battery83` text,
  `Battery84` text,
  `Battery85` text,
  `Battery86` text,
  `Battery87` text,
  `Battery88` text,
  `Battery89` text,
  `Battery90` text,
  `Battery91` text,
  `Battery92` text,
  `Battery93` text,
  `Battery94` text,
  `Battery95` text,
  `Battery96` text,
  `Battery97` text,
  `Battery98` text,
  `Battery99` text,
  `Battery100` text,
  `Battery101` text,
  `Battery102` text,
  `Battery103` text,
  `Battery104` text,
  `Battery105` text,
  `Battery106` text,
  `Battery107` text,
  `Battery108` text,
  `Battery109` text,
  `Battery110` text,
  `Battery111` text,
  `Battery112` text,
  `Battery113` text,
  `Battery114` text,
  `Battery115` text,
  `Battery116` text,
  `Battery117` text,
  `Battery118` text,
  `Battery119` text,
  `Battery120` text,
  `Battery121` text,
  `Battery122` text,
  `Battery123` text,
  `Battery124` text,
  `Battery125` text,
  `Battery126` text,
  `Battery127` text,
  `Battery128` text,
  `Battery129` text,
  `Battery130` text,
  `Battery131` text,
  `Battery132` text,
  `Battery133` text,
  `Battery134` text,
  `Battery135` text,
  `Battery136` text,
  `Battery137` text,
  `Battery138` text,
  `Battery139` text,
  `Battery140` text,
  `Battery141` text,
  `Battery142` text,
  `Battery143` text,
  `Battery144` text,
  `Battery145` text,
  `Battery146` text,
  `Battery147` text,
  `Battery148` text,
  `Battery149` text,
  `Battery150` text,
  `Battery151` text,
  `Battery152` text,
  `Battery153` text,
  `Battery154` text,
  `Battery155` text,
  `Battery156` text,
  `Battery157` text,
  `Battery158` text,
  `Battery159` text,
  `Battery160` text,
  `Battery161` text,
  `Battery162` text,
  `Battery163` text,
  `Battery164` text,
  `Battery165` text,
  `Battery166` text,
  `Battery167` text,
  `Battery168` text,
  `Battery169` text,
  `Battery170` text,
  `Battery171` text,
  `Battery172` text,
  `Battery173` text,
  `Battery174` text,
  `Battery175` text,
  `Battery176` text,
  PRIMARY KEY (`id`),
  KEY (`VehicleId`),
  KEY (`Day`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

