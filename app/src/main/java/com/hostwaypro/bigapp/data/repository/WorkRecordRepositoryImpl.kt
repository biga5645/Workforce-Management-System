package com.hostwaypro.bigapp.data.repository

import com.google.firebase.firestore.FirebaseFirestore
import com.google.firebase.firestore.Query
import com.hostwaypro.bigapp.data.model.WorkRecord
import kotlinx.coroutines.channels.awaitClose
import kotlinx.coroutines.flow.Flow
import kotlinx.coroutines.flow.callbackFlow
import kotlinx.coroutines.tasks.await
import java.util.Date
import javax.inject.Inject

class WorkRecordRepositoryImpl @Inject constructor(
    private val firestore: FirebaseFirestore
) : WorkRecordRepository {

    private val recordsCollection = firestore.collection("work_records")

    override suspend fun addWorkRecord(record: WorkRecord) {
        val docRef = recordsCollection.document()
        val recordWithId = record.copy(id = docRef.id)
        docRef.set(recordWithId).await()
    }

    override suspend fun addBulkWorkRecords(records: List<WorkRecord>) {
        val batch = firestore.batch()
        records.forEach { record ->
            val docRef = recordsCollection.document()
            batch.set(docRef, record.copy(id = docRef.id))
        }
        batch.commit().await()
    }

    override fun getWorkRecordsForWorker(workerId: String): Flow<List<WorkRecord>> = callbackFlow {
        val listener = recordsCollection
            .whereEqualTo("workerId", workerId)
            .orderBy("date", Query.Direction.DESCENDING)
            .addSnapshotListener { snapshot, error ->
                if (error != null) {
                    close(error)
                    return@addSnapshotListener
                }
                val records = snapshot?.toObjects(WorkRecord::class.java) ?: emptyList()
                trySend(records)
            }
        awaitClose { listener.remove() }
    }

    override fun getWorkRecordsByDateRange(startDate: Date, endDate: Date): Flow<List<WorkRecord>> = callbackFlow {
        val listener = recordsCollection
            .whereGreaterThanOrEqualTo("date", startDate)
            .whereLessThanOrEqualTo("date", endDate)
            .orderBy("date", Query.Direction.DESCENDING)
            .addSnapshotListener { snapshot, error ->
                if (error != null) {
                    close(error)
                    return@addSnapshotListener
                }
                val records = snapshot?.toObjects(WorkRecord::class.java) ?: emptyList()
                trySend(records)
            }
        awaitClose { listener.remove() }
    }

    override fun getWorkRecordsForWorkerByDateRange(
        workerId: String,
        startDate: Date,
        endDate: Date
    ): Flow<List<WorkRecord>> = callbackFlow {
        val listener = recordsCollection
            .whereEqualTo("workerId", workerId)
            .whereGreaterThanOrEqualTo("date", startDate)
            .whereLessThanOrEqualTo("date", endDate)
            .orderBy("date", Query.Direction.DESCENDING)
            .addSnapshotListener { snapshot, error ->
                if (error != null) {
                    close(error)
                    return@addSnapshotListener
                }
                val records = snapshot?.toObjects(WorkRecord::class.java) ?: emptyList()
                trySend(records)
            }
        awaitClose { listener.remove() }
    }
}
