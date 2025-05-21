<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    @php
        $logo = base64_encode(file_get_contents(public_path('/carga_mundo_express_horizontal.png')));
    @endphp

    <style>

        @page {
            size: 4in 6in;
            margin: 0.25in;
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

        .headerTicket {
            text-align: center;
        }

        .headerTicket > p {
            font-size: 8pt;
        }

        .remitenteHeader {
            margin-top: 5pt;
            text-align: center;
            width: 100%;
        }

        .remitenteHeader > h3 {
            border-left: solid 1pt #000000;
            border-right: solid 1pt #000000;
            border-top: solid 1pt #000000;
            width: 120pt;
            margin: 0 auto;
            padding: 3pt 0;
            border-radius: 13pt 13pt 0 0;
        }

        .remitenteBody {
            margin-top: 0;
            text-align: center;
            width: 100%;
            height: 25pt;
            border: solid 1pt #000000;
            border-radius: 10pt;
            display: table;
        }

        .remitenteBody > p {
            margin: auto 0;
            display: table-cell;
            vertical-align: middle;
        }

        .consolidado {
            border: solid 1pt #000000;
            padding: 2pt 5pt;
            border-radius: 10pt;
            margin-top: 5pt;
        }

        .infoCard3 {
            border: solid 1pt #000000;
            border-radius: 7pt;
            text-align: center;
            height: 30pt;
            padding-top: 2pt;
        }

        .infoCard3 > h3 {
            font-size: 12pt;
        }

        .infoCardDesc3 {
            overflow: hidden;
            height: 10.5pt;
        }

        .codigoBarra {
            width: 100%;
            text-align: center;
            margin-top: 10pt
        }

    </style>
</head>
<body>
    <div class="headerTicket">
        <img src="data:image/png;base64,{{ $logo }}" alt="Cargo mundo express logo" style="width: 80%; margin-top: 0; padding-top: 0; margin-bottom: 2pt;">

        <p>EE.UU address: 11sw 107th Ave, Miami, FL 33174</p>
        <p>Tel: 786-860-5461</p>
        <p>Nicaragua address: Managua, Bolonia, del canal 2, 2c arriba, 1/2c al sur frente a casa Nazaret</p>
        <p>Tel: +505 8610-7041 / 2228-4354</p>
    </div>

    <div class="remitenteHeader">
        <h3>Remitente</h3>
    </div>

    <div class="remitenteBody">
        <p>{{ $ticket->remitente }}</p>
    </div>

    <div class="remitenteHeader">
        <h3>Destinatario</h3>
    </div>

    <div class="remitenteBody">
        <p>{{ $ticket->destinatario }}</p>
    </div>

    <div class="consolidado">
        <table>
            <tr>
                <td style="text-align: left; width: 70%; border-right: solid 1pt #000000;">
                    <h3 style="font-size: 18pt;">Consolidado #</h3>
                </td>

                <td style="text-align: center; width: 30%;">
                    <p style="font-weight: bold; font-size: 15pt;">{{ $ticket->consolidado }}</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="codigoBarra">
        <img src="{{ $barcode }}" alt="Codigo de barra">
    </div>

    <table style="margin-top: 2pt;">
        <tr>
            <td style="width: 33%;">
                <div class="infoCard3">
                    <h3>Fecha</h3>

                    <div class="infoCardDesc3">
                        <p>{{ $ticket->fecha }}</p>
                    </div>
                </div>
            </td>

            <td style="width: 33%;">
                <div class="infoCard3">
                    <h3>Cantidad</h3>

                    <div class="infoCardDesc3">
                        <p>{{ $ticket->cantidad }}</p>
                    </div>
                </div>
            </td>

            <td style="width: 33%;">
                <div class="infoCard3">
                    <h3>Peso</h3>

                    <div class="infoCardDesc3">
                        <p>{{ $ticket->peso }} lb</p>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td style="width: 50%;">
                <div class="infoCard3">
                    <h3>Origen</h3>

                    <div class="infoCardDesc3">
                        <p>{{ $ticket->origen }}</p>
                    </div>
                </div>
            </td>

            <td style="width: 50%;">
                <div class="infoCard3">
                    <h3>Destino</h3>

                    <div class="infoCardDesc3">
                        <p>{{ $ticket->destino }}</p>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td style="width: 50%;">
                <div class="infoCard3">
                    <h3>Transporte</h3>

                    <div class="infoCardDesc3">
                        <p>{{ $ticket->transporte }}</p>
                    </div>
                </div>
            </td>

            <td style="width: 50%;">
                <div class="infoCard3">
                    <h3>Agencia</h3>

                    <div class="infoCardDesc3">
                        <p style="font-size: 8pt;">{{ $ticket->agencia }}</p>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</body>