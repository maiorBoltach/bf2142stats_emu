# stats keys

import host
import string
from bf2 import g_debug



VEHICLE_TYPE_MEC 	= 0
VEHICLE_TYPE_APC	= 1
VEHICLE_TYPE_TANK	= 2
VEHICLE_TYPE_ANTI_AIR	= 3
VEHICLE_TYPE_TRANSP_AIR	= 4
VEHICLE_TYPE_TITAN	= 5
VEHICLE_TYPE_FAAV	= 6
VEHICLE_TYPE_GDEF	= 7
VEHICLE_TYPE_PARACHUTE	= 8
VEHICLE_TYPE_SOLDIER	= 9
VEHICLE_TYPE_ATTACK_AIR	= 10
VEHICLE_TYPE_POD	= 11
VEHICLE_TYPE_TITAN_AA	= 12
VEHICLE_TYPE_TITAN_GDEF	= 13
VEHICLE_TYPE_IFV	= 14
VEHICLE_TYPE_HOVER_FAV	= 15
	
	
NUM_VEHICLE_TYPES 	= 16
VEHICLE_TYPE_UNKNOWN 	= NUM_VEHICLE_TYPES

WEAPON_TYPE_PAC_SNIPER 	= 0
WEAPON_TYPE_PAC_AR	= 1
WEAPON_TYPE_PAC_AV	= 2
WEAPON_TYPE_PAC_SMG	= 3
WEAPON_TYPE_PAC_LMG	= 4
WEAPON_TYPE_PAC_PISTOL	= 5
WEAPON_TYPE_EU_SNIPER 	= 6
WEAPON_TYPE_EU_AR	= 7
WEAPON_TYPE_EU_AV	= 8
WEAPON_TYPE_EU_SMG	= 9
WEAPON_TYPE_EU_LMG	= 10
WEAPON_TYPE_EU_PISTOL	= 11
WEAPON_TYPE_KNIFE	= 12
WEAPON_TYPE_CARBINE	= 13
WEAPON_TYPE_ADV_SNIPER	= 14
WEAPON_TYPE_HEAVY_AR	= 15
WEAPON_TYPE_LIGHT_AR	= 16
WEAPON_TYPE_HEAVY_AV 	= 17
WEAPON_TYPE_UNLOCK_SMG	= 18
WEAPON_TYPE_UNLOCK_LMG	= 19
WEAPON_TYPE_AUTO_SHOTGUN	= 20
WEAPON_TYPE_MINE	= 21
WEAPON_TYPE_GRENADE	= 22
WEAPON_TYPE_C4		= 23
WEAPON_TYPE_CLAYMORE	= 24
WEAPON_TYPE_RIFLE_ROCKET	= 25
WEAPON_TYPE_SENTRY_GUN	= 26
WEAPON_TYPE_SENTRY_DRONE	= 27
WEAPON_TYPE_GROUND_CANNON	= 28
WEAPON_TYPE_TITAN_CANNON	= 29
WEAPON_TYPE_VEHICLE_AA		= 30
WEAPON_TYPE_EXPLOSIVE_ROUNDS_SHOTGUN	= 31
WEAPON_TYPE_EQUIPMENT_START 	= 32
WEAPON_TYPE_SHOCKPADDLES	= 32
WEAPON_TYPE_WRENCH		= 33
WEAPON_TYPE_MEDIC_PACK	= 34
WEAPON_TYPE_CAMOUFLAGE	= 35
WEAPON_TYPE_FLIPPER_MINE	= 36
WEAPON_TYPE_MEDIC_HUB		= 37
WEAPON_TYPE_AMMO_HUB		= 38
WEAPON_TYPE_BEACON		= 39
WEAPON_TYPE_SONAR		= 40
WEAPON_TYPE_RECON_DRONE	= 41
WEAPON_TYPE_EMP_GRENADE	= 42
WEAPON_TYPE_EMP_MINE		= 43
WEAPON_TYPE_DECOY		= 44
WEAPON_TYPE_THROW_MEDPACK	= 45
WEAPON_TYPE_MINE_BAIT		= 46
WEAPON_TYPE_INFANTRY_SONAR	= 47

NUM_WEAPON_TYPES 	= 49
WEAPON_TYPE_UNKNOWN 	= NUM_WEAPON_TYPES



