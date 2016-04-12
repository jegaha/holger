<?php

namespace Holger;

use Holger\Entities\Link;
use Holger\Values\Byte;

class WANStats
{

    protected $endpoint = [
        'controlUri' => '/igdupnp/control/WANCommonIFC1',
        'uri' => 'urn:schemas-upnp-org:service:WANCommonInterfaceConfig:1',
        'scpdurl' => '/igdicfgSCPD.xml',
    ];

    use HasEndpoint;

    /**
     * Get info about the established WAN connection
     * returns upstream and downstream bitrate, link status and access type
     * @return Link
     */
    public function linkProperties()
    {
        $response = $this->prepareRequest()->GetCommonLinkProperties();

        return Link::fromResponse($response);
    }

    /**
     * Returns sent and received bytes since restart
     * @return array
     */
    public function byteStats()
    {
        $client = $this->prepareRequest();
        $sent = Byte::fromBytes($client->GetTotalBytesSent());
        $received = Byte::fromBytes($client->GetTotalBytesReceived());

        return compact('sent', 'received');
    }

    /**
     * Returns sent and received packages since restart
     * @return array
     */
    public function packetStats()
    {
        $client = $this->prepareRequest();

        return ['sent' => $client->GetTotalPacketsSent(), 'received' => $client->GetTotalPacketsReceived()];
    }
}
