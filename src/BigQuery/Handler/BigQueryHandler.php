<?php namespace BigQuery\Handler;

use Google_Auth_AssertionCredentials;
use Google_Client;
use Google_Service_Bigquery;
use Google_Service_Bigquery_TableDataInsertAllRequest;
use Google_Service_Bigquery_TableDataInsertAllRequestRows;
use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;

/**
 *  VERSION: 2.0
 */
class BigQueryHandler extends AbstractProcessingHandler
{
    private $client_email;
    private $private_key;
    private $project_id;
    private $dataset_id;
    private $table_id;
    private $bigquery;

    /**
     * @param $client_email
     * @param $private_key
     * @param $project_id
     * @param $dataset_id
     * @param $table_id
     * @param int $level The minimum logging level at which this handler will be triggered
     * @param Boolean $bubble Whether the messages that are handled can bubble up the stack or not
     */
    public function __construct($client_email, $private_key, $project_id, $dataset_id, $table_id, $level = Logger::DEBUG, $bubble = true)
    {
        $this->client_email = $client_email;
        $this->private_key = $private_key;
        $this->project_id = $project_id;
        $this->dataset_id = $dataset_id;
        $this->table_id = $table_id;


        $scopes = ['https://www.googleapis.com/auth/bigquery'];
        $credentials = new Google_Auth_AssertionCredentials(
            $client_email,
            $scopes,
            $private_key
        );

        $client = new Google_Client();
        $client->setAssertionCredentials($credentials);
        if ($client->getAuth()->isAccessTokenExpired()) {
            $client->getAuth()->refreshTokenWithAssertion();
        }

        $this->bigquery = new Google_Service_Bigquery($client);

        parent::__construct($level, $bubble);
    }

    /**
     * Writes the record down to the log of the implementing handler
     *
     * @param  array $record
     * @return void
     */
    protected function write(array $record)
    {
        $rows = [];
        $row = new Google_Service_Bigquery_TableDataInsertAllRequestRows;
        $row->setJson(['timestamp' => $record['datetime']->getTimestamp(), 'channel' => $record['channel'], 'message' => $record['message'], 'level' => $record['level_name']]);
        $row->setInsertId(strtotime('now'));
        $rows[0] = $row;

        $request = new Google_Service_Bigquery_TableDataInsertAllRequest;
        $request->setKind('bigquery#tableDataInsertAllRequest');
        $request->setRows($rows);

        $this->bigquery->tabledata->insertAll($this->project_id, $this->dataset_id, $this->table_id, $request);
    }
}
