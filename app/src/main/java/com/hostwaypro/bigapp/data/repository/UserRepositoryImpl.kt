package com.hostwaypro.bigapp.data.repository

import com.google.firebase.firestore.FirebaseFirestore
import com.google.firebase.firestore.toObject
import com.hostwaypro.bigapp.data.model.User
import com.hostwaypro.bigapp.data.model.UserRole
import com.hostwaypro.bigapp.data.model.UserStatus
import kotlinx.coroutines.channels.awaitClose
import kotlinx.coroutines.flow.Flow
import kotlinx.coroutines.flow.callbackFlow
import kotlinx.coroutines.tasks.await
import javax.inject.Inject

class UserRepositoryImpl @Inject constructor(
    private val firestore: FirebaseFirestore
) : UserRepository {

    private val usersCollection = firestore.collection("users")

    override suspend fun getUser(uid: String): User? {
        return usersCollection.document(uid).get().await().toObject<User>()
    }

    override fun getUserFlow(uid: String): Flow<User?> = callbackFlow {
        val listener = usersCollection.document(uid).addSnapshotListener { snapshot, error ->
            if (error != null) {
                close(error)
                return@addSnapshotListener
            }
            trySend(snapshot?.toObject<User>())
        }
        awaitClose { listener.remove() }
    }

    override suspend fun saveUser(user: User) {
        usersCollection.document(user.uid).set(user).await()
    }

    override suspend fun updateStatus(uid: String, status: UserStatus) {
        usersCollection.document(uid).update("status", status.name).await()
    }

    override fun getPendingWorkers(): Flow<List<User>> = callbackFlow {
        val listener = usersCollection
            .whereEqualTo("role", UserRole.WORKER.name)
            .whereEqualTo("status", UserStatus.PENDING.name)
            .addSnapshotListener { snapshot, error ->
                if (error != null) {
                    close(error)
                    return@addSnapshotListener
                }
                val workers = snapshot?.toObjects(User::class.java) ?: emptyList()
                trySend(workers)
            }
        awaitClose { listener.remove() }
    }

    override fun getAllActiveWorkers(): Flow<List<User>> = callbackFlow {
        val listener = usersCollection
            .whereEqualTo("role", UserRole.WORKER.name)
            .whereEqualTo("status", UserStatus.ACTIVE.name)
            .addSnapshotListener { snapshot, error ->
                if (error != null) {
                    close(error)
                    return@addSnapshotListener
                }
                val workers = snapshot?.toObjects(User::class.java) ?: emptyList()
                trySend(workers)
            }
        awaitClose { listener.remove() }
    }
}
