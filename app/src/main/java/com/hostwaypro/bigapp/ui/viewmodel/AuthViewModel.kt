package com.hostwaypro.bigapp.ui.viewmodel

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.hostwaypro.bigapp.data.model.User
import com.hostwaypro.bigapp.data.model.UserRole
import com.hostwaypro.bigapp.data.model.UserStatus
import com.hostwaypro.bigapp.data.repository.AuthRepository
import com.hostwaypro.bigapp.data.repository.UserRepository
import com.hostwaypro.bigapp.util.Resource
import dagger.hilt.android.lifecycle.HiltViewModel
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.flow.asStateFlow
import kotlinx.coroutines.launch
import java.util.Date
import javax.inject.Inject

@HiltViewModel
class AuthViewModel @Inject constructor(
    private val authRepository: AuthRepository,
    private val userRepository: UserRepository
) : ViewModel() {

    private val _authState = MutableStateFlow<Resource<User?>>(Resource.Loading)
    val authState: StateFlow<Resource<User?>> = _authState.asStateFlow()

    init {
        viewModelScope.launch {
            authRepository.authStateFlow().collect { firebaseUser ->
                if (firebaseUser == null) {
                    _authState.value = Resource.Success(null)
                } else {
                    val user = userRepository.getUser(firebaseUser.uid)
                    _authState.value = Resource.Success(user)
                }
            }
        }
    }

    fun signIn(email: String, pass: String) {
        viewModelScope.launch {
            _authState.value = Resource.Loading
            val result = authRepository.signIn(email, pass)
            result.onSuccess { firebaseUser ->
                val user = userRepository.getUser(firebaseUser.uid)
                _authState.value = Resource.Success(user)
            }.onFailure {
                _authState.value = Resource.Error(it.message ?: "Sign in failed")
            }
        }
    }

    fun signUp(email: String, pass: String, name: String, phone: String, startDate: Date) {
        viewModelScope.launch {
            _authState.value = Resource.Loading
            val result = authRepository.signUp(email, pass)
            result.onSuccess { firebaseUser ->
                // Check if this is the designated admin account
                val isAdminAccount = email.equals("biga.onee@gmail.com", ignoreCase = true) || email.equals("admin@bigapp.com", ignoreCase = true)
                
                val newUser = User(
                    uid = firebaseUser.uid,
                    name = name,
                    phone = phone,
                    role = if (isAdminAccount) "ADMIN" else "WORKER",
                    status = if (isAdminAccount) "ACTIVE" else "PENDING",
                    startDate = startDate
                )
                userRepository.saveUser(newUser)
                _authState.value = Resource.Success(newUser)
            }.onFailure {
                _authState.value = Resource.Error(it.message ?: "Sign up failed")
            }
        }
    }

    fun signOut() {
        authRepository.signOut()
        _authState.value = Resource.Success(null)
    }
}
