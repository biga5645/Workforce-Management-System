package com.hostwaypro.bigapp.data.repository

import com.hostwaypro.bigapp.data.model.User
import com.hostwaypro.bigapp.data.model.UserStatus
import kotlinx.coroutines.flow.Flow

interface UserRepository {
    suspend fun getUser(uid: String): User?
    fun getUserFlow(uid: String): Flow<User?>
    suspend fun saveUser(user: User)
    suspend fun updateStatus(uid: String, status: UserStatus)
    fun getPendingWorkers(): Flow<List<User>>
    fun getAllActiveWorkers(): Flow<List<User>>
}