KIT_TYPE_RECON			= 0
KIT_TYPE_ASSAULT		= 1
KIT_TYPE_ANTI_VEHICLE		= 2
KIT_TYPE_SUPPORT		= 3

NUM_KIT_TYPES			= 4
KIT_TYPE_UNKNOWN		= NUM_KIT_TYPES

ARMY_EU				= 0
ARMY_PAC			= 1

NUM_ARMIES			= 2
ARMY_UNKNOWN			= NUM_ARMIES

WEAPON_CLASS_SOLDIER		= 0
WEAPON_CLASS_VEHICLE		= 1

NUM_WEAPON_CLASSES		= 2
WEAPON_CLASS_UNKNOWN		= NUM_WEAPON_CLASSES

MAPTYPE_BF2142			= 0
MAPTYPE_BP1				= 1
MAPTYPE_FREE			= 2


vehicleTypeMap = {
"parachute" 		:VEHICLE_TYPE_PARACHUTE,
"eu_pod"		:VEHICLE_TYPE_POD,
"eu_pod0"		:VEHICLE_TYPE_POD,
"eu_pod_drop"		:VEHICLE_TYPE_POD,
"us_heavy_soldier" 	:VEHICLE_TYPE_SOLDIER,
"pac_heavy_soldier" 	:VEHICLE_TYPE_SOLDIER,
"as_ag"			:VEHICLE_TYPE_ATTACK_AIR,
"as_secondposition"	:VEHICLE_TYPE_ATTACK_AIR,
"as_apc"			:VEHICLE_TYPE_APC,
"as_apc_second_turret"	:VEHICLE_TYPE_APC,
"as_apc_podcannon1_base"	:VEHICLE_TYPE_APC,
"as_apc_podcannon2_base"	:VEHICLE_TYPE_APC,
"as_apc_podcannon3_base"	:VEHICLE_TYPE_APC,
"as_apc_podcannon4_base"	:VEHICLE_TYPE_APC,
"as_at_spawn"			:VEHICLE_TYPE_TRANSP_AIR,
"as_at_second_pos_spawn"		:VEHICLE_TYPE_TRANSP_AIR,
"as_at_Passenger3_Left_spawn"	:VEHICLE_TYPE_TRANSP_AIR,
"as_at_Passenger3_Right_spawn"	:VEHICLE_TYPE_TRANSP_AIR,
"as_at_third_pos_spawn"		:VEHICLE_TYPE_TRANSP_AIR,
"as_at_passenger2_left_spawn"	:VEHICLE_TYPE_SOLDIER,
"as_at_passenger2_right_spawn"	:VEHICLE_TYPE_SOLDIER,
"as_at"			:VEHICLE_TYPE_TRANSP_AIR,
"as_at_second_pos"	:VEHICLE_TYPE_TRANSP_AIR,
"as_at_Passenger3_Left"	:VEHICLE_TYPE_TRANSP_AIR,
"as_at_Passenger3_Right"	:VEHICLE_TYPE_TRANSP_AIR,
"as_at_third_pos"		:VEHICLE_TYPE_TRANSP_AIR,
"as_at_passenger2_left"	:VEHICLE_TYPE_SOLDIER,
"as_at_passenger2_right"	:VEHICLE_TYPE_SOLDIER,
"as_fav"			:VEHICLE_TYPE_FAAV,
"as_fav_secondposition"	:VEHICLE_TYPE_FAAV,
"as_fav_openposition"	:VEHICLE_TYPE_SOLDIER,
"as_heavy_mech"		:VEHICLE_TYPE_MEC,
"as_heavy_mech_secondaryposition"	:VEHICLE_TYPE_MEC,
"as_tank"				:VEHICLE_TYPE_TANK,
"as_tank__secondpos"		:VEHICLE_TYPE_TANK,
"eu_at_spawn"			:VEHICLE_TYPE_TRANSP_AIR,
"eu_at_l_gunner_spawn"		:VEHICLE_TYPE_TRANSP_AIR,
"eu_at_r_gunner_spawn"		:VEHICLE_TYPE_TRANSP_AIR,
"eu_at_passenger3_left_spawn"	:VEHICLE_TYPE_TRANSP_AIR,
"eu_at_passenger3_right_spawn"	:VEHICLE_TYPE_TRANSP_AIR,
"eu_at_passenger2_left_spawn"	:VEHICLE_TYPE_SOLDIER,
"eu_at_passenger2_right_spawn"	:VEHICLE_TYPE_SOLDIER,
"eu_at"				:VEHICLE_TYPE_TRANSP_AIR,
"eu_at_l_gunner"		:VEHICLE_TYPE_TRANSP_AIR,
"eu_at_r_gunner"		:VEHICLE_TYPE_TRANSP_AIR,
"eu_at_passenger3_left"	:VEHICLE_TYPE_TRANSP_AIR,
"eu_at_passenger3_right"	:VEHICLE_TYPE_TRANSP_AIR,
"eu_at_passenger2_left"	:VEHICLE_TYPE_SOLDIER,
"eu_at_passenger2_right"	:VEHICLE_TYPE_SOLDIER,
"eu_apc"			:VEHICLE_TYPE_APC,
"eu_apc_second_turret"	:VEHICLE_TYPE_APC,
"eu_apc_podcannon1"	:VEHICLE_TYPE_APC,
"eu_apc_podcannon2"	:VEHICLE_TYPE_APC,
"eu_apc_podcannon3"	:VEHICLE_TYPE_APC,
"eu_apc_podcannon4"	:VEHICLE_TYPE_APC,
"eu_fav"			:VEHICLE_TYPE_FAAV,
"eu_fav_secondposition"	:VEHICLE_TYPE_FAAV,
"eu_fav_openposition"		:VEHICLE_TYPE_SOLDIER,
"eu_tank"			:VEHICLE_TYPE_TANK,
"eu_tank_cupolabase"		:VEHICLE_TYPE_TANK,
"us_ag"				:VEHICLE_TYPE_ATTACK_AIR,
"us_ag_tad"			:VEHICLE_TYPE_ATTACK_AIR,
"us_heavy_mech"			:VEHICLE_TYPE_MEC,
"us_heavy_mech_secondaryposition"		:VEHICLE_TYPE_MEC,
"eu_groundcanone3_hub"			:VEHICLE_TYPE_TITAN_GDEF,
"eu_groundcanone3_01_hub"		:VEHICLE_TYPE_TITAN_GDEF,
"eu_groundcanone3_02_hub"		:VEHICLE_TYPE_TITAN_GDEF,
"eu_groundcanone3_03_hub"		:VEHICLE_TYPE_TITAN_GDEF,
"eu_aacannon_01_hub"			:VEHICLE_TYPE_TITAN_AA,
"eu_aacannon_hub"			:VEHICLE_TYPE_TITAN_AA,
"as_aacannon_00_hub"			:VEHICLE_TYPE_TITAN_AA,
"as_aacannon_01_hub"			:VEHICLE_TYPE_TITAN_AA,
"as_groundcanone3_00_hub"		:VEHICLE_TYPE_TITAN_GDEF,
"as_groundcanone3_01_hub"		:VEHICLE_TYPE_TITAN_GDEF,
"as_groundcanone3_02_hub"		:VEHICLE_TYPE_TITAN_GDEF,
"as_groundcanone3_03_hub"		:VEHICLE_TYPE_TITAN_GDEF,
"static_aa"				:VEHICLE_TYPE_GDEF,
"static_av"				:VEHICLE_TYPE_GDEF,
"eu_apc_alps"				:VEHICLE_TYPE_APC,
"eu_apc_podcannon1_alps"		:VEHICLE_TYPE_APC,
"eu_apc_podcannon2_alps"		:VEHICLE_TYPE_APC,
"eu_apc_podcannon3_alps"		:VEHICLE_TYPE_APC,
"eu_apc_podcannon4_alps"		:VEHICLE_TYPE_APC,
"as_apc_pods_alps"			:VEHICLE_TYPE_APC,
"as_apc_podcannon1_base_pods_alps"	:VEHICLE_TYPE_APC,
"as_apc_podcannon2_base_pods_alps"	:VEHICLE_TYPE_APC,
"as_apc_podcannon3_base_pods_alps"	:VEHICLE_TYPE_APC,
"as_apc_podcannon4_base_pods_alps"	:VEHICLE_TYPE_APC,
"eu_ifv"				:VEHICLE_TYPE_IFV,
"eu_ifv_grenadelauncherr_base"		:VEHICLE_TYPE_IFV,
"eu_ifv_machinegunr_base"		:VEHICLE_TYPE_IFV,
"eu_ifv_grenadeLauncherl_base"		:VEHICLE_TYPE_IFV,
"eu_ifv_machinegunl_base"		:VEHICLE_TYPE_IFV,
"as_hov_light"				:VEHICLE_TYPE_HOVER_FAV,
"as_hov_light_gunner"			:VEHICLE_TYPE_HOVER_FAV,
}

