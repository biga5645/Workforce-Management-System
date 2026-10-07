package com.hostwaypro.bigapp.data.repository

import com.google.firebase.auth.FirebaseUser
import kotlinx.coroutines.flow.Flow

interface AuthRepository {
    val currentUser: FirebaseUser?
    fun authStateFlow(): Flow<FirebaseUser?>
    suspend fun signIn(email: String, pass: String): Result<FirebaseUser>
    suspend fun signUp(email: String, pass: String): Result<FirebaseUser>
    fun signOut()
}
