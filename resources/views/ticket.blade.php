<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <style>
        @page {
            size: 4in 6in;
            margin: 0.125in 0.25in;
        }

        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }

        h1 {
            font-size: 15pt;
            text-transform: uppercase;
            width: 125pt;
            margin: 0;
        }

        h2 {
            font-size: 20pt;
            margin: 0;
        }

        h3 {
            margin: 0;
            font-size: 13pt;
            text-transform: uppercase;
        }

        p {
            margin: 0;
            padding: 0;
            font-size: 11pt;
            text-transform: uppercase;
        }

        table {
            width: 100%;
        }

        .mtop5 {
            margin-top: 5pt;
        }

        .ticket-preview {
            font-size: 18pt;
            width: 4in;
            height: 6in;
            border: solid 1px #000000;
            margin: 20px auto;
            box-sizing: border-box;
            overflow: hidden;
            padding: 0 10pt;
        }

        .separador {
            margin-top: 5pt;
            border-bottom: solid 2pt #000000;
        }

        .ticketHeader {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            width: 100%;
            border-bottom: solid 2pt #000000;
        }

        .ticketHeader > h1 {
            font-size: 15pt;
            text-transform: uppercase;
            width: 125pt;
        }

        .ticketHeader > h2 {
            font-size: 20pt;
        }

        .ticketDate {
            display: flex;
            justify-content: space-between;
        }

        .trackingCode {
            text-align: center;
            font-weight: 700;
            margin: 0;
            font-size: 25pt;
        }

        .destinoDir {
            text-align: center;
            font-size: 17pt;
        }

        .medidasProd {
            display: flex;
            justify-content: space-between;
            border-bottom: solid 2pt #000000;
        }

        .vweight {
            border-left: solid 2pt #000000;
            border-right: solid 2pt #000000;
            padding-left: 5pt;
            padding-right: 5pt;
        }

        .prodDescr {
            display: flex;
            justify-content: space-between;
        }

        .item {
            border-right: solid 2pt #000000;
            padding-right: 20pt;
        }

        .codigoBarra {
            width: 100%;
            text-align: center;
            margin-top: 10pt
        }
    </style>
</head>
<body>
    <div class="ticketHeader">
        <table>
            <tr>
                <td style="text-align: left; width: 60%;">
                    <h1>Cargo mundo express</h1>
                </td>

                <td style="text-align: right; width: 40%;">
                    <h2>{{ $ticket->transporte }}</h2>
                </td>
            </tr>
        </table>
    </div>

    <h3>consignatario</h3>
    <p>Cargo mundo express/Cesar Chavarria</p>
    <p class="mtop5">Reparto bolonia del canal 2, 2 calles arriba, 1/2 calle al sur, frente casa nazaret</p>
    <p class="mtop5">+50522877041</p>

    <div class="separador"></div>

    <p>cargo mundo express/Cesar Chavarria</p>

    <table>
        <tr>
            <td style="text-align: left;">
                <h3>Warehouse</h3>
            </td>

            <td style="text-align: right;">
                <h3>{{ $ticket->fecha }}</h3>
            </td>
        </tr>
    </table>

    <h2 class="trackingCode">{{ $ticket->codigoproducto }}</h3>

    <div class="separador"></div>

    <h3>Destino</h3>

    <p class="destinoDir">{{ $ticket->destino }}</p>

    <table>
        <tr>
            <td style="width: 25%; border-right: solid 2pt #000000; text-align: center;">
                <p>Weight</p>
                <p>{{ $ticket->weight }}</p>
            </td>

            <td style="width: 25%; border-right: solid 2pt #000000; text-align: center;">
                <p>Vweight</p>
                <p>{{ $ticket->vweight }}</p>
            </td>

            <td style="width: 50%; text-align: center;">
                <p>Dimensions</p>
                <p>{{ $ticket->dimensiones }}</p>
            </td>
        </tr>
    </table>

    <p>{{ $ticket->interncode }}</p>

    <div class="separador"></div>

    <table>
        <tr>
            <td style="width: 40%; border-right: solid 2pt #000000; text-align: left; padding-right: 10pt; vertical-align: top;">
                <p>Item</p>
                <p>{{ $ticket->codigoproducto }}</p>
            </td>

            <td style="width: 60%; text-align: justify; padding: 0 10pt;">
                <p>Descripcion</p>
                <p style="font-size: 9pt;">{{ $ticket->descripcion }}</p>
            </td>
        </tr>
    </table>

    <div class="codigoBarra">
        <img src="{{ $barcode }}" alt="Codigo de barra">
        <p>{{ $ticket->codigoproducto }}</p>
    </div>
</body>
</html>