weaponTypeMap = {
"eu_sni"			:WEAPON_TYPE_EU_SNIPER ,
"as_sni"			:WEAPON_TYPE_PAC_SNIPER ,
"unl_mine_antipersonnel"	:WEAPON_TYPE_CLAYMORE,
"unl_c4"			:WEAPON_TYPE_C4,
"unl_carbine"		:WEAPON_TYPE_CARBINE,
"unl_adv_sni"		:WEAPON_TYPE_ADV_SNIPER,
"eu_ar_rifle"		:WEAPON_TYPE_EU_AR,
"eu_ar_rocket"		:WEAPON_TYPE_EU_AR,
"eu_ar_shotgun"		:WEAPON_TYPE_EU_AR,
"unl_best_buy_rifle"		:WEAPON_TYPE_EU_AR,
"unl_best_buy_rocket"		:WEAPON_TYPE_EU_AR,
"unl_best_buy_shotgun"		:WEAPON_TYPE_EU_AR,
"as_ar_rifle"		:WEAPON_TYPE_PAC_AR,
"as_ar_rocket"		:WEAPON_TYPE_PAC_AR,
"as_ar_shotgun"		:WEAPON_TYPE_PAC_AR,
"unl_hub_medic"		:WEAPON_TYPE_MEDIC_HUB,
"unl_hub_ammo"		:WEAPON_TYPE_AMMO_HUB,
"unl_defibrillator"		:WEAPON_TYPE_SHOCKPADDLES,
"unl_medic_upgrade"	:WEAPON_TYPE_MEDIC_HUB,
"unl_har_rifle"		:WEAPON_TYPE_HEAVY_AR,
"unl_har_rocket"		:WEAPON_TYPE_HEAVY_AR,
"unl_har_shotgun"		:WEAPON_TYPE_HEAVY_AR,
"unl_lar_rifle"		:WEAPON_TYPE_LIGHT_AR,
"unl_lar_rocket"		:WEAPON_TYPE_LIGHT_AR,
"unl_lar_shotgun"		:WEAPON_TYPE_LIGHT_AR,
"eu_av"			:WEAPON_TYPE_EU_AV,
"as_av"			:WEAPON_TYPE_PAC_AV,
"eu_smg"			:WEAPON_TYPE_EU_SMG,
"as_smg"			:WEAPON_TYPE_PAC_SMG,
"unl_repair"		:WEAPON_TYPE_WRENCH,
"unl_active_camo"		:WEAPON_TYPE_CAMOUFLAGE,
"unl_mine_motion"		:WEAPON_TYPE_MINE,
"unl_mine_emp"		:WEAPON_TYPE_EMP_MINE,
"unl_av_rifle"		:WEAPON_TYPE_HEAVY_AV ,
"eu_mg"			:WEAPON_TYPE_EU_LMG,
"as_mg"			:WEAPON_TYPE_PAC_LMG,
"unl_grenade_emp"	:WEAPON_TYPE_EMP_GRENADE,
"eu_sentrygun_firearm"	:WEAPON_TYPE_SENTRY_GUN,
"unl_deployer_sentrygun"	:WEAPON_TYPE_SENTRY_GUN,
"unl_mg"			:WEAPON_TYPE_UNLOCK_LMG,
"unl_shotgun"		:WEAPON_TYPE_AUTO_SHOTGUN,
"unl_grenade_frag"		:WEAPON_TYPE_GRENADE,
"knife"			:WEAPON_TYPE_KNIFE,
"knife_unlock"		:WEAPON_TYPE_KNIFE,
"eu_handgun"		:WEAPON_TYPE_EU_PISTOL,
"as_handgun"		:WEAPON_TYPE_PAC_PISTOL,
"beacon"			:WEAPON_TYPE_BEACON,
"unl_deployer_drone_recon"	:WEAPON_TYPE_RECON_DRONE,
"unl_drone_recon_fireArm"		:WEAPON_TYPE_RECON_DRONE,
"unl_drone_sentry_detonator"	:WEAPON_TYPE_SENTRY_DRONE,
"unl_drone_sentry_firearm"		:WEAPON_TYPE_SENTRY_DRONE,
"as_heavy_mech_aacannon_firearm"	:WEAPON_TYPE_VEHICLE_AA,
"as_heavy_mech_aa_launcher"	:WEAPON_TYPE_VEHICLE_AA,
"us_heavy_mech_aacannon_barrel"	:WEAPON_TYPE_VEHICLE_AA,
"us_heavy_mech_aa_launcher"	:WEAPON_TYPE_VEHICLE_AA,
"static_aa_missile_firearm"		:WEAPON_TYPE_VEHICLE_AA,
"static_aa_cannon_firearm"		:WEAPON_TYPE_VEHICLE_AA,
"static_aa_missile_firearm"		:WEAPON_TYPE_VEHICLE_AA,
"static_aa_cannon_firearm"		:WEAPON_TYPE_VEHICLE_AA,
"eu_aa"				:WEAPON_TYPE_UNLOCK_SMG,
"as_aa"				:WEAPON_TYPE_UNLOCK_SMG,
"unl_hmg"			:WEAPON_TYPE_UNLOCK_LMG,
"bp1_expl_shotgun"		:WEAPON_TYPE_EXPLOSIVE_ROUNDS_SHOTGUN,
"bp1_decoy"			:WEAPON_TYPE_DECOY,
"bp1_minimed"			:WEAPON_TYPE_THROW_MEDPACK,
"bp1_motion_mine_bait"		:WEAPON_TYPE_MINE_BAIT,
"bp1_sonar"			:WEAPON_TYPE_INFANTRY_SONAR
}

