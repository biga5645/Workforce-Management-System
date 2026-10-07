package com.hostwaypro.bigapp.data.repository

import com.hostwaypro.bigapp.data.model.WorkRecord
import kotlinx.coroutines.flow.Flow
import java.util.Date

interface WorkRecordRepository {
    suspend fun addWorkRecord(record: WorkRecord)
    suspend fun addBulkWorkRecords(records: List<WorkRecord>)
    fun getWorkRecordsForWorker(workerId: String): Flow<List<WorkRecord>>
    fun getWorkRecordsByDateRange(startDate: Date, endDate: Date): Flow<List<WorkRecord>>
    fun getWorkRecordsForWorkerByDateRange(workerId: String, startDate: Date, endDate: Date): Flow<List<WorkRecord>>
}
