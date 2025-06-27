<?php

namespace Database\Seeders;

use App\Models\TransporteBlancoZona;
use Illuminate\Database\Seeder;

class TransporteBlancoZonaSeeder extends Seeder
{
    private $zonas = [
        ['nombre' => 'AZUA', 'latitud' => '18.4517818', 'longitud' => '-70.7715999', 'transporte_blanco_categoria_id' => 5],
        ['nombre' => 'BANI', 'latitud' => '18.2811898', 'longitud' => '-70.3513225', 'transporte_blanco_categoria_id' => 2],
        ['nombre' => 'BARAHONA', 'latitud' => '18.2101913', 'longitud' => '-71.1238307', 'transporte_blanco_categoria_id' => 5],
        ['nombre' => 'BAVARO Y SUS ZONAS', 'latitud' => '18.68375888', 'longitud' => '-68.4815516', 'transporte_blanco_categoria_id' => 7],
        ['nombre' => 'BAYAGUANA', 'latitud' => '18.8153447', 'longitud' => '-69.7200719', 'transporte_blanco_categoria_id' => 6],
        ['nombre' => 'BOCA CHICA', 'latitud' => '18.4555814', 'longitud' => '-69.6211295', 'transporte_blanco_categoria_id' => 3],
        ['nombre' => 'BONAO', 'latitud' => '18.9297688', 'longitud' => '-70.4257562', 'transporte_blanco_categoria_id' => 1],
        ['nombre' => 'CABARETE', 'latitud' => '19.7613096', 'longitud' => '-70.4447976', 'transporte_blanco_categoria_id' => 4],
        ['nombre' => 'CABRAL', 'latitud' => '18.2470713', 'longitud' => '-71.2221014', 'transporte_blanco_categoria_id' => 5],
        ['nombre' => 'CABRERA', 'latitud' => '19.62549', 'longitud' => '-69.9210399', 'transporte_blanco_categoria_id' => 4],
        ['nombre' => 'CONSTANZA', 'latitud' => '18.9128696', 'longitud' => '-70.7778128', 'transporte_blanco_categoria_id' => 4],
        ['nombre' => 'COTUI', 'latitud' => '19.0498218', 'longitud' => '-70.169141', 'transporte_blanco_categoria_id' => 4],
        ['nombre' => 'DAJABON', 'latitud' => '19.5488701', 'longitud' => '-71.7138791,', 'transporte_blanco_categoria_id' => 7],
        ['nombre' => 'DUVERGE', 'latitud' => '18.3778279', 'longitud' => '-71.5300689', 'transporte_blanco_categoria_id' => 7],
        ['nombre' => 'El SEYBO', 'latitud' => '18.7642673', 'longitud' => '-69.0513254', 'transporte_blanco_categoria_id' => 6],
        ['nombre' => 'ENRIQUILLO', 'latitud' => '18.5662785', 'longitud' => '-69.2994119', 'transporte_blanco_categoria_id' => 7],
        ['nombre' => 'ESPERANZA', 'latitud' => '19.5887583', 'longitud' => '-71.0234931', 'transporte_blanco_categoria_id' => 4],
        ['nombre' => 'FANTINO', 'latitud' => '19.1224326', 'longitud' => '-70.3034663', 'transporte_blanco_categoria_id' => 4],
        ['nombre' => 'GASPAR HERNANDEZ', 'latitud' => '19.6266202', 'longitud' => '-70.2987246', 'transporte_blanco_categoria_id' => 4],
        ['nombre' => 'GUERRA', 'latitud' => '18.557781', 'longitud' => '-69.7248445', 'transporte_blanco_categoria_id' => 6],
        ['nombre' => 'HAINA', 'latitud' => '18.413589', 'longitud' => '-70.0531615', 'transporte_blanco_categoria_id' => 2],
        ['nombre' => 'HATO MAYOR', 'latitud' => '18.7631788', 'longitud' => '-69.2722269', 'transporte_blanco_categoria_id' => 6],
        ['nombre' => 'HIGUEY', 'latitud' => '18.6126709', 'longitud' => '-68.7409618', 'transporte_blanco_categoria_id' => 6],
        ['nombre' => 'JARABACOA', 'latitud' => '19.124005', 'longitud' => '-70.6349248', 'transporte_blanco_categoria_id' => 4],
        ['nombre' => 'JIMANI', 'latitud' => '18.4887025', 'longitud' => '-71.8633613', 'transporte_blanco_categoria_id' => 7],
        ['nombre' => 'LA CALETA', 'latitud' => '18.445126', 'longitud' => '-69.7131991', 'transporte_blanco_categoria_id' => 3],
        ['nombre' => 'LA DESCUBIERTA', 'latitud' => '18.5710022', 'longitud' => '-71.7385555', 'transporte_blanco_categoria_id' => 7],
        ['nombre' => 'LA ROMANA', 'latitud' => '18.4327866', 'longitud' => '-69.01372', 'transporte_blanco_categoria_id' => 6],
        ['nombre' => 'LA VEGA', 'latitud' => '19.2206861', 'longitud' => '-70.548792', 'transporte_blanco_categoria_id' => 1],
        ['nombre' => 'LAS GALERAS', 'latitud' => '19.280217', 'longitud' => '-69.2106915', 'transporte_blanco_categoria_id' => 7],
        ['nombre' => 'LAS TERRENAS', 'latitud' => '19.3056246', 'longitud' => '-69.5880215', 'transporte_blanco_categoria_id' => 7],
        ['nombre' => 'LOMA DE CABRERA', 'latitud' => '19.4160971', 'longitud' => '-71.6262031', 'transporte_blanco_categoria_id' => 7],
        ['nombre' => 'LA ISABELA', 'latitud' => '19.889855', 'longitud' => '-71.0804379', 'transporte_blanco_categoria_id' => 4],
        ['nombre' => 'LA LOMA', 'latitud' => '19.8975195', 'longitud' => '-70.8775219', 'transporte_blanco_categoria_id' => 5],
        ['nombre' => 'MAIMON', 'latitud' => '19.8129635', 'longitud' => '-70.7932003', 'transporte_blanco_categoria_id' => 4],
        ['nombre' => 'MICHES', 'latitud' => '18.9826478', 'longitud' => '-69.0483749', 'transporte_blanco_categoria_id' => 7],
        ['nombre' => 'MONCION', 'latitud' => '19.4152169', 'longitud' => '-71.164198', 'transporte_blanco_categoria_id' => 4],
        ['nombre' => 'MONTE CRISTI', 'latitud' => '19.8452546', 'longitud' => '-71.6641191', 'transporte_blanco_categoria_id' => 7],
        ['nombre' => 'MONTE PLATA', 'latitud' => '18.845536', 'longitud' => '-69.9690607', 'transporte_blanco_categoria_id' => 6],
        ['nombre' => 'MOCA', 'latitud' => '19.3942763', 'longitud' => '-70.5429725', 'transporte_blanco_categoria_id' => 1],
        ['nombre' => 'NAGUA', 'latitud' => '19.3721046', 'longitud' => '-69.8661805', 'transporte_blanco_categoria_id' => 4],
        ['nombre' => 'NAVARRETE', 'latitud' => '19.5576764', 'longitud' => '-70.9128772', 'transporte_blanco_categoria_id' => 4],
        ['nombre' => 'NEYBA', 'latitud' => '18.4889693', 'longitud' => '-71.4259815', 'transporte_blanco_categoria_id' => 7],
        ['nombre' => 'PERDERNALES', 'latitud' => '17.9063931', 'longitud' => '-71.8483077', 'transporte_blanco_categoria_id' => 7],
        ['nombre' => 'PIMENTEL', 'latitud' => '19.2162264', 'longitud' => '-70.2081902', 'transporte_blanco_categoria_id' => 4],
        ['nombre' => 'PRESA DE TAVERAS', 'latitud' => '19.2857573', 'longitud' => '-70.7102073', 'transporte_blanco_categoria_id' => 4],
        ['nombre' => 'PUERTO PLATA', 'latitud' => '19.7896043', 'longitud' => '-70.7272819', 'transporte_blanco_categoria_id' => 4],
        ['nombre' => 'RIO SAN JUAN', 'latitud' => '19.6407874', 'longitud' => '-70.0926236', 'transporte_blanco_categoria_id' => 4],
        ['nombre' => 'S,GDE. DE BOYA', 'latitud' => '18.9447198', 'longitud' => '-69.8035348', 'transporte_blanco_categoria_id' => 6],
        ['nombre' => 'SABANA DE LA MAR', 'latitud' => '19.0567829', 'longitud' => '-69.4001198', 'transporte_blanco_categoria_id' => 7],
        ['nombre' => 'SALCEDO', 'latitud' => '19.3759312', 'longitud' => '-70.4303456', 'transporte_blanco_categoria_id' => 1],
        ['nombre' => 'SAMANÁ', 'latitud' => '19.2076685', 'longitud' => '-69.3498435', 'transporte_blanco_categoria_id' => 7],
        ['nombre' => 'SAMANÁ - EL LIMÓN', 'latitud' => '19.3165726', 'longitud' => '-69.5298336', 'transporte_blanco_categoria_id' => 7],
        ['nombre' => 'SAN CRISTOBAL', 'latitud' => '18.5214031', 'longitud' => '-70.3499578', 'transporte_blanco_categoria_id' => 2],
        ['nombre' => 'SAN FCO. MACORIS', 'latitud' => '19.2981403', 'longitud' => '-70.2953268', 'transporte_blanco_categoria_id' => 1],
        ['nombre' => 'SAN JOSE DE OCOA', 'latitud' => '18.5432348', 'longitud' => '-70.5168415', 'transporte_blanco_categoria_id' => 5],
        ['nombre' => 'SAN JUAN DE LA MAGUANA', 'latitud' => '18.8104429', 'longitud' => '-71.2509689', 'transporte_blanco_categoria_id' => 5],
        ['nombre' => 'SAN PEDRO DE MACORIS', 'latitud' => '18.469709', 'longitud' => '-69.3804392', 'transporte_blanco_categoria_id' => 3],
        ['nombre' => 'SANCHEZ', 'latitud' => '19.2310331', 'longitud' => '-69.6213592', 'transporte_blanco_categoria_id' => 4],
        ['nombre' => 'SANTIAGO RODRIGUEZ', 'latitud' => '19.3968741', 'longitud' => '-71.4811371', 'transporte_blanco_categoria_id' => 4],
        ['nombre' => 'SOSUA', 'latitud' => '19.7629852', 'longitud' => '-70.5160906', 'transporte_blanco_categoria_id' => 4],
        ['nombre' => 'SAN JOSE DE LAS MATAS', 'latitud' => '19.3407253', 'longitud' => '-70.9583763', 'transporte_blanco_categoria_id' => 4],
        ['nombre' => 'TAMAYO', 'latitud' => '18.3991105', 'longitud' => '-71.2118555', 'transporte_blanco_categoria_id' => 5],
        ['nombre' => 'TAMBORIL', 'latitud' => '19.4918172', 'longitud' => '-70.621691', 'transporte_blanco_categoria_id' => 4],
        ['nombre' => 'VALVERDE MAO', 'latitud' => '19.6378789', 'longitud' => '-71.1270034', 'transporte_blanco_categoria_id' => 4],
        ['nombre' => 'VICENTE NOBLE', 'latitud' => '18.3859768', 'longitud' => '-71.1928444', 'transporte_blanco_categoria_id' => 5],
        ['nombre' => 'VILLA ALTAGRACIA', 'latitud' => '18.6803448', 'longitud' => '-70.3101145', 'transporte_blanco_categoria_id' => 1],
        ['nombre' => 'VILLA GONZALEZ', 'latitud' => '19.5504359', 'longitud' => '-70.8252183', 'transporte_blanco_categoria_id' => 4],
        ['nombre' => 'VILLA VASQUEZ', 'latitud' => '19.7417321', 'longitud' => '-71.4571381', 'transporte_blanco_categoria_id' => 4],
        ['nombre' => 'YAMASA', 'latitud' => '18.772669', 'longitud' => '-70.0302887', 'transporte_blanco_categoria_id' => 6]
    ];


    public function run(): void
    {
        foreach ($this->zonas as $zona){
            TransporteBlancoZona::factory()->create($zona);
        }
    }


}