weaponClassMap = {
"eu_sni"			:WEAPON_CLASS_SOLDIER,
"as_sni"			:WEAPON_CLASS_SOLDIER,
"unl_ammo_sniper"	:WEAPON_CLASS_SOLDIER,
"unl_scope_zoom"		:WEAPON_CLASS_SOLDIER,
"unl_scope_stablizer"	:WEAPON_CLASS_SOLDIER,
"unl_mine_antipersonnel"	:WEAPON_CLASS_SOLDIER,
"unl_activecamo"		:WEAPON_CLASS_SOLDIER,
"unl_c4"			:WEAPON_CLASS_SOLDIER,
"unl_recon_hud"		:WEAPON_CLASS_SOLDIER,
"unl_carbine"		:WEAPON_CLASS_SOLDIER,
"unl_adv_sni"		:WEAPON_CLASS_SOLDIER,
"eu_ar_rifle"		:WEAPON_CLASS_SOLDIER,
"eu_ar_rocket"		:WEAPON_CLASS_SOLDIER,
"eu_ar_shotgun"		:WEAPON_CLASS_SOLDIER,
"unl_best_buy_rifle"		:WEAPON_CLASS_SOLDIER,
"unl_best_buy_rocket"		:WEAPON_CLASS_SOLDIER,
"unl_best_buy_shotgun"		:WEAPON_CLASS_SOLDIER,
"as_ar_rifle"		:WEAPON_CLASS_SOLDIER,
"as_ar_rocket"		:WEAPON_CLASS_SOLDIER,
"as_ar_shotgun"		:WEAPON_CLASS_SOLDIER,
"unl_hub_medic"		:WEAPON_CLASS_SOLDIER,
"unl_grenade_smoke"	:WEAPON_CLASS_SOLDIER,
"unl_defibrillator"		:WEAPON_CLASS_SOLDIER,
"unl_medic_upgrade"	:WEAPON_CLASS_SOLDIER,
"unl_assault_hud"		:WEAPON_CLASS_SOLDIER,
"unl_har_rifle"		:WEAPON_CLASS_SOLDIER,
"unl_har_rocket"		:WEAPON_CLASS_SOLDIER,
"unl_har_shotgun"		:WEAPON_CLASS_SOLDIER,
"unl_lar_rifle"		:WEAPON_CLASS_SOLDIER,
"unl_lar_rocket"		:WEAPON_CLASS_SOLDIER,
"unl_lar_shotgun"		:WEAPON_CLASS_SOLDIER,
"eu_av"			:WEAPON_CLASS_SOLDIER,
"as_av"			:WEAPON_CLASS_SOLDIER,
"eu_smg"			:WEAPON_CLASS_SOLDIER,
"as_smg"			:WEAPON_CLASS_SOLDIER,
"unl_ammo_rocket"	:WEAPON_CLASS_SOLDIER,
"unl_mine_flipper"		:WEAPON_CLASS_SOLDIER,
"unl_sight_vehicle"		:WEAPON_CLASS_SOLDIER,
"unl_repair"		:WEAPON_CLASS_SOLDIER,
"unl_defuser"		:WEAPON_CLASS_SOLDIER,
"unl_mine_motion"		:WEAPON_CLASS_SOLDIER,
"unl_mine_emp"		:WEAPON_CLASS_SOLDIER,
"unl_sonar"		:WEAPON_CLASS_SOLDIER,
"unl_repair_upgrade"	:WEAPON_CLASS_SOLDIER,
"unl_engineer_hud"	:WEAPON_CLASS_SOLDIER,
"unl_av_rifle"		:WEAPON_CLASS_SOLDIER,
"eu_mg"			:WEAPON_CLASS_SOLDIER,
"as_mg"			:WEAPON_CLASS_SOLDIER,
"unl_active_camo"		:WEAPON_CLASS_SOLDIER,
"unl_hub_ammo"		:WEAPON_CLASS_SOLDIER,
"unl_grenade_emp"	:WEAPON_CLASS_SOLDIER,
"unl_pulse_meter"		:WEAPON_CLASS_SOLDIER,
"unl_ammo_upgrade"	:WEAPON_CLASS_SOLDIER,
"eu_sentrygun_fireArm"	:WEAPON_CLASS_SOLDIER,
"unl_deployer_sentrygun"	:WEAPON_CLASS_SOLDIER,
"unl_support_hud"		:WEAPON_CLASS_SOLDIER,
"unl_mg"			:WEAPON_CLASS_SOLDIER,
"unl_shotgun"		:WEAPON_CLASS_SOLDIER,
"unl_grenade_frag"		:WEAPON_CLASS_SOLDIER,
"knife"			:WEAPON_CLASS_SOLDIER,
"knife_unlock"		:WEAPON_CLASS_SOLDIER,
"eu_handgun"		:WEAPON_CLASS_SOLDIER,
"as_handgun"		:WEAPON_CLASS_SOLDIER,
"unl_sprint_amount"	:WEAPON_CLASS_SOLDIER,
"unl_sprint_recharge"	:WEAPON_CLASS_SOLDIER,
"unl_ammo_pistol"		:WEAPON_CLASS_SOLDIER,
"unl_ammo_grenade"	:WEAPON_CLASS_SOLDIER,
"beacon"			:WEAPON_CLASS_SOLDIER,
"unl_hmg"			:WEAPON_CLASS_SOLDIER,
"eu_aa"				:WEAPON_CLASS_SOLDIER,
"as_aa"				:WEAPON_CLASS_SOLDIER,
"unl_deployer_drone_recon"		:WEAPON_CLASS_SOLDIER,
"unl_drone_recon_firearm"		:WEAPON_CLASS_SOLDIER,
"unl_drone_sentry_detonator"	:WEAPON_CLASS_SOLDIER,
"unl_drone_sentry_firearm"		:WEAPON_CLASS_SOLDIER,
"as_ag_gunnercannon"		:WEAPON_CLASS_VEHICLE,
"as_ag_pilotweapon"		:WEAPON_CLASS_VEHICLE,
"as_apc_emplauncher"		:WEAPON_CLASS_VEHICLE,
"as_apc_fpgun"			:WEAPON_CLASS_VEHICLE,
"as_apc_2pos_weapon"		:WEAPON_CLASS_VEHICLE,
"as_apc_podcannon1_gun"		:WEAPON_CLASS_VEHICLE,
"as_apc_podcannon2_gun"		:WEAPON_CLASS_VEHICLE,
"as_apc_podcannon3_gun"		:WEAPON_CLASS_VEHICLE,
"as_apc_podcannon4_gun"		:WEAPON_CLASS_VEHICLE,
"as_at_l_gun_barrel"		:WEAPON_CLASS_VEHICLE,
"as_at_r_gun_barrel"		:WEAPON_CLASS_VEHICLE,
"as_fav_gun"			:WEAPON_CLASS_VEHICLE,
"as_heavy_mech_aacannon_firearm"	:WEAPON_CLASS_VEHICLE,
"as_heavy_mech_main_cannon"		:WEAPON_CLASS_VEHICLE,
"as_heavy_mech_dumrockets_firearm"	:WEAPON_CLASS_VEHICLE,
"as_tank_turretfirearm"			:WEAPON_CLASS_VEHICLE,
"as_tank_cupola5"				:WEAPON_CLASS_VEHICLE,
"eu_apc_emplauncher"			:WEAPON_CLASS_VEHICLE,
"eu_apc_pilotgun"				:WEAPON_CLASS_VEHICLE,
"eu_apc_2pos_weapon"			:WEAPON_CLASS_VEHICLE,
"eu_apc_podcannon1_gun"			:WEAPON_CLASS_VEHICLE,
"eu_apc_podcannon2_gun"			:WEAPON_CLASS_VEHICLE,
"eu_apc_podcannon3_gun"			:WEAPON_CLASS_VEHICLE,
"eu_apc_podcannon4_gun"			:WEAPON_CLASS_VEHICLE,
"eu_fav_machinegun_firearm"		:WEAPON_CLASS_VEHICLE,
"eu_tank_barrel"				:WEAPON_CLASS_VEHICLE,
"eu_tank_cupola_firearm"			:WEAPON_CLASS_VEHICLE,
"us_ag_pilotweapon"			:WEAPON_CLASS_VEHICLE,
"ua_ag_cannon_firearm"			:WEAPON_CLASS_VEHICLE,
"us_heavy_mech_aacannon_barrel"		:WEAPON_CLASS_VEHICLE,
"us_heavy_mech_minigun_firearm"		:WEAPON_CLASS_VEHICLE,
"us_heavy_mech_dumrockets_firearm"	:WEAPON_CLASS_VEHICLE,
"as_heavy_mech_aa_launcher"		:WEAPON_CLASS_VEHICLE,
"us_heavy_mech_aa_launcher"		:WEAPON_CLASS_VEHICLE,
"static_aa_missile_firearm"			:WEAPON_CLASS_VEHICLE,
"static_aa_cannon_firearm"			:WEAPON_CLASS_VEHICLE
}


