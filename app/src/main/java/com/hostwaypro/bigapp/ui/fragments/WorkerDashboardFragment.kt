package com.hostwaypro.bigapp.ui.fragments

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.fragment.app.Fragment
import androidx.fragment.app.viewModels
import androidx.lifecycle.lifecycleScope
import androidx.recyclerview.widget.LinearLayoutManager
import com.hostwaypro.bigapp.databinding.FragmentWorkerDashboardBinding
import com.hostwaypro.bigapp.ui.adapters.WorkRecordsAdapter
import com.hostwaypro.bigapp.ui.viewmodel.AuthViewModel
import com.hostwaypro.bigapp.ui.viewmodel.WorkerViewModel
import com.hostwaypro.bigapp.util.Resource
import dagger.hilt.android.AndroidEntryPoint
import kotlinx.coroutines.launch

import java.util.Locale

@AndroidEntryPoint
class WorkerDashboardFragment : Fragment() {

    private var _binding: FragmentWorkerDashboardBinding? = null
    private val binding get() = _binding!!
    private val authViewModel: AuthViewModel by viewModels()
    private val workerViewModel: WorkerViewModel by viewModels()
    private lateinit var adapter: WorkRecordsAdapter

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        _binding = FragmentWorkerDashboardBinding.inflate(inflater, container, false)
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)

        adapter = WorkRecordsAdapter()
        binding.rvWorkRecords.layoutManager = LinearLayoutManager(requireContext())
        binding.rvWorkRecords.adapter = adapter

        binding.btnLogout.setOnClickListener {
            authViewModel.signOut()
        }

        viewLifecycleOwner.lifecycleScope.launch {
            authViewModel.authState.collect { resource ->
                if (resource is Resource.Success) {
                    val user = resource.data
                    user?.let { workerViewModel.loadWorkRecords(it.uid) }
                }
            }
        }

        viewLifecycleOwner.lifecycleScope.launch {
            workerViewModel.workRecords.collect { resource ->
                when (resource) {
                    is Resource.Loading -> binding.progressBar.visibility = View.VISIBLE
                    is Resource.Success -> {
                        binding.progressBar.visibility = View.GONE
                        val records = resource.data ?: emptyList()
                        val total = records.sumOf { it.amount }
                        binding.tvTotalAmount.text = String.format(Locale.getDefault(), "%.2f DH", total)
                        if (::adapter.isInitialized) {
                            adapter.submitList(records)
                        }
                    }
                    is Resource.Error -> {
                        binding.progressBar.visibility = View.GONE
                    }
                }
            }
        }
    }

    override fun onDestroyView() {
        super.onDestroyView()
        _binding = null
    }
}
