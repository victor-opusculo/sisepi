<?php
require_once "database.php";
require_once "eventworkplans.uploadFiles.php";

function getEventWorkPlanAttachments(int $workPlanId, ?mysqli $optConnection = null)
{
    $conn = $optConnection ?? createConnectionAsEditor();

    $query = "SELECT * FROM eventworkplanattachments WHERE workPlanId = ? ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $workPlanId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $dataRows = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : null;
    $result->close();

    if (!$optConnection) $conn->close();
    return $dataRows;
}

function updateWorkPlanAttachments(int $workPlanId, array $postData, array $filePostData, ?mysqli $optConnection = null)
{
    $conn = $optConnection ?? createConnectionAsEditor();
    $changesReport = json_decode($postData['eventWorkPlanAttachmentsChangesReport']);
    $affectedRows = 0;
    if ($changesReport->delete)
        foreach ($changesReport->delete as $deleteReg)
        {
            $stmt = $conn->prepare('SELECT fileName FROM eventworkplanattachments WHERE id = ? ');
            $stmt->bind_param('i', $deleteReg->id);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            $attFileName = $result->fetch_assoc()['fileName'];
            $result->close();

            $stmt = $conn->prepare('DELETE FROM eventworkplanattachments WHERE id = ? ');
            $stmt->bind_param('i', $deleteReg->id);
            $stmt->execute();
            $affectedRows += $stmt->affected_rows;
            $stmt->close();

            Model\EventWorkPlanAttachments\deleteFile($workPlanId, $attFileName);
        }

    if ($changesReport->create)
        foreach ($changesReport->create as $createReg)
        {
            $fileInputElementName = $createReg->fileInputElementName;
            $fileName = basename($filePostData[$fileInputElementName]['name']);

            $uploadResult = Model\EventWorkPlanAttachments\uploadFile($workPlanId, $filePostData, $fileInputElementName);
            if ($uploadResult)
            {
                $stmt = $conn->prepare('INSERT INTO eventworkplanattachments (workPlanId, fileName) VALUES (?, ?) ');
                $stmt->bind_param('is', $workPlanId, $fileName);
                $stmt->execute();
                $affectedRows += $stmt->affected_rows;
                $stmt->close();
            }
        }

    Model\EventWorkPlanAttachments\checkForEmptyDir($workPlanId);

    if (!$optConnection) $conn->close();
    return $affectedRows;
}