kitTypeMap = {
	"eu_assault"		: KIT_TYPE_ASSAULT,
	"eu_recon"		: KIT_TYPE_RECON,
	"eu_support"		: KIT_TYPE_SUPPORT,
	"eu_anti-vehicle"	: KIT_TYPE_ANTI_VEHICLE,
	"bp1_eu_assault"	: KIT_TYPE_ASSAULT,
	"bp1_eu_recon"		: KIT_TYPE_RECON,
	"bp1_eu_support"	: KIT_TYPE_SUPPORT,
	"bp1_eu_anti-vehicle"	: KIT_TYPE_ANTI_VEHICLE,
	"pac_assault"		: KIT_TYPE_ASSAULT,
	"pac_recon"		: KIT_TYPE_RECON,
	"pac_support"		: KIT_TYPE_SUPPORT,
	"pac_anti-vehicle"	: KIT_TYPE_ANTI_VEHICLE,
	"bp1_pac_assault"	: KIT_TYPE_ASSAULT,
	"bp1_pac_recon"		: KIT_TYPE_RECON,
	"bp1_pac_support"	: KIT_TYPE_SUPPORT,
	"bp1_pac_anti-vehicle"	: KIT_TYPE_ANTI_VEHICLE,
}

armyMap = {
	"eu"			: ARMY_EU,
	"pac"			: ARMY_PAC,
}

