package com.hostwaypro.bigapp.data.model

import com.google.firebase.firestore.ServerTimestamp
import java.util.Date

enum class UserRole {
    ADMIN, WORKER
}

enum class UserStatus {
    PENDING, ACTIVE, REJECTED, DISABLED
}

data class User(
    val uid: String = "",
    val name: String = "",
    val phone: String = "",
    val role: String = "WORKER", // Changed to String for Firestore compatibility
    val status: String = "PENDING", // Changed to String for Firestore compatibility
    val startDate: Date? = null,
    @ServerTimestamp
    val createdAt: Date? = null,
    @ServerTimestamp
    val updatedAt: Date? = null
) {
    // Helper properties to work with Enums in code
    fun getRoleEnum(): UserRole = try { UserRole.valueOf(role.uppercase()) } catch(e: Exception) { UserRole.WORKER }
    fun getStatusEnum(): UserStatus = try { UserStatus.valueOf(status.uppercase()) } catch(e: Exception) { UserStatus.PENDING }
}
