package com.hostwaypro.bigapp.ui.viewmodel

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.hostwaypro.bigapp.data.model.User
import com.hostwaypro.bigapp.data.model.UserStatus
import com.hostwaypro.bigapp.data.model.WorkRecord
import com.hostwaypro.bigapp.data.repository.UserRepository
import com.hostwaypro.bigapp.data.repository.WorkRecordRepository
import com.hostwaypro.bigapp.util.Resource
import dagger.hilt.android.lifecycle.HiltViewModel
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.flow.asStateFlow
import kotlinx.coroutines.flow.collectLatest
import kotlinx.coroutines.launch
import java.util.Date
import javax.inject.Inject

@HiltViewModel
class AdminViewModel @Inject constructor(
    private val userRepository: UserRepository,
    private val workRecordRepository: WorkRecordRepository
) : ViewModel() {

    private val _pendingWorkers = MutableStateFlow<Resource<List<User>>>(Resource.Loading)
    val pendingWorkers: StateFlow<Resource<List<User>>> = _pendingWorkers.asStateFlow()

    private val _activeWorkers = MutableStateFlow<Resource<List<User>>>(Resource.Loading)
    val activeWorkers: StateFlow<Resource<List<User>>> = _activeWorkers.asStateFlow()

    init {
        loadPendingWorkers()
        loadActiveWorkers()
    }

    fun loadPendingWorkers() {
        viewModelScope.launch {
            userRepository.getPendingWorkers().collectLatest {
                _pendingWorkers.value = Resource.Success(it)
            }
        }
    }

    fun loadActiveWorkers() {
        viewModelScope.launch {
            userRepository.getAllActiveWorkers().collectLatest {
                _activeWorkers.value = Resource.Success(it)
            }
        }
    }

    fun approveWorker(uid: String) {
        viewModelScope.launch {
            userRepository.updateStatus(uid, UserStatus.ACTIVE)
        }
    }

    fun rejectWorker(uid: String) {
        viewModelScope.launch {
            userRepository.updateStatus(uid, UserStatus.REJECTED)
        }
    }

    fun saveBulkWorkRecords(records: List<WorkRecord>) {
        viewModelScope.launch {
            workRecordRepository.addBulkWorkRecords(records)
        }
    }
}