mapMap = {
	"suez_canal" 		: 0,
	"verdun" 		: 1,
	"shuhia_taiba" 		: 2,
	"minsk" 		: 3,
	"camp_gibraltar" 	: 4,
	"sidi_power_plant" 	: 5,
	"fall_of_berlin" 	: 6,
	"belgrade" 		: 7,
	"cerbere_landing" 	: 8,
	"tunis_harbor"	 	: 9,
	"bridge_at_remagen"	: 10,
	"liberation_of_leipzig"	: 11,
	"port_bavaria"		: 12,
	"highway_tampa"		: 14,
	"operation_blue_pearl"	: 15,
	"wake_island_2142"	: 16,
	"operation_shingle"	: 17,
	"yellow_knife"		: 18,
	"strike_at_karkand"	: 19,
	"molokai"		: 20,
}

UNKNOWN_MAP = 99

mapArmyMap = {
	"suez_canal" 		: ARMY_PAC,
	"verdun" 		: ARMY_EU,
	"shuhia_taiba" 		: ARMY_PAC,
	"minsk" 		: ARMY_EU,
	"camp_gibraltar" 	: ARMY_PAC,
	"sidi_power_plant" 	: ARMY_PAC,
	"fall_of_berlin" 	: ARMY_EU,
	"belgrade" 		: ARMY_EU,
	"cerbere_landing" 	: ARMY_EU,
	"tunis_harbor"	 	: ARMY_PAC,
	"highway_tampa"	 	: ARMY_EU,
	"wake_island_2142"	: ARMY_EU,
	"operation_shingle"	: ARMY_EU,
	"operation_blue_pearl"	: ARMY_PAC,
	"yellow_knife"		: ARMY_PAC,
	"strike_at_karkand"	: ARMY_PAC,
	"molokai"		: ARMY_EU,
}

