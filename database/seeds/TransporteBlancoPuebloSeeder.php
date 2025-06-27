<?php

namespace Database\Seeders;

use App\Models\TransporteBlancoPueblo;
use Illuminate\Database\Seeder;

class TransporteBlancoPuebloSeeder extends Seeder
{
    private $pueblos = [
        [ 'nombre' => 'CENTRO DE AZUA', 'latitud' => '18.4517818', 'longitud' => '-70.7715999', 'transporte_blanco_zona_id' => 1,
            'dias_de_entrega' => ['Monday','Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'OCOA', 'latitud' => '18.5456164', 'longitud' => '-70.51081', 'transporte_blanco_zona_id' => 1,
            'dias_de_entrega' => ['Monday','Tuesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'EL PINAR', 'latitud' => '18.5437594', 'longitud' => '-70.5573962', 'transporte_blanco_zona_id' => 1,
            'dias_de_entrega' => ['Thursday']
        ],
        [ 'nombre' => 'PERALTA', 'latitud' => '18.5811144', 'longitud' => '-70.7697629', 'transporte_blanco_zona_id' => 1,
            'dias_de_entrega' => ['Tuesday']
        ],
        [ 'nombre' => 'PADRE LAS CASAS', 'latitud' => '18.7313', 'longitud' => '-70.9496642', 'transporte_blanco_zona_id' => 1,
            'dias_de_entrega' => ['Wednesday']
        ],
        [ 'nombre' => 'BOHECHIO', 'latitud' => '18.7735171', 'longitud' => '-70.9944087', 'transporte_blanco_zona_id' => 1,
            'dias_de_entrega' => ['Wednesday']
        ],
        [ 'nombre' => 'BOCA CANASTA', 'latitud' => '18.2552866', 'longitud' => '-70.3523609', 'transporte_blanco_zona_id' => 2,
            'dias_de_entrega' => ['Monday','Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'SOMBRERO', 'latitud' => '18.2596638', 'longitud' => '-70.3785793', 'transporte_blanco_zona_id' => 2,
            'dias_de_entrega' => ['Monday','Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'MATANZA', 'latitud' => '18.2438919', 'longitud' => '-70.4282857', 'transporte_blanco_zona_id' => 2,
            'dias_de_entrega' => ['Monday','Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'LOS TUMBAOS', 'latitud' => '18.2375316', 'longitud' => '-70.4575806', 'transporte_blanco_zona_id' => 2,
            'dias_de_entrega' => ['Monday','Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'ARROLLO HONDO', 'latitud' => '18.3326856', 'longitud' => '-70.2804388', 'transporte_blanco_zona_id' => 2,
            'dias_de_entrega' => ['Monday','Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'PALMAR DE OCOA', 'latitud' => '18.2972048', 'longitud' => '-70.5906344', 'transporte_blanco_zona_id' => 2,
            'dias_de_entrega' => ['Monday','Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'CAÑAFISTOL', 'latitud' => '18.2808656', 'longitud' => '-70.3777469', 'transporte_blanco_zona_id' => 2,
            'dias_de_entrega' => ['Monday','Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'VILLA FUNDACIÓN', 'latitud' => '18.2805501', 'longitud' => '-70.5004263', 'transporte_blanco_zona_id' => 2,
            'dias_de_entrega' => ['Monday','Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'EL LLANO', 'latitud' => '18.8526413', 'longitud' => '-71.6358623', 'transporte_blanco_zona_id' => 2,
            'dias_de_entrega' => ['Monday','Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'QUIJÁ QUIETA', 'latitud' => '18.7992169', 'longitud' => '-71.2395102', 'transporte_blanco_zona_id' => 2,
            'dias_de_entrega' => ['Monday','Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'SALINAS', 'latitud' => '18.2714776', 'longitud' => '-71.3220158', 'transporte_blanco_zona_id' => 2,
            'dias_de_entrega' => ['Monday','Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'BARAHONA ', 'latitud' => '18.2101913', 'longitud' => '-71.1238307', 'transporte_blanco_zona_id' => 3,
            'dias_de_entrega' => ['Monday', 'Wednesday', 'Friday']
        ],
        [ 'nombre' => 'FONDO NEGRO', 'latitud' => '18.4369625', 'longitud' => '-71.1193193', 'transporte_blanco_zona_id' => 3,
            'dias_de_entrega' => ['Monday', 'Wednesday', 'Friday']
        ],
        [ 'nombre' => 'CANOA ', 'latitud' => '18.3656519', 'longitud' => '-71.2292052', 'transporte_blanco_zona_id' => 3,
            'dias_de_entrega' => ['Monday', 'Wednesday', 'Friday']
        ],
        [ 'nombre' => 'ALGODÓN', 'latitud' => '18.2871542', 'longitud' => '-71.1685593', 'transporte_blanco_zona_id' => 3,
            'dias_de_entrega' => ['Monday', 'Wednesday', 'Friday']
        ],
        [ 'nombre' => 'QUITA CORAZA', 'latitud' => '18.4704199', 'longitud' => '-71.0757602', 'transporte_blanco_zona_id' => 3,
            'dias_de_entrega' => ['Monday', 'Wednesday', 'Friday']
        ],
        [ 'nombre' => 'EL BATEY', 'latitud' => '18.7666867', 'longitud' => '-71.3269667', 'transporte_blanco_zona_id' => 3,
            'dias_de_entrega' => ['Monday', 'Wednesday', 'Friday']
        ],
        [ 'nombre' => 'PEDERNALES', 'latitud' => '17.9063931', 'longitud' => '-71.8483077', 'transporte_blanco_zona_id' => 3,
            'dias_de_entrega' => ['Monday']
        ],
        [ 'nombre' => 'PARAISO', 'latitud' => '17.98708', 'longitud' => '-71.1788696', 'transporte_blanco_zona_id' => 3,
            'dias_de_entrega' => ['Monday']
        ],
        [ 'nombre' => 'ENRIQUILLO', 'latitud' => '17.8978605', 'longitud' => '-71.2416816', 'transporte_blanco_zona_id' => 3,
            'dias_de_entrega' => ['Monday']
        ],
        [ 'nombre' => 'OVIEDO', 'latitud' => '18.006099', 'longitud' => '-71.3956508', 'transporte_blanco_zona_id' => 3,
            'dias_de_entrega' => ['Monday']
        ],
        [ 'nombre' => 'PALO ALTO', 'latitud' => '18.3008424', 'longitud' => '-71.1720301', 'transporte_blanco_zona_id' => 3,
            'dias_de_entrega' => ['Monday', 'Wednesday', 'Friday']
        ],
        [ 'nombre' => 'CIUDAD', 'latitud' => '18.6837588', 'longitud' => '-68.4815516', 'transporte_blanco_zona_id' => 4,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'LA OTRA BANDA', 'latitud' => '18.6491828', 'longitud' => '-68.6657095', 'transporte_blanco_zona_id' => 4,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'VERÓN', 'latitud' => '18.5934304', 'longitud' => '-68.5019502', 'transporte_blanco_zona_id' => 4,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'PUNTA CANA', 'latitud' => '18.6409711', 'longitud' => '-68.6361545', 'transporte_blanco_zona_id' => 4,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'LAGUNA DE NISIBÓN', 'latitud' => '18.8349063', 'longitud' => '-68.6784554', 'transporte_blanco_zona_id' => 4,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'UVERO ALTO', 'latitud' => '18.8100338', 'longitud' => '-68.6039545', 'transporte_blanco_zona_id' => 4,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'MACAO', 'latitud' => '18.756483', 'longitud' => '-68.547993', 'transporte_blanco_zona_id' => 4,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'ARENA GORDA', 'latitud' => '18.7239426', 'longitud' => '-68.4739962', 'transporte_blanco_zona_id' => 4,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'CABEZA DE TORO', 'latitud' => '18.5942955', 'longitud' => '-71.2412578', 'transporte_blanco_zona_id' => 4,
            'dias_de_entrega' => ['Monday', 'Thursday']
        ],
        [ 'nombre' => 'CAP CANA', 'latitud' => '18.4801237', 'longitud' => '-68.4896973', 'transporte_blanco_zona_id' => 4,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'BAYAGUANA', 'latitud' => '18.8153447', 'longitud' => '-69.7200719', 'transporte_blanco_zona_id' => 39,
            'dias_de_entrega' => ['Tuesday', 'Thursday']
        ],
        [ 'nombre' => 'MONTE PLATA', 'latitud' => '18.810097', 'longitud' => '-69.8012066', 'transporte_blanco_zona_id' => 39,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday']
        ],
        [ 'nombre' => 'PUNTA DE VILLA MELLA', 'latitud' => '18.552176', 'longitud' => '-69.904806', 'transporte_blanco_zona_id' => 39,
            'dias_de_entrega' => ['Tuesday']
        ],
        [ 'nombre' => 'SABANA GRANDE DE BOYA', 'latitud' => '18.9447198', 'longitud' => '-69.8035348', 'transporte_blanco_zona_id' => 39,
            'dias_de_entrega' => ['Tuesday', 'Thursday']
        ],
        [ 'nombre' => 'YAMASA', 'latitud' => '18.772669', 'longitud' => '-70.0302887', 'transporte_blanco_zona_id' => 39,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'PERALVILLO', 'latitud' => '18.8200649', 'longitud' => '-70.0474058', 'transporte_blanco_zona_id' => 39,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'DON JUAN', 'latitud' => '18.8320021', 'longitud' => '-69.9527406', 'transporte_blanco_zona_id' => 39,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'EL CACIQUE', 'latitud' => '18.8069546', 'longitud' => '-69.7901552', 'transporte_blanco_zona_id' => 39,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'CHIRINO', 'latitud' => '18.6735214', 'longitud' => '-69.8105474', 'transporte_blanco_zona_id' => 39,
            'dias_de_entrega' => ['Tuesday', 'Thursday']
        ],
        [ 'nombre' => 'GONZALO', 'latitud' => '18.9498951', 'longitud' => '-69.7608019', 'transporte_blanco_zona_id' => 39,
            'dias_de_entrega' => ['Tuesday', 'Thursday']
        ],
        [ 'nombre' => 'LA VICTORIA', 'latitud' => '18.6269215', 'longitud' => '-69.9684285', 'transporte_blanco_zona_id' => 39,
            'dias_de_entrega' => ['Monday', 'Wednesday', 'Friday']
        ],
        [ 'nombre' => 'GUERRA', 'latitud' => '18.557993', 'longitud' => '-69.7248069', 'transporte_blanco_zona_id' => 39,
            'dias_de_entrega' => ['Monday', 'Wednesday', 'Friday']
        ],
        [ 'nombre' => 'HACIENDA ESTRELLA', 'latitud' => '18.6829384', 'longitud' => '-69.8804284', 'transporte_blanco_zona_id' => 39,
            'dias_de_entrega' => ['Monday', 'Wednesday', 'Friday']
        ],
        [ 'nombre' => 'GUANUMA', 'latitud' => '18.7693799', 'longitud' => '-69.9578062', 'transporte_blanco_zona_id' => 39,
            'dias_de_entrega' => ['Monday', 'Wednesday', 'Friday']
        ],
        [ 'nombre' => 'SAN LUIS', 'latitud' => '18.5459667', 'longitud' => '-69.8123068', 'transporte_blanco_zona_id' => 39,
            'dias_de_entrega' => ['Monday', 'Wednesday', 'Friday']
        ],
        [ 'nombre' => 'SAN ISIDRO', 'latitud' => '18.532849', 'longitud' => '-69.7835899', 'transporte_blanco_zona_id' => 39,
            'dias_de_entrega' => ['Monday', 'Wednesday', 'Friday']
        ],
        [ 'nombre' => 'VILLA MELLA', 'latitud' => '18.5656957', 'longitud' => '-69.9219874', 'transporte_blanco_zona_id' => 39,
            'dias_de_entrega' => ['Monday', 'Wednesday', 'Friday']
        ],
        [ 'nombre' => 'LA LUISA', 'latitud' => '18.5688949', 'longitud' => '-69.9217168', 'transporte_blanco_zona_id' => 39,
            'dias_de_entrega' => ['Monday', 'Wednesday', 'Friday']
        ],
        [ 'nombre' => 'QUISQUEYA', 'latitud' => '18.5545043', 'longitud' => '-69.4236592', 'transporte_blanco_zona_id' => 58,
            'dias_de_entrega' => ['Monday', 'Wednesday', 'Friday']
        ],
        [ 'nombre' => 'LOS LLANOS', 'latitud' => '18.6235942', 'longitud' => '-69.4974947', 'transporte_blanco_zona_id' => 58,
            'dias_de_entrega' => ['Monday', 'Wednesday', 'Friday']
        ],
        [ 'nombre' => 'RAMON SANTANA', 'latitud' => '18.543927', 'longitud' => '-69.1898561', 'transporte_blanco_zona_id' => 58,
            'dias_de_entrega' => ['Monday', 'Wednesday', 'Friday']
        ],
        [ 'nombre' => 'BOCA CHICA', 'latitud' => '18.4555814', 'longitud' => '-69.6211295', 'transporte_blanco_zona_id' => 58,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'SAN PEDRO', 'latitud' => '18.469709', 'longitud' => '-69.3804392', 'transporte_blanco_zona_id' => 58,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'JUAN DOLIO', 'latitud' => '18.4547919', 'longitud' => '-69.4531211', 'transporte_blanco_zona_id' => 58,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'BONAO', 'latitud' => '18.929768', 'longitud' => '-70.4257562', 'transporte_blanco_zona_id' => 7,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'PIEDRA BLANCA', 'latitud' => '18.8446542', 'longitud' => '-70.3217805', 'transporte_blanco_zona_id' => 7,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'MAIMON', 'latitud' => '18.9056152', 'longitud' => '-70.2926091', 'transporte_blanco_zona_id' => 7,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'LOS QUEMADOS', 'latitud' => '18.8901505', 'longitud' => '-70.4592238', 'transporte_blanco_zona_id' => 7,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'SABANA PUERTO', 'latitud' => '19.0670944', 'longitud' => '-70.4979424', 'transporte_blanco_zona_id' => 7,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'LA CUMBRE', 'latitud' => '19.545934', 'longitud' => '-70.6288933', 'transporte_blanco_zona_id' => 7,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'MONTE LLANO', 'latitud' => '18.3364079', 'longitud' => '-70.4769839', 'transporte_blanco_zona_id' => 61,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'SOSUA', 'latitud' => '19.7629852', 'longitud' => '-70.5160906', 'transporte_blanco_zona_id' => 61,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'BATEY', 'latitud' => '19.7197638', 'longitud' => '-70.6240867', 'transporte_blanco_zona_id' => 61,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'LOS CHARAMICOS', 'latitud' => '19.7531483', 'longitud' => '-70.52477', 'transporte_blanco_zona_id' => 61,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'CABARETE', 'latitud' => '19.7613096', 'longitud' => '-70.4447976', 'transporte_blanco_zona_id' => 61,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'ISLABON', 'latitud' => '19.6965713', 'longitud' => '-70.3891053', 'transporte_blanco_zona_id' => 61,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'SABANETA DE YASICA', 'latitud' => '19.6769084', 'longitud' => '-70.3821517', 'transporte_blanco_zona_id' => 61,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'CANO DULCE', 'latitud' => '19.649741', 'longitud' => '-70.3558445', 'transporte_blanco_zona_id' => 61,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'GASPAR HERNANDEZ', 'latitud' => '19.6266202', 'longitud' => '-70.2987246', 'transporte_blanco_zona_id' => 61,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'LA CANTERA', 'latitud' => '19.6157466', 'longitud' => '-70.1353029', 'transporte_blanco_zona_id' => 61,
            'dias_de_entrega' => ['Monday', 'Tuesday','Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'LA YAGUA', 'latitud' => '19.6092191', 'longitud' => '-70.1865721', 'transporte_blanco_zona_id' => 61,
            'dias_de_entrega' => ['Monday', 'Tuesday','Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'PALO AMARILLO', 'latitud' => '19.6666369', 'longitud' => '-70.4173385', 'transporte_blanco_zona_id' => 61,
            'dias_de_entrega' => ['Monday', 'Tuesday','Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'LOS BRAZOS', 'latitud' => '19.653710', 'longitud' => ' -70.443948', 'transporte_blanco_zona_id' => 61,
            'dias_de_entrega' => ['Monday', 'Tuesday','Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'JAMAO AL NORTE', 'latitud' => '19.6347109', 'longitud' => '-70.4496574', 'transporte_blanco_zona_id' => 61,
            'dias_de_entrega' => ['Monday', 'Tuesday','Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'LA CUMBRE', 'latitud' => '19.5454538', 'longitud' => '-70.6258571', 'transporte_blanco_zona_id' => 61,
            'dias_de_entrega' => ['Monday', 'Tuesday','Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'RIO SAN JUAN', 'latitud' => '19.6407882', 'longitud' => '-70.0834394', 'transporte_blanco_zona_id' => 61,
            'dias_de_entrega' => ['Monday', 'Wednesday', 'Friday']
        ],
        [ 'nombre' => 'CABRERA', 'latitud' => '19.62549', 'longitud' => '-69.9210399', 'transporte_blanco_zona_id' => 61,
            'dias_de_entrega' => ['Monday', 'Tuesday','Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'CONSTANZA', 'latitud' => '18.9128531', 'longitud' => '-70.749144', 'transporte_blanco_zona_id' => 24,
            'dias_de_entrega' => ['Monday', 'Tuesday','Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'JARABACOA', 'latitud' => '19.124005', 'longitud' => '-70.6349248', 'transporte_blanco_zona_id' => 24,
            'dias_de_entrega' => ['Monday', 'Tuesday','Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'COTUI', 'latitud' => '19.0498218', 'longitud' => '-70.169141', 'transporte_blanco_zona_id' => 12,
            'dias_de_entrega' => ['Monday', 'Tuesday','Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'ANGELINA', 'latitud' => '19.126735', 'longitud' => '-70.2785666', 'transporte_blanco_zona_id' => 12,
            'dias_de_entrega' => ['Monday', 'Tuesday','Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'FANTINO', 'latitud' => '19.1224326', 'longitud' => '-70.3034663', 'transporte_blanco_zona_id' => 12,
            'dias_de_entrega' => ['Monday', 'Tuesday','Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'JIMA ARRIBA', 'latitud' => '19.1300234', 'longitud' => '-70.3579906', 'transporte_blanco_zona_id' => 12,
            'dias_de_entrega' => ['Monday', 'Tuesday','Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'JIMA ABAJO', 'latitud' => '19.1338211', 'longitud' => '-70.3786326', 'transporte_blanco_zona_id' => 12,
            'dias_de_entrega' => ['Monday', 'Tuesday','Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'LAS MATAS DE COTUI', 'latitud' => '19.1005356', 'longitud' => '-70.1738834', 'transporte_blanco_zona_id' => 12,
            'dias_de_entrega' => ['Monday', 'Tuesday','Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'PINO LA VEGA', 'latitud' => '19.1475592', 'longitud' => '-70.480857', 'transporte_blanco_zona_id' => 12,
            'dias_de_entrega' => ['Monday', 'Tuesday','Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'LAS GUARANAS', 'latitud' => '19.196879', 'longitud' => '-70.2631052', 'transporte_blanco_zona_id' => 12,
            'dias_de_entrega' => ['Monday', 'Tuesday','Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'LA CUEVA DE CEVICO', 'latitud' => '19.0054688', 'longitud' => '-69.9860132', 'transporte_blanco_zona_id' => 12,
            'dias_de_entrega' => ['Monday', 'Thursday']
        ],
        [ 'nombre' => 'DAJABON', 'latitud' => '19.5488701', 'longitud' => '-71.7138791', 'transporte_blanco_zona_id' => 13,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'MONTE CRISTI', 'latitud' => '19.8452546', 'longitud' => '-71.6641191', 'transporte_blanco_zona_id' => 13,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'MANZANILLO', 'latitud' => '19.6988591', 'longitud' => '-71.7522884', 'transporte_blanco_zona_id' => 13,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'PALO VERDE', 'latitud' => '19.7555531', 'longitud' => '-71.5582365', 'transporte_blanco_zona_id' => 13,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'VILLA VASQUEZ', 'latitud' => '19.7417313', 'longitud' => '-71.4663223', 'transporte_blanco_zona_id' => 13,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'DUVERGE', 'latitud' => '18.3778279', 'longitud' => '-71.5300689', 'transporte_blanco_zona_id' => 43,
            'dias_de_entrega' => ['Tuesday', 'Thursday']
        ],
        [ 'nombre' => 'DESCUBIERTA', 'latitud' => '18.5710022', 'longitud' => '-71.7385555', 'transporte_blanco_zona_id' => 43,
            'dias_de_entrega' => ['Tuesday', 'Thursday']
        ],
        [ 'nombre' => 'LOS RIOS', 'latitud' => '18.5139767', 'longitud' => '-71.72336', 'transporte_blanco_zona_id' => 43,
            'dias_de_entrega' => ['Tuesday', 'Thursday']
        ],
        [ 'nombre' => 'CACHON', 'latitud' => '18.2471581', 'longitud' => '-71.1903548', 'transporte_blanco_zona_id' => 43,
            'dias_de_entrega' => ['Tuesday', 'Thursday']
        ],
        [ 'nombre' => 'POSTRER RIO', 'latitud' => '18.54416', 'longitud' => '-71.6565051', 'transporte_blanco_zona_id' => 43,
            'dias_de_entrega' => ['Tuesday', 'Thursday']
        ],
        [ 'nombre' => 'EL SALADO', 'latitud' => '18.4840658', 'longitud' => '-71.3291056', 'transporte_blanco_zona_id' => 43,
            'dias_de_entrega' => ['Tuesday', 'Thursday']
        ],
        [ 'nombre' => 'MONSERRAT', 'latitud' => '18.3864788', 'longitud' => '-71.2553717', 'transporte_blanco_zona_id' => 43,
            'dias_de_entrega' => ['Tuesday', 'Thursday']
        ],
        [ 'nombre' => 'UVILLAS', 'latitud' => '18.3624731', 'longitud' => '-71.2149239', 'transporte_blanco_zona_id' => 43,
            'dias_de_entrega' => ['Tuesday', 'Thursday']
        ],
        [ 'nombre' => 'EL LIMON', 'latitud' => '18.4316443', 'longitud' => '-71.7865993', 'transporte_blanco_zona_id' => 43,
            'dias_de_entrega' => ['Tuesday', 'Thursday']
        ],
        [ 'nombre' => 'VICENTE NOBLE', 'latitud' => '18.3859768', 'longitud' => '-71.1928444', 'transporte_blanco_zona_id' => 43,
            'dias_de_entrega' => ['Tuesday', 'Thursday']
        ],
        [ 'nombre' => 'JIMANI', 'latitud' => '18.4887025', 'longitud' => '-71.8633613', 'transporte_blanco_zona_id' => 43,
            'dias_de_entrega' => ['Tuesday', 'Thursday']
        ],
        [ 'nombre' => 'TAMAYO', 'latitud' => '18.3991098', 'longitud' => '-71.2210397', 'transporte_blanco_zona_id' => 43,
            'dias_de_entrega' => ['Tuesday', 'Thursday']
        ],
        [ 'nombre' => 'GALVAN', 'latitud' => '18.5053204', 'longitud' => '-71.3535405', 'transporte_blanco_zona_id' => 43,
            'dias_de_entrega' => ['Tuesday', 'Thursday']
        ],
        [ 'nombre' => 'NEYBA', 'latitud' => '18.4889693', 'longitud' => '-71.4259815', 'transporte_blanco_zona_id' => 43,
            'dias_de_entrega' => ['Tuesday', 'Thursday']
        ],
        [ 'nombre' => 'SANTANA', 'latitud' => '18.4219825', 'longitud' => '-71.1967171', 'transporte_blanco_zona_id' => 43,
            'dias_de_entrega' => ['Tuesday', 'Thursday']
        ],
        [ 'nombre' => 'BAYAHONDA', 'latitud' => '18.4219825', 'longitud' => '-71.1967171', 'transporte_blanco_zona_id' => 43,
            'dias_de_entrega' => ['Tuesday', 'Thursday']
        ],
        [ 'nombre' => 'BATEY 4', 'latitud' => '18.3916513', 'longitud' => '-71.2563908', 'transporte_blanco_zona_id' => 43,
            'dias_de_entrega' => ['Tuesday', 'Thursday']
        ],
        [ 'nombre' => 'BATEY 6', 'latitud' => '18.3547231', 'longitud' => '-71.2331119', 'transporte_blanco_zona_id' => 43,
            'dias_de_entrega' => ['Tuesday', 'Thursday']
        ],
        [ 'nombre' => 'DESCUBIERTA', 'latitud' => '18.5710114', 'longitud' => '-71.7528895', 'transporte_blanco_zona_id' => 43,
            'dias_de_entrega' => ['Tuesday', 'Thursday']
        ],
        [ 'nombre' => 'CLAVELLINAS', 'latitud' => '18.5108097', 'longitud' => '-71.5617495', 'transporte_blanco_zona_id' => 43,
            'dias_de_entrega' => ['Tuesday', 'Thursday']
        ],
        [ 'nombre' => 'SAN BARTOLOME', 'latitud' => '18.4816346', 'longitud' => '-71.4204408', 'transporte_blanco_zona_id' => 43,
            'dias_de_entrega' => ['Tuesday', 'Thursday']
        ],
        [ 'nombre' => 'EL SEYBO', 'latitud' => '18.7642673', 'longitud' => '-69.0513254', 'transporte_blanco_zona_id' => 22,
            'dias_de_entrega' => ['Wednesday', 'Friday']
        ],
        [ 'nombre' => 'SABANA DE LA MAR', 'latitud' => '19.0567829', 'longitud' => '-69.4001198', 'transporte_blanco_zona_id' => 22,
            'dias_de_entrega' => ['Monday']
        ],
        [ 'nombre' => 'MICHES', 'latitud' => '18.9826478', 'longitud' => '-69.0483749', 'transporte_blanco_zona_id' => 22,
            'dias_de_entrega' => ['Monday']
        ],
        [ 'nombre' => 'HATO MAYOR', 'latitud' => '18.8216411', 'longitud' => '-69.5168272', 'transporte_blanco_zona_id' => 22,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'INGENIO CONSUELO', 'latitud' => '18.5555826', 'longitud' => '-69.307251', 'transporte_blanco_zona_id' => 22,
            'dias_de_entrega' => ['Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'NAVARRETE', 'latitud' => '19.5576601', 'longitud' => '-70.8842084', 'transporte_blanco_zona_id' => 23,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'ESPERANZA', 'latitud' => '19.5893576', 'longitud' => '-71.0286459', 'transporte_blanco_zona_id' => 23,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'LAGUNA SALADA', 'latitud' => '19.6490336', 'longitud' => '-71.1031509', 'transporte_blanco_zona_id' => 23,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'EL MAIZAL', 'latitud' => '19.6631586', 'longitud' => '-71.1547087', 'transporte_blanco_zona_id' => 23,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'HATILLO PALMA', 'latitud' => '19.6617794', 'longitud' => '-71.2002362', 'transporte_blanco_zona_id' => 23,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'VILLA ELISA', 'latitud' => '19.6858486', 'longitud' => '-71.2803269', 'transporte_blanco_zona_id' => 23,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'HATO AL MEDIO', 'latitud' => '19.6943747', 'longitud' => '-71.3209891', 'transporte_blanco_zona_id' => 23,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'CRUCE DE GUAYACANES', 'latitud' => '19.6470222', 'longitud' => '-71.0705996', 'transporte_blanco_zona_id' => 23,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'YUMA', 'latitud' => '18.377425', 'longitud' => '-68.6259271', 'transporte_blanco_zona_id' => 54,
            'dias_de_entrega' => ['Tuesday', 'Thursday']
        ],
        [ 'nombre' => 'CENTRO DE SAN CRISTOBAL', 'latitud' => '18.5214031', 'longitud' => '-70.3499578', 'transporte_blanco_zona_id' => 54,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'CAMBITA', 'latitud' => '18.4559896', 'longitud' => '-70.2010166', 'transporte_blanco_zona_id' => 54,
            'dias_de_entrega' => ['Tuesday', 'Thursday']
        ],
        [ 'nombre' => 'NIZAO', 'latitud' => '18.246372', 'longitud' => '-70.2172621', 'transporte_blanco_zona_id' => 54,
            'dias_de_entrega' => ['Monday', 'Wednesday', 'Friday']
        ],
        [ 'nombre' => 'PALENQUE', 'latitud' => '18.2614477', 'longitud' => '-70.1572646', 'transporte_blanco_zona_id' => 54,
            'dias_de_entrega' => ['Monday', 'Wednesday', 'Friday']
        ],
        [ 'nombre' => 'CENTRO DE HIGUEY', 'latitud' => '18.6126709', 'longitud' => '-68.7409618', 'transporte_blanco_zona_id' => 23,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'VILLA CERRO', 'latitud' => '18.6333571', 'longitud' => '-68.7244686', 'transporte_blanco_zona_id' => 23,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'LA MALENA', 'latitud' => '18.5916455', 'longitud' => '-68.7136288', 'transporte_blanco_zona_id' => 23,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'CIUDAD', 'latitud' => '18.4328089', 'longitud' => '-69.1284117', 'transporte_blanco_zona_id' => 28,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'CASA DE CAMPO', 'latitud' => '18.4231337', 'longitud' => '-68.9320926', 'transporte_blanco_zona_id' => 28,
            'dias_de_entrega' => ['Thursday']
        ],
        [ 'nombre' => 'ALTOS DE CHAVON', 'latitud' => '18.4291356', 'longitud' => '-69.066845', 'transporte_blanco_zona_id' => 28,
            'dias_de_entrega' => ['Thursday']
        ],
        [ 'nombre' => 'BAYAHIBE', 'latitud' => '18.32202', 'longitud' => '-68.8512669', 'transporte_blanco_zona_id' => 28,
            'dias_de_entrega' => ['Tuesday', 'Friday']
        ],
        [ 'nombre' => 'VISTA CATALINA', 'latitud' => '18.4080799', 'longitud' => '-68.9872027', 'transporte_blanco_zona_id' => 28,
            'dias_de_entrega' => ['Monday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'VILLA HERMOSA', 'latitud' => '18.4321384', 'longitud' => '-69.0466693', 'transporte_blanco_zona_id' => 28,
            'dias_de_entrega' => ['Tuesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'GUAYMATE', 'latitud' => '18.5897399', 'longitud' => '-68.9816523', 'transporte_blanco_zona_id' => 28,
            'dias_de_entrega' => ['Wednesday']
        ],
        [ 'nombre' => 'VILLA RIVAS', 'latitud' => '19.1523289', 'longitud' => '-69.9422302', 'transporte_blanco_zona_id' => 31,
            'dias_de_entrega' => ['Monday', 'Wednesday', 'Friday']
        ],
        [ 'nombre' => 'SANCHEZ', 'latitud' => '19.2310331', 'longitud' => '-69.6213592', 'transporte_blanco_zona_id' => 31,
            'dias_de_entrega' => ['Monday', 'Wednesday', 'Friday']
        ],
        [ 'nombre' => 'LAS TERRENAS', 'latitud' => '19.3056246', 'longitud' => '-69.5880215', 'transporte_blanco_zona_id' => 31,
            'dias_de_entrega' => ['Monday', 'Wednesday', 'Friday']
        ],
        [ 'nombre' => 'LAS GALERAS', 'latitud' => '19.280217', 'longitud' => '-69.2106915', 'transporte_blanco_zona_id' => 31,
            'dias_de_entrega' => ['Friday']
        ],
        [ 'nombre' => 'LIMON DEL YUNA', 'latitud' => '19.1272436', 'longitud' => '-69.8007688', 'transporte_blanco_zona_id' => 31,
            'dias_de_entrega' => ['Wednesday']
        ],
        [ 'nombre' => 'EL GUARAGUAO', 'latitud' => '19.1138051', 'longitud' => '-69.84556', 'transporte_blanco_zona_id' => 31,
            'dias_de_entrega' => ['Wednesday']
        ],
        [ 'nombre' => 'LA COLE', 'latitud' => '', 'longitud' => '', 'transporte_blanco_zona_id' => 31,
            'dias_de_entrega' => ['Wednesday']
        ],
        [ 'nombre' => 'EL VALLE', 'latitud' => '18.9442526', 'longitud' => '-69.4270494', 'transporte_blanco_zona_id' => 31,
            'dias_de_entrega' => ['Wednesday']
        ],
        [ 'nombre' => 'MONCION', 'latitud' => '19.4152169', 'longitud' => '-71.164198', 'transporte_blanco_zona_id' => 60,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'SANTIAGO RODRIGUEZ', 'latitud' => '19.3966826', 'longitud' => '-71.6281568', 'transporte_blanco_zona_id' => 60,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'RESTAURACION', 'latitud' => '19.3146364', 'longitud' => '-71.6980435', 'transporte_blanco_zona_id' => 60,
            'dias_de_entrega' => ['Monday']
        ],
        [ 'nombre' => 'VILLA LOS ALMACIGOS', 'latitud' => '19.4065451', 'longitud' => '-71.451881', 'transporte_blanco_zona_id' => 60,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'EL PINO', 'latitud' => '19.4360191', 'longitud' => '-71.4876938', 'transporte_blanco_zona_id' => 60,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'MANUEL BUENO', 'latitud' => '19.3882838', 'longitud' => '-71.5025278', 'transporte_blanco_zona_id' => 60,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'LOMA DE CABRERA', 'latitud' => '19.4192613', 'longitud' => '-71.617356', 'transporte_blanco_zona_id' => 60,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'CAPOTILLO', 'latitud' => '19.4107043', 'longitud' => '-71.6680724', 'transporte_blanco_zona_id' => 60,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'ALTO DE CANA', 'latitud' => '19.4393313', 'longitud' => '-71.3501529', 'transporte_blanco_zona_id' => 60,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'SAN JOSE', 'latitud' => '', 'longitud' => '', 'transporte_blanco_zona_id' => 60,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'LA GORRA', 'latitud' => '', 'longitud' => '', 'transporte_blanco_zona_id' => 60,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'PASTOR', 'latitud' => '19.4554763', 'longitud' => '-70.7570372', 'transporte_blanco_zona_id' => 60,
            'dias_de_entrega' => ['Monday']
        ],
        [ 'nombre' => 'LA BREÑA', 'latitud' => '', 'longitud' => '', 'transporte_blanco_zona_id' => 60,
            'dias_de_entrega' => ['Monday']
        ],
        [ 'nombre' => 'MAIMON', 'latitud' => '19.8129635', 'longitud' => '-70.7932003', 'transporte_blanco_zona_id' => 47,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'SAN MARCOS', 'latitud' => '19.7771941', 'longitud' => '-70.7456607', 'transporte_blanco_zona_id' => 47,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'COSTAMBAR', 'latitud' => '19.8114007', 'longitud' => '-70.7182159', 'transporte_blanco_zona_id' => 47,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'COFRESI', 'latitud' => '19.8238453', 'longitud' => '-70.7349265', 'transporte_blanco_zona_id' => 47,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'LOMA DE LA BESTIA', 'latitud' => '19.8144639', 'longitud' => '-70.7730775', 'transporte_blanco_zona_id' => 47,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'PUERTO PLATA', 'latitud' => '19.7895922', 'longitud' => '-70.7640223', 'transporte_blanco_zona_id' => 47,
            'dias_de_entrega' => ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes']
        ],
        [ 'nombre' => 'SOSUA', 'latitud' => '19.7629852', 'longitud' => '-70.5160906', 'transporte_blanco_zona_id' => 47,
            'dias_de_entrega' => ['Monday']
        ],
        [ 'nombre' => 'SUPERMERCADO PLAYERO', 'latitud' => '19.7626291', 'longitud' => '-70.5176571', 'transporte_blanco_zona_id' => 47,
            'dias_de_entrega' => ['Monday']
        ],
        [ 'nombre' => 'SAN FRANCISCO DE MACORIS', 'latitud' => '19.2981433', 'longitud' => '-70.2769578', 'transporte_blanco_zona_id' => 47,
            'dias_de_entrega' => ['Thursday']
        ],
        [ 'nombre' => 'EL GRAN PORVENIR', 'latitud' => '19.3035025', 'longitud' => '-70.263823', 'transporte_blanco_zona_id' => 47,
            'dias_de_entrega' => ['Thursday']
        ],
        [ 'nombre' => 'PALMARE', 'latitud' => '19.287498', 'longitud' => '-70.2723033', 'transporte_blanco_zona_id' => 47,
            'dias_de_entrega' => ['Thursday']
        ],
        [ 'nombre' => 'SM YOMA', 'latitud' => '19.1757764', 'longitud' => '-70.2827796', 'transporte_blanco_zona_id' => 47,
            'dias_de_entrega' => ['Thursday']
        ],
        [ 'nombre' => 'PIMENTEL', 'latitud' => '19.1856021', 'longitud' => '-70.1284365', 'transporte_blanco_zona_id' => 41,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'CASTILLO', 'latitud' => '19.2081272', 'longitud' => '-70.0347948', 'transporte_blanco_zona_id' => 41,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'NAGUA', 'latitud' => '19.3721046', 'longitud' => '-69.8661805', 'transporte_blanco_zona_id' => 41,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'EL POZO DE NAGUA', 'latitud' => '19.266965', 'longitud' => '-69.8902314', 'transporte_blanco_zona_id' => 41,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'PAYITA', 'latitud' => '19.5074936', 'longitud' => '-69.9571825', 'transporte_blanco_zona_id' => 41,
            'dias_de_entrega' => ['Friday']
        ],
        [ 'nombre' => 'LAS GORDAS', 'latitud' => '19.4585623', 'longitud' => '-70.0251874', 'transporte_blanco_zona_id' => 41,
            'dias_de_entrega' => ['Friday']
        ],
        [ 'nombre' => 'CAÑO AZUL', 'latitud' => '19.4245495', 'longitud' => '-70.0075465', 'transporte_blanco_zona_id' => 41,
            'dias_de_entrega' => ['Friday']
        ],
        [ 'nombre' => 'LA VEGA', 'latitud' => '19.2206861', 'longitud' => '-70.548792', 'transporte_blanco_zona_id' => 29,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'LA PRESA', 'latitud' => '19.2907245', 'longitud' => '-70.7150636', 'transporte_blanco_zona_id' => 29,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'VILLA TAPIA', 'latitud' => '19.2991646', 'longitud' => '-70.4284894', 'transporte_blanco_zona_id' => 29,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'CUTUPU', 'latitud' => '19.3163339', 'longitud' => '-70.5430986', 'transporte_blanco_zona_id' => 29,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'CAYETANO GERMOSEN', 'latitud' => '19.3431138', 'longitud' => '-70.4890548', 'transporte_blanco_zona_id' => 29,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'RIO VERDE', 'latitud' => '19.3121186', 'longitud' => '-70.5133505', 'transporte_blanco_zona_id' => 29,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'MOCA', 'latitud' => '19.3942763', 'longitud' => '-70.5429725', 'transporte_blanco_zona_id' => 51,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'SALCEDO', 'latitud' => '19.3759312', 'longitud' => '-70.4303456', 'transporte_blanco_zona_id' => 51,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'TENARES', 'latitud' => '19.3742101', 'longitud' => '-70.3579796', 'transporte_blanco_zona_id' => 51,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'GUACI', 'latitud' => '19.4042773', 'longitud' => '-70.5210099', 'transporte_blanco_zona_id' => 51,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'LAS LAGUNAS', 'latitud' => '19.427214', 'longitud' => '-70.4791834', 'transporte_blanco_zona_id' => 51,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'JUAN LOPEZ', 'latitud' => '19.446501', 'longitud' => '-70.5137089', 'transporte_blanco_zona_id' => 51,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'SAN FRANCISCO', 'latitud' => '19.2981403', 'longitud' => '-70.2953268', 'transporte_blanco_zona_id' => 51,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'CENOVI', 'latitud' => '19.2534066', 'longitud' => '-70.3743536', 'transporte_blanco_zona_id' => 51,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'RANCHITO', 'latitud' => '19.1905917', 'longitud' => '-70.4426678', 'transporte_blanco_zona_id' => 51,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'SAJOMA', 'latitud' => '19.3407162', 'longitud' => '-70.9440422', 'transporte_blanco_zona_id' => 62,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'JANICO', 'latitud' => '19.3251661', 'longitud' => '-70.8208859', 'transporte_blanco_zona_id' => 62,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'SABANA IGLESIA', 'latitud' => '19.3252568', 'longitud' => '-70.7656647', 'transporte_blanco_zona_id' => 62,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'HATO DEL YAQUE', 'latitud' => '19.4426152', 'longitud' => '-70.8688602', 'transporte_blanco_zona_id' => 62,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'EL CAIMITO', 'latitud' => '19.3980543', 'longitud' => '-71.0100461', 'transporte_blanco_zona_id' => 62,
            'dias_de_entrega' => ['Monday']
        ],
        [ 'nombre' => 'RINCON DE PIEDRA', 'latitud' => '19.3983239', 'longitud' => '-71.164555', 'transporte_blanco_zona_id' => 62,
            'dias_de_entrega' => ['Monday']
        ],
        [ 'nombre' => 'LA PLACETA', 'latitud' => '19.2145018', 'longitud' => '-71.0219796', 'transporte_blanco_zona_id' => 62,
            'dias_de_entrega' => ['Monday']
        ],
        [ 'nombre' => 'JUAN DE HERRERA', 'latitud' => '18.8756241', 'longitud' => '-71.2415247', 'transporte_blanco_zona_id' => 57,
            'dias_de_entrega' => ['Monday', 'Wednesday', 'Friday']
        ],
        [ 'nombre' => 'MAGAYAL', 'latitud' => '', 'longitud' => '', 'transporte_blanco_zona_id' => 57,
            'dias_de_entrega' => ['Monday', 'Wednesday', 'Friday']
        ],
        [ 'nombre' => 'VILLA SABANA ALTA', 'latitud' => '18.8028141', 'longitud' => '-71.2439905', 'transporte_blanco_zona_id' => 57,
            'dias_de_entrega' => ['Monday', 'Wednesday', 'Friday']
        ],
        [ 'nombre' => 'JUANICO', 'latitud' => '19.3251661', 'longitud' => '-70.8208859', 'transporte_blanco_zona_id' => 57,
            'dias_de_entrega' => ['Monday', 'Wednesday', 'Friday']
        ],
        [ 'nombre' => 'PEDRO CORTO', 'latitud' => '18.8476553', 'longitud' => '-71.4146662', 'transporte_blanco_zona_id' => 57,
            'dias_de_entrega' => ['Tuesday', 'Thursday']
        ],
        [ 'nombre' => 'LAS MATAS DE FARFAN', 'latitud' => '18.8731522', 'longitud' => '-71.5381677', 'transporte_blanco_zona_id' => 57,
            'dias_de_entrega' => ['Tuesday', 'Thursday']
        ],
        [ 'nombre' => 'EL CERCADO', 'latitud' => '18.7292885', 'longitud' => '-71.5223802', 'transporte_blanco_zona_id' => 57,
            'dias_de_entrega' => ['Tuesday', 'Thursday']
        ],
        [ 'nombre' => 'ELIAS PIÑA', 'latitud' => '18.877001', 'longitud' => '-71.7058324', 'transporte_blanco_zona_id' => 57,
            'dias_de_entrega' => ['Tuesday', 'Thursday']
        ],
        [ 'nombre' => 'SAN JOSE DE LOS LLANOS', 'latitud' => '18.6235942', 'longitud' => '-69.4974947', 'transporte_blanco_zona_id' => 57,
            'dias_de_entrega' => ['Tuesday', 'Thursday']
        ],
        [ 'nombre' => 'EL CORBANO', 'latitud' => '18.8055979', 'longitud' => '-71.2579894', 'transporte_blanco_zona_id' => 57,
            'dias_de_entrega' => ['Tuesday', 'Thursday']
        ],
        [ 'nombre' => 'LICEY', 'latitud' => '19.4302651', 'longitud' => '-70.6252612', 'transporte_blanco_zona_id' => 64,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'TAMBORIL', 'latitud' => '19.4918172', 'longitud' => '-70.621691', 'transporte_blanco_zona_id' => 64,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'MAO', 'latitud' => '19.5496647', 'longitud' => '-71.1124716', 'transporte_blanco_zona_id' => 65,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'PUEBLO NUEVO DE MAO', 'latitud' => '19.5807153', 'longitud' => '-71.1761284', 'transporte_blanco_zona_id' => 65,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'LA CANELA', 'latitud' => '19.481314', 'longitud' => '-70.8894126', 'transporte_blanco_zona_id' => 65,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'AMINA', 'latitud' => '19.5462933', 'longitud' => '-71.0041475', 'transporte_blanco_zona_id' => 65,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'LAS MATAS DE SANTA CRUZ', 'latitud' => '19.6647948', 'longitud' => '-71.5152026', 'transporte_blanco_zona_id' => 65,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'CASTAÑUELA', 'latitud' => '19.7155756', 'longitud' => '-71.5046454', 'transporte_blanco_zona_id' => 65,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'GUAYUBIN', 'latitud' => '19.672554', 'longitud' => '-71.4041698', 'transporte_blanco_zona_id' => 65,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'CANA CHAPETON', 'latitud' => '19.6075462', 'longitud' => '-71.2585956', 'transporte_blanco_zona_id' => 65,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'MARTIN GARCIA', 'latitud' => '19.5896509', 'longitud' => '-71.4095021', 'transporte_blanco_zona_id' => 65,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'VILLA GONZALES', 'latitud' => '19.5504359', 'longitud' => '-70.8252183', 'transporte_blanco_zona_id' => 33,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'EL MAMEY', 'latitud' => '19.314727', 'longitud' => '-70.655401', 'transporte_blanco_zona_id' => 33,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'LA ISABELA', 'latitud' => '19.889855', 'longitud' => '-71.0804379', 'transporte_blanco_zona_id' => 33,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'GUANANICO', 'latitud' => '19.7236448', 'longitud' => '-70.9302664', 'transporte_blanco_zona_id' => 33,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'LUPERON', 'latitud' => '19.8924882', 'longitud' => '-70.9715511', 'transporte_blanco_zona_id' => 33,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'LA JAIBA', 'latitud' => '19.7982473', 'longitud' => '-71.1501431', 'transporte_blanco_zona_id' => 33,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'IMBERT', 'latitud' => '19.7550307', 'longitud' => '-70.8396078', 'transporte_blanco_zona_id' => 33,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'ESTERO HONDO', 'latitud' => '19.8262071', 'longitud' => '-71.1746478', 'transporte_blanco_zona_id' => 33,
            'dias_de_entrega' => ['Monday']
        ],
        [ 'nombre' => 'ALTA MIRA', 'latitud' => '19.6657021', 'longitud' => '-70.844393', 'transporte_blanco_zona_id' => 33,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'LA TURISTICA', 'latitud' => '', 'longitud' => '', 'transporte_blanco_zona_id' => 34,
            'dias_de_entrega' => ['Monday']
        ],
        [ 'nombre' => 'EL JOBO DE TENARES', 'latitud' => '19.4166667', 'longitud' => ' -70.3833333', 'transporte_blanco_zona_id' => 34,
            'dias_de_entrega' => ['Monday']
        ],
        [ 'nombre' => 'LOMA GASPAR HERNANDEZ', 'latitud' => '19.6133525', 'longitud' => '-70.2614253', 'transporte_blanco_zona_id' => 34,
            'dias_de_entrega' => ['Monday']
        ],
        [ 'nombre' => 'MOCA', 'latitud' => '19.3942763', 'longitud' => '-70.5429725', 'transporte_blanco_zona_id' => 40,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'SAN VICTOR', 'latitud' => '19.4541926', 'longitud' => '-70.5496853', 'transporte_blanco_zona_id' => 40,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'HIGUERITO', 'latitud' => '19.3704845', 'longitud' => '-70.6083588', 'transporte_blanco_zona_id' => 40,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'LAS GUAZUMAS', 'latitud' => '19.3649792', 'longitud' => '-70.5915146', 'transporte_blanco_zona_id' => 40,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ],
        [ 'nombre' => 'ESTANCIA NUEVA', 'latitud' => '19.4126668', 'longitud' => '-70.550294', 'transporte_blanco_zona_id' => 40,
            'dias_de_entrega' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
        ]
    ];

    public function run(): void
    {
        foreach ($this->pueblos as $pueblo){
            if (isset($pueblo['dias_de_entrega'])) {
                $pueblo['dias_de_entrega'] = json_encode($pueblo['dias_de_entrega']);
            }
            TransporteBlancoPueblo::factory()->create($pueblo);
        }
    }
}