UNKNOWN_MAPARMY = 99

gameModeMap = {
	"gpm_cq"		: 0,
	"gpm_ti"		: 1,	
	"gpm_sl"		: 2,
	"gpm_coop"		: 3,
	"gpm_nv"		: 0,
	"gpm_ca"		: 0,
}
UNKNOWN_GAMEMODE = 99

MAP_UNKNOWN			= 99

mapTypeMap = {
	"suez_canal" 		: MAPTYPE_BF2142,
	"verdun" 		: MAPTYPE_BF2142,
	"shuhia_taiba" 		: MAPTYPE_BF2142,
	"minsk" 		: MAPTYPE_BF2142,
	"camp_gibraltar" 	: MAPTYPE_BF2142,
	"sidi_power_plant" 	: MAPTYPE_BF2142,
	"fall_of_berlin" 	: MAPTYPE_BF2142,
	"belgrade" 		: MAPTYPE_BF2142,
	"cerbere_landing" 	: MAPTYPE_BF2142,
	"tunis_harbor"	 	: MAPTYPE_BF2142,
	"bridge_at_remagen"	: MAPTYPE_BP1,
	"liberation_of_leipzig"	: MAPTYPE_BP1,
	"port_bavaria"		: MAPTYPE_BP1,
	"highway_tampa"		: MAPTYPE_FREE,
	"wake_island_2142"	: MAPTYPE_FREE,
	"operation_shingle"	: MAPTYPE_FREE,
	"operation_blue_pearl"	: MAPTYPE_FREE,
	"yellow_knife"		: MAPTYPE_FREE,
	"strike_at_karkand"	: MAPTYPE_FREE,
	"molokai"		: MAPTYPE_FREE,
}

UNKNOWN_MAPTYPE = 99


def getVehicleType(templateName):
	try:
		vehicleType = vehicleTypeMap[string.lower(templateName)]
	except KeyError:
		return VEHICLE_TYPE_UNKNOWN
	
	return vehicleType

	
def getWeaponType(templateName):
	try:
		weaponType = weaponTypeMap[string.lower(templateName)]
	except KeyError:
		return WEAPON_TYPE_UNKNOWN
	
	return weaponType


def getWeaponClass(templateName):
	try:
		weaponClass = weaponClassMap[string.lower(templateName)]
	except KeyError:
		return WEAPON_CLASS_UNKNOWN

	return weaponClass

	
def getKitType(templateName):	
	try:
		kitType = kitTypeMap[string.lower(templateName)]
	except KeyError:
		return KIT_TYPE_UNKNOWN
	
	return kitType	

	
	
def getArmy(templateName):
	try:
		army = armyMap[string.lower(templateName)]
	except KeyError:
		return ARMY_UNKNOWN
	
	return army



def getMapId(mapName):
	try:
		mapId = mapMap[string.lower(mapName)]
	except KeyError:
		return UNKNOWN_MAP
	
	return mapId


def getMapArmy(mapName):
	try:
		mapArmyId = mapArmyMap[string.lower(mapName)]
	except KeyError:
		return UNKNOWN_MAPARMY
	
	return mapArmyId


def getGameModeId(gameMode):
	try:
		gameModeId = gameModeMap[string.lower(gameMode)]
	except KeyError:
		return UNKNOWN_GAMEMODE
	
	return gameModeId


def getMapType(mapName):
	try:
		mapType = mapTypeMap[string.lower(mapName)]
	except KeyError:
		return UNKNOWN_MAPTYPE

	return mapType


if g_debug: print "Stat constants loaded"
